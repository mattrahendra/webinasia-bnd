<?php

namespace App\Filament\Resources\TemplateProjectResource\Pages;

use App\Filament\Resources\TemplateProjectResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTemplateProjects extends ListRecords
{
    protected static string $resource = TemplateProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
