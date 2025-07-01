<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\TemplateProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $query = TemplateProject::query();

        if ($request->has('template_id')) {
            $query->where('template_id', $request->template_id);
        }

        if ($request->boolean('with_template')) {
            $query->with('template');
        }

        $projects = $query->paginate($request->get('per_page', 15));

        return view('template-projects.index', compact('projects'));
    }

    public function create()
    {
        $templates = Template::all();
        return view('template-projects.create', compact('templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:templates,id',
            'project_file' => 'required|file|mimes:zip,rar|max:10240',
            'preview_files' => 'nullable|array',
            'preview_files.*' => 'file|mimes:html,css,js,png,jpg,jpeg',
        ]);

        $projectFilePath = $request->file('project_file')->store('template-projects', 'public');

        $data = [
            'template_id' => $request->template_id,
            'project_file' => $projectFilePath,
        ];

        if ($request->hasFile('preview_files')) {
            $previewPath = 'template-previews/' . uniqid();
            foreach ($request->file('preview_files') as $file) {
                $file->store($previewPath, 'public');
            }
            $data['preview_path'] = $previewPath;
        }

        TemplateProject::create($data);

        return redirect()->route('template-projects.index')->with('success', 'Template project created successfully');
    }

    public function show(TemplateProject $templateProject, Request $request)
    {
        if ($request->boolean('with_template')) {
            $templateProject->load('template');
        }

        return view('template-projects.show', compact('templateProject'));
    }

    public function edit(TemplateProject $templateProject)
    {
        $templates = Template::all();
        return view('template-projects.edit', compact('templateProject', 'templates'));
    }

    public function update(Request $request, TemplateProject $templateProject)
    {
        $request->validate([
            'template_id' => 'sometimes|required|exists:templates,id',
            'project_file' => 'sometimes|file|mimes:zip,rar|max:10240',
            'preview_files' => 'nullable|array',
            'preview_files.*' => 'file|mimes:html,css,js,png,jpg,jpeg',
        ]);

        $data = $request->only(['template_id']);

        if ($request->hasFile('project_file')) {
            if ($templateProject->project_file) {
                Storage::disk('public')->delete($templateProject->project_file);
            }
            $data['project_file'] = $request->file('project_file')->store('template-projects', 'public');
        }

        if ($request->hasFile('preview_files')) {
            if ($templateProject->preview_path) {
                Storage::disk('public')->deleteDirectory($templateProject->preview_path);
            }
            $previewPath = 'template-previews/' . uniqid();
            foreach ($request->file('preview_files') as $file) {
                $file->store($previewPath, 'public');
            }
            $data['preview_path'] = $previewPath;
        }

        $templateProject->update($data);

        return redirect()->route('template-projects.show', $templateProject)->with('success', 'Template project updated successfully');
    }

    public function destroy(TemplateProject $templateProject)
    {
        if ($templateProject->project_file) {
            Storage::disk('public')->delete($templateProject->project_file);
        }
        if ($templateProject->preview_path) {
            Storage::disk('public')->deleteDirectory($templateProject->preview_path);
        }

        $templateProject->delete();

        return redirect()->route('template-projects.index')->with('success', 'Template project deleted successfully');
    }
}
