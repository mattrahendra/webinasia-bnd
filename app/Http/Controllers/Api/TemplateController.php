<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTemplateRequest;
use App\Http\Requests\UpdateTemplateRequest;
use App\Http\Resources\TemplateResource;
use App\Models\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index(Request $request)
    {
        $query = Template::query();

        // Search functionality
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Price range filter
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Include relationships
        if ($request->boolean('with_category')) {
            $query->with('category');
        }
        if ($request->boolean('with_images')) {
            $query->with('images');
        }
        if ($request->boolean('with_projects')) {
            $query->with('projects');
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $templates = $query->paginate($perPage);

        return TemplateResource::collection($templates);
    }

    public function store(StoreTemplateRequest $request)
    {
        $template = Template::create($request->validated());

        return new TemplateResource($template);
    }

    public function show(Template $template, Request $request)
    {
        $with = [];
        if ($request->boolean('with_category')) $with[] = 'category';
        if ($request->boolean('with_images')) $with[] = 'images';
        if ($request->boolean('with_projects')) $with[] = 'projects';

        if (!empty($with)) {
            $template->load($with);
        }

        return new TemplateResource($template);
    }

    public function update(UpdateTemplateRequest $request, Template $template)
    {
        $template->update($request->validated());

        return new TemplateResource($template);
    }

    public function destroy(Template $template)
    {
        $template->delete();

        return response()->json([
            'message' => 'Template deleted successfully',
        ]);
    }
}
