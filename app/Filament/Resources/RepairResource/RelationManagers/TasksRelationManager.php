<?php

namespace App\Filament\Resources\RepairResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'Task';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('repair_id')->relationship('repair', 'customer_name')->label('Customer Name')->required(),
                Forms\Components\Select::make('user_id')
                                        ->options(function () {
                                            // Query to fetch only users with the role 'Operator'
                                            return \App\Models\User::where('role', 'Operator')
                                                                    ->where('status', 'Active')
                                                                    ->pluck('name', 'id');
                                        })
                                        ->label('Operator')
                                        ->required(),
                Forms\Components\DateTimePicker::make('start_time')->label('Start Time')->native(false)->withoutSeconds()->required(),
                Forms\Components\DateTimePicker::make('end_time')->label('End time')->native(false)->withoutSeconds()->required(),
                Forms\Components\TextInput::make('amount')->label('Amount')->required(),
                Forms\Components\Textarea::make('task_description')->label('Task Description')->required(),
                Forms\Components\Select::make('status')
                        ->options([
                            'Pending' => 'Pending',
                            'In Progress' => 'In Progress',
                            'Completed' => 'Completed',
                ])->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
        ->recordTitleAttribute('Tasks')
        ->columns([
            Tables\Columns\TextColumn::make('user.name')->label('Operator')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('repair.customer_name')->label('Customer Name')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('start_time')
                                        ->label('Start Time')
                                        ->dateTime('d.m.Y H:i')->searchable(),
            Tables\Columns\TextColumn::make('end_time')
                                        ->label('End Time')
                                        ->dateTime('d.m.Y H:i')->searchable(),
            Tables\Columns\TextColumn::make('status')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('amount')->label('Amount')->sortable()->searchable(),
        ])
        ->filters([
            //
        ])
        ->headerActions([
            Tables\Actions\CreateAction::make(),
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
}