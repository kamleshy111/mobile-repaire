<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerHistoryResource\Pages;
use App\Filament\Resources\CustomerHistoryResource\RelationManagers;
use App\Models\CustomerHistory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Filter;
use Carbon\Carbon;

class CustomerHistoryResource extends Resource
{
    protected static ?string $model = CustomerHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Customer History';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                                        ->options(function () {
                                            // Query to fetch only users with the role 'enginer'
                                            return \App\Models\User::where('role', 'enginer')
                                                                    ->where('status', 'active')
                                                                    ->pluck('name', 'id');
                                        })
                                        ->label('Assigned Enginery')
                                        ->required(),
                Forms\Components\Select::make('repair_id')
                                        ->options(function () {
                                            return \App\Models\Repair::pluck('customer_name', 'id');
                                        })
                                        ->label('Customer')
                                        ->required(),
                Forms\Components\TextInput::make('issue')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Enginer Name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('repair.customer_name')->label('Customer Name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('issue')->label('Issue'),
                Tables\Columns\TextColumn::make('created_at')->label('Created At')->dateTime()->sortable(),
            ])
            ->filters([
          
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('start_date'),
                        DatePicker::make('end_date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['start_date'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['end_date'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    })
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
            'index' => Pages\ListCustomerHistories::route('/'),
            'create' => Pages\CreateCustomerHistory::route('/create'),
            'edit' => Pages\EditCustomerHistory::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
       return false;
    }

}
