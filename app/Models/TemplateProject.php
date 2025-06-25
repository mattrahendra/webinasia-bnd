<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateProject extends Model
{
    protected $fillable = ['template_id', 'project_file', 'preview_path'];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function getPreviewUrlAttribute()
    {
        return $this->preview_path ? \Illuminate\Support\Facades\Storage::url($this->preview_path . '/index.html') : null;
    }
}
