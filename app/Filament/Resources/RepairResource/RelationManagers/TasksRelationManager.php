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
use App\Models\Product;

class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'Task';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('repair_id')
                                        ->default(fn (RelationManager $livewire) => $livewire->ownerRecord->id),
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
                Forms\Components\Select::make('product_id')
                                            ->relationship('product', 'part_type') // Assuming the 'name' field is used to display products
                                            ->label('Select Item'),
                Forms\Components\TextInput::make('quantity')->numeric()->required(),
                Forms\Components\Select::make('status')
                        ->options([
                            'Pending' => 'Pending',
                            'In Progress' => 'In Progress',
                            'Completed' => 'Completed',
                ])->required(),
                Forms\Components\Textarea::make('task_description')->label('Issue Description')->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
        ->recordTitleAttribute('Tasks')
        ->columns([
            Tables\Columns\TextColumn::make('user.name')->label('Operator')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('repair.customer_name')->label('Customer Name')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('product.name')->label('Product name')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('product.model')->label('Model')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('product.part_type')->label('Part name')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('quantity')->searchable(),
            Tables\Columns\TextColumn::make('repair.issue')->label('Issue')->sortable()->searchable(),

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