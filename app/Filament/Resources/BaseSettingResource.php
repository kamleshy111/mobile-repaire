<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BaseSettingResource\Pages;
use App\Filament\Resources\BaseSettingResource\RelationManagers;
use App\Models\BaseSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BaseSettingResource extends Resource
{
    protected static ?string $model = BaseSetting::class;

    protected static ?string $navigationIcon = 'heroicon-s-cog';

    protected static ?string $navigationGroup = 'General Web Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('favicon_icon')
                                ->label('Favicon Icon')
                                ->image()
                                ->directory('uploads/favicon_icon')
                                ->visibility('public'),
                Forms\Components\FileUpload::make('logo')
                                ->image()
                                ->directory('uploads/logo')
                                ->visibility('public'),
                Forms\Components\TextInput::make('title')->required(),
                            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('favicon_icon')->label(('Favicon Icon')),
                Tables\Columns\ImageColumn::make('logo'),
                Tables\Columns\TextColumn::make('title')->sortable()->searchable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListBaseSettings::route('/'),
            'create' => Pages\CreateBaseSetting::route('/create'),
            'edit' => Pages\EditBaseSetting::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return !BaseSetting::exists();
    }
}
