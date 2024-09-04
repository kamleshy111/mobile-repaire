<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RepairResource\Pages;
use App\Filament\Resources\RepairResource\RelationManagers;
use App\Models\Repair;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RepairResource extends Resource
{
    protected static ?string $model = Repair::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('customer_name')->label('Customer Name')->required(),
                Forms\Components\TextInput::make('customer_contact')->label('Customer Contact')->numeric()->required(),
                Forms\Components\TextInput::make('device_brand')->label('Device Brand')->required(),
                Forms\Components\TextInput::make('device_model')->label('Device Model')->required(),
                Forms\Components\TextInput::make('estimated_cost')->label('Estimated Cost')->numeric()->required(),
                Forms\Components\TextInput::make('final_cost')->label('Final Cost')->numeric()->required(),
                Forms\Components\TextInput::make('issue')->label('issue')->required(),
                Forms\Components\Select::make('status')
                        ->options([
                            'Pending' => 'Pending',
                            'In Progress' => 'In Progress',
                            'Completed' => 'Completed',
                            'Cancelled' => 'Cancelled',
                ])->required(),
                Forms\Components\Textarea::make('issue_description')->label('Issue Description')->columnSpan('full')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer_name')->label('Customer Name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('device_brand')->label('Device Brand')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('device_model')->label('Device Model')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('issue')->label('Issue')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('issue_description')->label('Issue Description')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('status')->sortable()->searchable(),
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
            RelationManagers\TasksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRepairs::route('/'),
            'create' => Pages\CreateRepair::route('/create'),
            'edit' => Pages\EditRepair::route('/{record}/edit'),
        ];
    }
}
