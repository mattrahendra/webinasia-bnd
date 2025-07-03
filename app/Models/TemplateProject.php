<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TemplateProject extends Model
{
    protected $fillable = ['template_id', 'project_file', 'preview_path'];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function getPreviewUrlAttribute()
    {
        if (!$this->preview_path) {
            return null;
        }

        // Cari index.html di preview_path
        $indexPath = $this->findIndexHtml();

        if ($indexPath && Storage::disk('public')->exists($indexPath)) {
            return Storage::disk('public')->url($indexPath);
        }

        return null;
    }

    public function getProjectFileUrlAttribute()
    {
        if (!$this->project_file) {
            return null;
        }

        return Storage::disk('public')->url($this->project_file);
    }

    public function hasPreview()
    {
        return !empty($this->preview_path) &&
            !is_null($this->findIndexHtml()) &&
            Storage::disk('public')->exists($this->findIndexHtml());
    }

    /**
     * Mencari file index.html dalam folder preview_path
     * Memeriksa langsung di preview_path dan juga di subfolder
     */
    private function findIndexHtml()
    {
        if (!$this->preview_path) {
            return null;
        }

        // Cek langsung di preview_path
        $directPath = $this->preview_path . '/index.html';
        if (Storage::disk('public')->exists($directPath)) {
            return $directPath;
        }

        // Cek di subfolder (hasil ekstrak zip)
        $directories = Storage::disk('public')->directories($this->preview_path);

        foreach ($directories as $directory) {
            $subfolderIndexPath = $directory . '/index.html';
            if (Storage::disk('public')->exists($subfolderIndexPath)) {
                return $subfolderIndexPath;
            }
        }

        return null;
    }
}
