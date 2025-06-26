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
        $query = Template::with(['category', 'images', 'projects']);

        // Filter berdasarkan kategori
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Search berdasarkan nama
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter berdasarkan harga
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $templates = $query->paginate(15);
        return TemplateResource::collection($templates);
    }

    public function store(StoreTemplateRequest $request)
    {
        $template = Template::create($request->validated());
        $template->load(['category', 'images', 'projects']);
        return new TemplateResource($template);
    }

    public function show(Template $template)
    {
        $template->load(['category', 'images', 'projects']);
        return new TemplateResource($template);
    }

    public function update(UpdateTemplateRequest $request, Template $template)
    {
        $template->update($request->validated());
        $template->load(['category', 'images', 'projects']);
        return new TemplateResource($template);
    }

    public function destroy(Template $template)
    {
        $template->delete();
        return response()->json(['message' => 'Template deleted successfully']);
    }
}
