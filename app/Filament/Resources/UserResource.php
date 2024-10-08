<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Hidden;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function getPluralModelLabel(): string
    {
        return 'Engineries';
    }

    public static function getModelLabel(): string
    {
        return 'Enginer';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('engineer_id')
                ->label('Engineer ID')
                ->readOnly() // Use read-only instead of disabled
                ->default(fn() => 'B-' . time() . rand(10, 99)),
                TextInput::make('name')->required()->maxLength(255),
                TextInput::make('contact')->numeric()->required(),
                TextInput::make('email')
                            ->required()
                            ->email()
                            ->rules([
                                'unique:users,email,' . ($form->model?->id ?? 'NULL') . ',id', // This ensures the email is only unique for new users
                            ]),
                TextInput::make('password')
                             ->password()
                             ->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null) // Hash the password if provided
                             ->required(fn($livewire) => $livewire instanceof CreateRecord) // Required only during creation
                             ->nullable()
                             ->dehydrated(fn($state) => filled($state)),
                Hidden::make('role')->default('enginer'),
                Select::make('status')
                        ->options([
                            'active' => 'Active',
                            'inactive' => 'Inactive',
                ])->default('active')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('engineer_id')->label('Engineer ID')->sortable()->searchable(),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('contact')->sortable()->searchable(),
                TextColumn::make('status')->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            // ->defaultSort('name');
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
