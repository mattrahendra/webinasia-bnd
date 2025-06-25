<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TemplateProjectResource\Pages;
use App\Models\TemplateProject;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class TemplateProjectResource extends Resource
{
    protected static ?string $model = TemplateProject::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('template_id')
                    ->label('Template')
                    ->relationship('template', 'name')
                    ->required(),
                Forms\Components\FileUpload::make('project_file')
                    ->label('Project File (ZIP)')
                    ->acceptedFileTypes(['application/zip', 'application/x-zip-compressed'])
                    ->maxSize(10240) // 10MB max
                    ->disk('public')
                    ->directory('templates/projects')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('template.name')
                    ->label('Template')
                    ->searchable(),
                Tables\Columns\TextColumn::make('project_file')
                    ->label('Project File')
                    ->formatStateUsing(function ($state) {
                        return $state ? basename($state) : '-';
                    })
                    ->url(function ($state) {
                        return $state ? Storage::url($state) : null;
                    })
                    ->openUrlInNewTab(),
                Tables\Columns\TextColumn::make('preview_url')
                    ->label('Preview')
                    ->formatStateUsing(function ($state, $record) {
                        return $record->preview_url ? 'View Preview' : '-';
                    })
                    ->url(function ($record) {
                        if (!$record->preview_url) {
                            return null;
                        }

                        // Debug: log URL yang akan digunakan
                        Log::info('Preview URL clicked for record ' . $record->id . ': ' . $record->preview_url);

                        // Pastikan URL lengkap dengan domain
                        $url = $record->preview_url;

                        // Jika URL relatif, buat menjadi absolut
                        if (str_starts_with($url, '/')) {
                            $url = url($url);
                        }

                        Log::info('Final preview URL: ' . $url);
                        return $url;
                    })
                    ->openUrlInNewTab(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('template')
                    ->relationship('template', 'name'),
            ])
            ->actions([
                Tables\Actions\Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-o-eye')
                    ->url(function (TemplateProject $record) {
                        if (!$record->preview_url) {
                            return null;
                        }

                        // Buat URL absolut
                        $url = $record->preview_url;
                        if (str_starts_with($url, '/')) {
                            $url = url($url);
                        }

                        return $url;
                    })
                    ->openUrlInNewTab()
                    ->visible(fn (TemplateProject $record) => !empty($record->preview_url)),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTemplateProjects::route('/'),
            'create' => Pages\CreateTemplateProject::route('/create'),
            'edit' => Pages\EditTemplateProject::route('/{record}/edit'),
        ];
    }

    public static function processZipFile(TemplateProject $record): void
    {
        if (!$record->project_file) {
            Log::warning('No project file found for template project ' . $record->id);
            return;
        }

        // Delete old preview directory if exists
        if ($record->preview_path && Storage::disk('public')->exists($record->preview_path)) {
            Storage::disk('public')->deleteDirectory($record->preview_path);
            Log::info('Deleted old preview directory: ' . $record->preview_path);
        }

        // Extract ZIP to templates/projects/extracted/{template_project_id}/
        $extractPath = 'templates/projects/extracted/' . $record->id;
        $zip = new ZipArchive();
        $fullZipPath = Storage::disk('public')->path($record->project_file);

        Log::info('Attempting to open ZIP: ' . $fullZipPath);

        if ($zip->open($fullZipPath) === true) {
            // Buat direktori tujuan jika belum ada
            $fullExtractPath = Storage::disk('public')->path($extractPath);
            if (!is_dir($fullExtractPath)) {
                mkdir($fullExtractPath, 0755, true);
                Log::info('Created extraction directory: ' . $fullExtractPath);
            }

            // Extract semua file
            $extractResult = $zip->extractTo($fullExtractPath);
            $zip->close();

            // Set permission untuk file yang diekstrak
            try {
                $iterator = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($fullExtractPath)
                );

                foreach ($iterator as $file) {
                    if ($file->isFile()) {
                        chmod($file->getPathname(), 0644);
                    } elseif ($file->isDir()) {
                        chmod($file->getPathname(), 0755);
                    }
                }
                Log::info('Set permissions for extracted files');
            } catch (\Exception $e) {
                Log::warning('Failed to set permissions: ' . $e->getMessage());
            }

            if ($extractResult) {
                // Cari file index.html dengan metode yang lebih robust
                $indexPath = self::findIndexHtml($extractPath);

                // Jika tidak ditemukan index.html, coba cari file HTML lainnya
                if (!$indexPath) {
                    $indexPath = self::findAnyHtmlFile($extractPath);
                }

                // Update record dengan preview_path dan preview_url
                $previewUrl = $indexPath ? Storage::url($indexPath) : null;

                $record->update([
                    'preview_path' => $extractPath,
                    'preview_url' => $previewUrl
                ]);

                Log::info('Successfully extracted ZIP for template project ' . $record->id . ' to ' . $extractPath);
                Log::info('Found HTML file at: ' . ($indexPath ?: 'Not found'));
                Log::info('Preview URL set to: ' . ($previewUrl ?: 'Not found'));

                // Debug URL generation
                if ($previewUrl) {
                    Log::info('=== URL DEBUG ===');
                    Log::info('Storage disk path: ' . Storage::disk('public')->path(''));
                    Log::info('Index path relative: ' . $indexPath);
                    Log::info('Generated URL: ' . $previewUrl);
                    Log::info('Full file path: ' . Storage::disk('public')->path($indexPath));
                    Log::info('File exists check: ' . (Storage::disk('public')->exists($indexPath) ? 'YES' : 'NO'));
                }

                // List semua file yang berhasil di-extract
                $allFiles = Storage::disk('public')->allFiles($extractPath);
                Log::info('Extracted ' . count($allFiles) . ' files');

                // Log struktur folder untuk debugging
                self::logDirectoryStructure($extractPath);

            } else {
                Log::error('Failed to extract files from ZIP for template project ' . $record->id);
            }

        } else {
            Log::error('Failed to open ZIP file for template project ' . $record->id . ': ' . $fullZipPath);

            // Cek apakah file ZIP benar-benar ada
            if (!Storage::disk('public')->exists($record->project_file)) {
                Log::error('ZIP file does not exist: ' . $record->project_file);
            } else {
                Log::error('ZIP file exists but cannot be opened. File may be corrupted.');
            }
        }
    }

    /**
     * Cari file index.html dengan prioritas:
     * 1. index.html di root folder extract
     * 2. index.html di subfolder pertama (jika ada folder root di dalam ZIP)
     * 3. index.html di subdirectory manapun
     */
    private static function findIndexHtml(string $extractPath): ?string
    {
        Log::info('Searching for index.html in: ' . $extractPath);

        // 1. Cek di root folder extract
        $rootIndexPath = $extractPath . '/index.html';
        if (Storage::disk('public')->exists($rootIndexPath)) {
            Log::info('Found index.html at root: ' . $rootIndexPath);
            return $rootIndexPath;
        }

        // 2. Dapatkan semua file terlebih dahulu untuk debugging
        $allFiles = Storage::disk('public')->allFiles($extractPath);
        Log::info('All extracted files: ' . implode(', ', $allFiles));

        // 3. Cek di level pertama subdirectories (paling umum untuk ZIP dengan folder root)
        $directories = Storage::disk('public')->directories($extractPath);
        Log::info('Found directories: ' . implode(', ', $directories));

        if (!empty($directories)) {
            // Urutkan direktori untuk konsistensi (ambil yang pertama alphabetically)
            sort($directories);

            foreach ($directories as $dir) {
                $subIndexPath = $dir . '/index.html';
                Log::info('Checking for index.html at: ' . $subIndexPath);

                if (Storage::disk('public')->exists($subIndexPath)) {
                    Log::info('Found index.html in subfolder: ' . $subIndexPath);
                    return $subIndexPath;
                }
            }
        }

        // 4. Cari index.html di semua file secara rekursif
        foreach ($allFiles as $file) {
            if (basename($file) === 'index.html') {
                Log::info('Found index.html recursively: ' . $file);
                return $file;
            }
        }

        Log::warning('No index.html found in extracted files. Available files: ' . implode(', ', array_map('basename', $allFiles)));
        return null;
    }

    /**
     * Cari file HTML apapun jika index.html tidak ditemukan
     */
    private static function findAnyHtmlFile(string $extractPath): ?string
    {
        $allFiles = Storage::disk('public')->allFiles($extractPath);
        Log::info('Searching for any HTML file. Total files: ' . count($allFiles));

        // Prioritaskan file HTML di level teratas dulu
        $htmlFiles = [];
        foreach ($allFiles as $file) {
            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if ($extension === 'html' || $extension === 'htm') {
                $htmlFiles[] = $file;
                Log::info('Found HTML file: ' . $file);
            }
        }

        if (!empty($htmlFiles)) {
            // Urutkan berdasarkan depth (yang paling dangkal dulu)
            usort($htmlFiles, function($a, $b) {
                return substr_count($a, '/') - substr_count($b, '/');
            });

            $selectedFile = $htmlFiles[0];
            Log::info('Selected HTML file: ' . $selectedFile);
            return $selectedFile;
        }

        Log::warning('No HTML files found in extracted files');
        return null;
    }

    /**
     * Log struktur direktori untuk debugging
     */
    private static function logDirectoryStructure(string $extractPath): void
    {
        try {
            $allFiles = Storage::disk('public')->allFiles($extractPath);
            $allDirs = Storage::disk('public')->allDirectories($extractPath);

            Log::info('Directory structure for ' . $extractPath . ':');
            Log::info('Directories: ' . implode(', ', $allDirs));
            Log::info('Files: ' . implode(', ', array_map('basename', $allFiles)));

            // Log first 10 files with full path
            $firstTenFiles = array_slice($allFiles, 0, 10);
            foreach ($firstTenFiles as $file) {
                Log::info('File path: ' . $file);
            }

        } catch (\Exception $e) {
            Log::error('Error logging directory structure: ' . $e->getMessage());
        }
    }
}
