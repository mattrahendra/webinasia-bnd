<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\TemplateImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $query = TemplateImage::query();

        if ($request->has('template_id')) {
            $query->where('template_id', $request->template_id);
        }

        if ($request->boolean('with_template')) {
            $query->with('template');
        }

        $images = $query->paginate($request->get('per_page', 15));

        return view('template-images.index', compact('images'));
    }

    public function create()
    {
        $templates = Template::all();
        return view('template-images.create', compact('templates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:templates,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $request->file('image')->store('template-images', 'public');

        TemplateImage::create([
            'template_id' => $request->template_id,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('template-images.index')->with('success', 'Template image created successfully');
    }

    public function show(TemplateImage $templateImage, Request $request)
    {
        if ($request->boolean('with_template')) {
            $templateImage->load('template');
        }

        return view('template-images.show', compact('templateImage'));
    }

    public function edit(TemplateImage $templateImage)
    {
        $templates = Template::all();
        return view('template-images.edit', compact('templateImage', 'templates'));
    }

    public function update(Request $request, TemplateImage $templateImage)
    {
        $request->validate([
            'template_id' => 'sometimes|required|exists:templates,id',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['template_id']);

        if ($request->hasFile('image')) {
            if ($templateImage->image_path) {
                Storage::disk('public')->delete($templateImage->image_path);
            }
            $data['image_path'] = $request->file('image')->store('template-images', 'public');
        }

        $templateImage->update($data);

        return redirect()->route('template-images.show', $templateImage)->with('success', 'Template image updated successfully');
    }

    public function destroy(TemplateImage $templateImage)
    {
        if ($templateImage->image_path) {
            Storage::disk('public')->delete($templateImage->image_path);
        }

        $templateImage->delete();

        return redirect()->route('template-images.index')->with('success', 'Template image deleted successfully');
    }
}
