<?php

namespace App\Filament\Resources\TemplateProjectResource\Pages;

use App\Filament\Resources\TemplateProjectResource;
use App\Models\TemplateProject;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreateTemplateProject extends CreateRecord
{
    protected static string $resource = TemplateProjectResource::class;

    protected function afterCreate(): void
    {
        // Process ZIP file after record is created
        TemplateProjectResource::processZipFile($this->record);
    }
}
