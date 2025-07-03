<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::withCount('templates')->get();
        $selectedCategoryId = $request->get('category_id');

        $query = Template::with(['category', 'images']);

        // Filter by category if selected
        if ($selectedCategoryId && $selectedCategoryId !== 'all') {
            $query->where('category_id', $selectedCategoryId);
        }

        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $perPage = $request->get('per_page', 12);
        $templates = $query->paginate($perPage);

        // Get selected category for display
        $selectedCategory = null;
        if ($selectedCategoryId && $selectedCategoryId !== 'all') {
            $selectedCategory = Category::find($selectedCategoryId);
        }

        return view('templates.index', compact('templates', 'categories', 'selectedCategoryId', 'selectedCategory'));
    }

    public function show(Template $template, Request $request)
    {
        $template->load(['category', 'images', 'projects']);
        return view('templates.show', compact('template'));
    }

    public function preview(Template $template)
    {
        // Load relasi projects untuk template ini
        $template->load('projects');

        // Cari project yang memiliki preview_path dan tidak kosong
        $templateProject = $template->projects()
            ->whereNotNull('preview_path')
            ->where('preview_path', '!=', '')
            ->first();

        if (!$templateProject) {
            return redirect()->back()->with('error', 'Preview tidak tersedia untuk template ini');
        }

        // Cek apakah template project memiliki preview yang valid
        if (!$templateProject->hasPreview()) {
            return redirect()->back()->with('error', 'File preview tidak ditemukan untuk template ini');
        }

        // Gunakan accessor yang sudah didefinisikan di model TemplateProject
        $previewUrl = $templateProject->preview_url;

        return view('templates.preview', compact('template', 'templateProject', 'previewUrl'));
    }

    public function selectTemplate(Template $template)
    {
        // Redirect to order creation with template pre-selected
        return redirect()->route('orders.create', ['template_id' => $template->id]);
    }

    // Tambahkan method ini ke TemplateController untuk debugging

    public function debugPreview(Template $template)
    {
        $template->load('projects');

        $templateProject = $template->projects()
            ->whereNotNull('preview_path')
            ->where('preview_path', '!=', '')
            ->first();

        if (!$templateProject) {
            dd('No template project found for template ID: ' . $template->id);
        }

        // Cek folder dan file yang ada
        $previewPath = $templateProject->preview_path;
        $directories = Storage::disk('public')->directories($previewPath);
        $files = Storage::disk('public')->files($previewPath);

        // Cek setiap subfolder untuk index.html
        $foundIndexPaths = [];
        foreach ($directories as $directory) {
            $indexPath = $directory . '/index.html';
            if (Storage::disk('public')->exists($indexPath)) {
                $foundIndexPaths[] = $indexPath;
            }
        }

        $info = [
            'template_id' => $template->id,
            'template_name' => $template->name,
            'project_id' => $templateProject->id,
            'project_file' => $templateProject->project_file,
            'preview_path' => $templateProject->preview_path,
            'direct_index_exists' => Storage::disk('public')->exists($previewPath . '/index.html'),
            'directories_in_preview_path' => $directories,
            'files_in_preview_path' => $files,
            'found_index_files' => $foundIndexPaths,
            'final_preview_url' => $templateProject->preview_url,
            'has_preview' => $templateProject->hasPreview(),
            'storage_base_path' => Storage::disk('public')->path(''),
        ];

        dd($info);
    }
}
