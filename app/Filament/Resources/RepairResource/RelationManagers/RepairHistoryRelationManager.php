<?php

namespace App\Filament\Resources\RepairResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RepairHistoryRelationManager extends RelationManager
{
    protected static string $relationship = 'customerHistory';

    protected function getTableHeading(): string
{
    return 'Repair History';
}
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('repair_id')
                    ->relationship('repair', 'customer_name')
                    ->required(),
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\TextInput::make('issue')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Engineer Name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.contact')->label('Engineer Contact')
                    ->sortable(),
                Tables\Columns\TextColumn::make('issue')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label('Created AT')
                    ->sortable(),
            ])->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected function canCreate(): bool
    {
        return false;
    }

}
