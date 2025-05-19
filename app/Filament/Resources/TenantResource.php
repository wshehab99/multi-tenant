<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TenantResource\Pages;
use App\Filament\Resources\TenantResource\RelationManagers;
use App\Models\Tenant;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Filament\Forms\Components\Fieldset;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    Step::make('Tenant Information')
                        ->schema([
                            Section::make()
                                ->schema([
                                    TextInput::make('name')
                                        ->required()
                                        ->maxLength(255)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(function ($set, $state) {
                                            $set('domain', Str::slug($state) . '.test');
                                        }),

                                    TextInput::make('domain')
                                        ->required()
                                        ->unique(table: 'domains', column: 'domain')
                                        ->prefix('https://')
                                        ->suffix('.test')
                                        ->maxLength(255),
                                ])->columns(2),
                        ]),

                    Step::make('Admin User')
                        ->schema([
                            Fieldset::make('Administrator Account')
                                ->schema([
                                    TextInput::make('admin_name')
                                        ->required()
                                        ->maxLength(255),

                                    TextInput::make('admin_email')
                                        ->email()
                                        ->required()
                                        ->unique(table: 'users', column: 'email'),

                                    TextInput::make('admin_password')
                                        ->password()
                                        ->required()
                                        ->confirmed()
                                        ->rules(['min:8']),

                                    TextInput::make('admin_password_confirmation')
                                        ->password()
                                        ->required(),
                                ]),
                        ]),
                ])
                    ->skippable(),
            ])
            ->statePath('data');

    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Tenant ID')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Tenant Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('domains.domain')
                    ->label('Domain'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
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
            'index' => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'edit' => Pages\EditTenant::route('/{record}/edit'),
        ];
    }
}
