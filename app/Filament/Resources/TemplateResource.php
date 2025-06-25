<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TemplateResource\Pages;
use App\Models\Template;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class TemplateResource extends Resource
{
    protected static ?string $model = Template::class;

    protected static ?string $navigationIcon = 'heroicon-o-document';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Template')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('description')
                            ->maxLength(500)
                            ->rows(3),

                    ])
                    ->columns(1),

                Section::make('Kategori Template')
                    ->schema([
                        Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name') // asumsi kolom nama di tabel categories adalah 'name'
                            ->required(),
                    ])
                    ->columns(1),

                Section::make('Harga Template')
                    ->schema([
                        TextInput::make('price')
                            ->label('Harga')
                            ->numeric()
                            ->prefix('Rp')
                            ->required(),
                    ])
                    ->columns(1),

                Section::make('Gambar Template')
                    ->schema([
                        Repeater::make('images') // Harus sesuai dengan relasi di model
                            ->relationship('images') // Penting! Agar otomatis set template_id
                            ->schema([
                                FileUpload::make('image_path')
                                    ->label('Upload Gambar')
                                    ->image()
                                    ->required()
                                    ->directory('template-images') // Folder penyimpanan di storage/app/public/
                                    ->visibility('public')
                                    ->imagePreviewHeight('150'),
                            ])
                            ->columns(1)
                            ->label('Daftar Gambar')
                            ->collapsible(),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('main_image')
                    ->label('Main Image')
                    ->disk('public')
                    ->getStateUsing(function ($record) {
                        return $record->main_image;
                    }),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('idr')
                    ->sortable(),
                Tables\Columns\TextColumn::make('images_count')
                    ->label('Images')
                    ->counts('images')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
            ])
            ->actions([
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
            'index' => Pages\ListTemplates::route('/'),
            'create' => Pages\CreateTemplate::route('/create'),
            'edit' => Pages\EditTemplate::route('/{record}/edit'),
        ];
    }
}
