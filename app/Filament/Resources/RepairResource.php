<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RepairResource\Pages;
use App\Filament\Resources\RepairResource\RelationManagers;
use App\Models\Repair;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Enums\FiltersLayout;
use Carbon\Carbon;
use App\Models\User;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Section;



class RepairResource extends Resource
{
    protected static ?string $model = Repair::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('customer_id')
                ->label('Customer ID')
                ->readOnly()
                ->default(fn() => 'B-' . time() . rand(10, 99)),
                Section::make('Customer Information')
                    ->schema([
                        Forms\Components\Grid::make()->columns(2)->schema([
                        Forms\Components\TextInput::make('customer_name')->label('Customer Name')->required(),
                        Forms\Components\TextInput::make('customer_contact')->label('Customer Contact')->numeric()->required(),
                    ]),
                ]),

                Section::make('Device Information')
                    ->schema([
                        Forms\Components\Grid::make()->columns(2)->schema([
                            Forms\Components\Select::make('brand_id')->relationship('brand', 'name')->label('Device Brand')->required(),
                            Forms\Components\TextInput::make('device_model')->label('Device Model')->required(),
                            Forms\Components\TextInput::make('patern_lock')->label('Patern Lock')->numeric(),
                        ]),
                ]),

                Section::make('Financial Information')
                ->schema([
                    Forms\Components\Grid::make()->columns(2)->schema([
                        Forms\Components\TextInput::make('estimated_cost')->label('Estimated Cost')->numeric()->required(),
                        Forms\Components\TextInput::make('received_amount')->label('Received Amount')->numeric(),
                        Forms\Components\DateTimePicker::make('date_time')->label('Start Time')->native(false)->withoutSeconds()->required(),
                        Forms\Components\DateTimePicker::make('deliver_date')->label('Deliver Date')->native(false)->withoutSeconds()->required(),
                    ]),
                ]),
                Section::make('Engineers Assigned')
                ->schema([
                    Forms\Components\Grid::make()->columns(2)->schema([
                        Forms\Components\Select::make('user_id')
                            ->options(function () {
                                // Query to fetch only users with the role 'Engineer'
                                return User::where('role', 'enginer') // Corrected spelling from 'enginer' to 'engineer'
                                    ->where('status', 'active')
                                    ->pluck('name', 'id');
                            })
                            ->label('Engineer Name')
                            ->required(),
                        Forms\Components\Select::make('status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'in_progress' => 'In Progress',
                                        'Completed' => 'Completed',
                                        'Cancelled' => 'Cancelled',
                            ])->required(),
                        Forms\Components\TextInput::make('issue')->label('issue')->required(),
                        Forms\Components\Textarea::make('issue_description')->label('Issue Description')->required(),
                    ]),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('customer_id')->label('Customer ID')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('customer_name')->label('Customer Name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('user.name')->label('Enginery Name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('brand.name')->label('Device Brand')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('device_model')->label('Device Model')->sortable()->searchable(),
                // Tables\Columns\TextColumn::make('issue')->label('Issue')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('date_time')->label('Start Time')->dateTime('d.m.Y H:i')->searchable(),
                Tables\Columns\TextColumn::make('deliver_date')->label('Deliver Time')->dateTime('d.m.Y H:i')->searchable(),
                Tables\Columns\TextColumn::make('status')->sortable()->searchable(),
            ])

            ->filters([
                SelectFilter::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'in_progress' => 'In Progress',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ]),

                SelectFilter::make('Operator')
                            ->label('Filter by Enginer')
                            ->options(function () {
                                return User::where('role', 'enginer')->pluck('name', 'id')->toArray();
                            })
                            ->query(function (Builder $query, array $data) {
                                if(!empty($data['value'])){
                                    return $query->whereHas('user', function ($query) use ($data) {
                                        $query->where('id', $data['value']);
                                    });
                                }
                                return $query;
                            })->searchable()->indicator('Enginer'),
                        
                Filter::make('date_range')
                            ->form([
                                DatePicker::make('start_date')
                                    ->label('Start Date')
                                    ->required(),
                                DatePicker::make('end_date')
                                    ->label('End Date')
                                    ->required(),
                            ])
                            ->query(function (Builder $query, array $data) {
                                if (!empty($data['start_date']) && !empty($data['end_date'])) {
                                    return $query->whereBetween('created_at', [
                                        $data['start_date'],
                                        $data['end_date'],
                                    ]);
                                }
                                return $query;
                            })
                            ->indicateUsing(function (array $data) {
                                if(!empty($data['start_date']) && !empty($data['end_date'])){
                                  return  'Date Range: ' . Carbon::parse($data['start_date'])->format('d M Y') . ' to ' . Carbon::parse($data['end_date'])->format('d M Y');
                                }
                                return [

                                ];
                            })->columnSpan(2)->columns(2),

                        ], layout: FiltersLayout::AboveContent)->filtersFormColumns(4)
            ->actions([
                ActionGroup::make([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
                ]),
                Tables\Actions\Action::make('download_pdf')
                                        ->icon('heroicon-o-document') // Example icon
                                        ->action(function ($record) {
                                            $repairs = collect([$record]);
                                            $pdf = Pdf::loadView('pdf.repairs', ['repairs' => $repairs]);
                                            return Response::streamDownload(fn () => print($pdf->output()), 'repair-' . $record->id . '.pdf');
                                        }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('download_pdf')
                    ->label('Download PDF')
                    ->action(function ($records) {
                        // Convert the collection to an array of IDs
                        $recordIds = $records->pluck('id')->toArray();
                        
                        // Fetch the repairs using the array of IDs
                        $repairs = Repair::whereIn('id', $recordIds)->get();
                        
                        // Generate the PDF with the repairs data
                        $pdf = Pdf::loadView('pdf.repairs', ['repairs' => $repairs]);
                
                        // Stream the PDF download
                        return Response::streamDownload(fn () => print($pdf->output()), 'repairs.pdf');
                    }),
                
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\RepairHistoryRelationManager::class, 
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
