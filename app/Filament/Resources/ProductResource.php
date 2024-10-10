<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getPluralModelLabel(): string
    {
        return 'Parts';
    }

    public static function getModelLabel(): string
    {
        return 'Part';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('brand_id')->relationship('brand', 'name')->label('Brand Name')->required(),
                Forms\Components\TextInput::make('model')->label('Model Name')->required(),
                Forms\Components\Repeater::make('part_details')
                                        ->schema([
                                            Forms\Components\TextInput::make('part_type')
                                                            ->label('Parts Name'),
                                            Forms\Components\TextInput::make('stock_quantity')
                                                            ->label('stock Quantity')->numeric(),
                                            Forms\Components\TextInput::make('price')->numeric(),
                                        ])
                ->label(__('Part Details'))
                ->createItemButtonLabel(__('Add Part'))->columns(3)->columnSpan('full')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('brand.name')->label('Brand Name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('model')->sortable()->searchable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
