<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateImage extends Model
{
    protected $fillable = ['template_id', 'image_path'];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }
}
