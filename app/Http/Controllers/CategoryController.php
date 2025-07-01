<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        if ($request->boolean('with_templates_count')) {
            $query->withCount('templates');
        }

        if ($request->boolean('with_templates')) {
            $query->with('templates');
        }

        $perPage = $request->get('per_page', 15);
        $categories = $query->paginate($perPage);

        return view('categories.index', compact('categories'));
    }

    public function show(Category $category, Request $request)
    {
        if ($request->boolean('with_templates')) {
            $category->load('templates');
        }

        return view('categories.show', compact('category'));
    }
}
