<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TemplateProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'template_id' => $this->template_id,
            'project_file' => $this->project_file,
            'preview_path' => $this->preview_path,
            'preview_url' => $this->preview_url,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'template' => new TemplateResource($this->whenLoaded('template')),
        ];
    }
}
