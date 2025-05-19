<?php

namespace App\Filament\Tenant\Resources;

use App\Filament\Tenant\Resources\DocumentResource\Pages;
use App\Models\Document;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\FileUpload::make('path')
                    ->label('Document')
                    ->disk('tenant_documents')
                    ->required()
                    ->directory(fn() => tenant('id') . '/documents')
                    ->preserveFilenames()
                    ->reactive()
                    ->acceptedFileTypes([
                        'application/pdf',
                        'text/plain',
                        'image/*',
                    ])
                    ->maxSize(10240) // 10MB
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if ($state) {
                            if ($state) {
                                $set('mime_type', $state->getClientMimeType());
                                $set('size', $state->getSize());
                            }
                        }
                    }),
                Forms\Components\Hidden::make('mime_type')
                    ->required()
                    ->default('unknown'),

                Forms\Components\Hidden::make('size')
                    ->required()
                    ->default(0),

                Forms\Components\KeyValue::make('metadata')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\ImageColumn::make('preview')
                    ->getStateUsing(
                        fn($record) =>
                        str_starts_with($record->mime_type, 'image/')
                        ? $record->preview_url
                        : null
                    )
                    ->size(100),
                Tables\Columns\TextColumn::make('mime_type')
                    ->label('Type')
                    ->sortable(),

                Tables\Columns\TextColumn::make('size')
                    ->label('Size')
                    ->formatStateUsing(fn(string $state): string => format_bytes($state))
                    ->sortable(),


                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('preview')
                    ->icon('heroicon-o-eye')
                    ->modalHeading(fn($record) => $record->name)
                    ->modalContent(view('filament.documents.preview')),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn(Document $record) => asset("storage/" . $record->path))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'view' => Pages\ViewDocument::route('/{record}'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }
}
