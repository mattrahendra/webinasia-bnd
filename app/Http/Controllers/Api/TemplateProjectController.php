<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TemplateProjectResource;
use App\Models\TemplateProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateProjectController extends Controller
{
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

        return TemplateProjectResource::collection($projects);
    }

    public function store(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:templates,id',
            'project_file' => 'required|file|mimes:zip,rar|max:10240', // 10MB
            'preview_files' => 'nullable|array',
            'preview_files.*' => 'file|mimes:html,css,js,png,jpg,jpeg',
        ]);

        $projectFilePath = $request->file('project_file')->store('template-projects', 'public');

        $data = [
            'template_id' => $request->template_id,
            'project_file' => $projectFilePath,
        ];

        // Handle preview files
        if ($request->hasFile('preview_files')) {
            $previewPath = 'template-previews/' . uniqid();
            foreach ($request->file('preview_files') as $file) {
                $file->store($previewPath, 'public');
            }
            $data['preview_path'] = $previewPath;
        }

        $templateProject = TemplateProject::create($data);

        return new TemplateProjectResource($templateProject);
    }

    public function show(TemplateProject $templateProject, Request $request)
    {
        if ($request->boolean('with_template')) {
            $templateProject->load('template');
        }

        return new TemplateProjectResource($templateProject);
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
            // Delete old file
            if ($templateProject->project_file) {
                Storage::disk('public')->delete($templateProject->project_file);
            }

            $data['project_file'] = $request->file('project_file')->store('template-projects', 'public');
        }

        if ($request->hasFile('preview_files')) {
            // Delete old preview files
            if ($templateProject->preview_path) {
                Storage::disk('public')->deleteDirectory($templateProject->preview_path);
            }

            // Store new preview files
            $previewPath = 'template-previews/' . uniqid();
            foreach ($request->file('preview_files') as $file) {
                $file->store($previewPath, 'public');
            }
            $data['preview_path'] = $previewPath;
        }

        $templateProject->update($data);

        return new TemplateProjectResource($templateProject);
    }

    public function destroy(TemplateProject $templateProject)
    {
        // Delete files
        if ($templateProject->project_file) {
            Storage::disk('public')->delete($templateProject->project_file);
        }
        if ($templateProject->preview_path) {
            Storage::disk('public')->deleteDirectory($templateProject->preview_path);
        }

        $templateProject->delete();

        return response()->json([
            'message' => 'Template project deleted successfully',
        ]);
    }
}
