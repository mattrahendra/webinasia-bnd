<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TemplateImageResource;
use App\Models\TemplateImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateImageController extends Controller
{
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

        return TemplateImageResource::collection($images);
    }

    public function store(Request $request)
    {
        $request->validate([
            'template_id' => 'required|exists:templates,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $request->file('image')->store('template-images', 'public');

        $templateImage = TemplateImage::create([
            'template_id' => $request->template_id,
            'image_path' => $imagePath,
        ]);

        return new TemplateImageResource($templateImage);
    }

    public function show(TemplateImage $templateImage, Request $request)
    {
        if ($request->boolean('with_template')) {
            $templateImage->load('template');
        }

        return new TemplateImageResource($templateImage);
    }

    public function update(Request $request, TemplateImage $templateImage)
    {
        $request->validate([
            'template_id' => 'sometimes|required|exists:templates,id',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['template_id']);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($templateImage->image_path) {
                Storage::disk('public')->delete($templateImage->image_path);
            }

            // Store new image
            $data['image_path'] = $request->file('image')->store('template-images', 'public');
        }

        $templateImage->update($data);

        return new TemplateImageResource($templateImage);
    }

    public function destroy(TemplateImage $templateImage)
    {
        // Delete image file
        if ($templateImage->image_path) {
            Storage::disk('public')->delete($templateImage->image_path);
        }

        $templateImage->delete();

        return response()->json([
            'message' => 'Template image deleted successfully',
        ]);
    }
}
