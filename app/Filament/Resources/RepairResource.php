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
use Carbon\Carbon;
use App\Models\User;


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
                Tables\Columns\TextColumn::make('status')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')->sortable()->searchable(),

            ])

            ->filters([
                SelectFilter::make('status')
                            ->options([
                                'Pending' => 'Pending',
                                'In Progress' => 'In Progress',
                                'Completed' => 'Completed',
                                'Cancelled' => 'Cancelled',
                            ]),

                SelectFilter::make('Operator')
                            ->label('Filter by Operator')
                            ->options(function () {
                                return User::where('role', 'Operator')->pluck('name', 'id')->toArray();
                            })
                            ->query(function (Builder $query, array $data) {
                                if(!empty($data['value'])){
                                    return $query->whereHas('task', function ($query) use ($data) {
                                        $query->where('user_id', $data['value']);
                                    });
                                }
                                return $query;
                            }),
                        
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
                            }),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
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
