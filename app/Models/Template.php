<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $fillable = ['name', 'description', 'category_id', 'price'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(TemplateImage::class);
    }

    public function projects()
    {
        return $this->hasMany(TemplateProject::class);
    }

    public function getMainImageAttribute()
    {
        return $this->images()->first()?->image_path;
    }
}
