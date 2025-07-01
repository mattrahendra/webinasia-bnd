<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index(Request $request)
    {
        $query = Template::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->boolean('with_category')) {
            $query->with('category');
        }
        if ($request->boolean('with_images')) {
            $query->with('images');
        }
        if ($request->boolean('with_projects')) {
            $query->with('projects');
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $query->orderBy($sortBy, $sortDirection);

        $perPage = $request->get('per_page', 15);
        $templates = $query->paginate($perPage);

        return view('templates.index', compact('templates'));
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

        return view('templates.show', compact('template'));
    }
}
