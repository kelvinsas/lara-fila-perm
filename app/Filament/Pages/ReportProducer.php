<?php

namespace App\Filament\Pages;

use Illuminate\Support\Facades\DB;
use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\ActionGroup;
use Filament\Forms;
use Filament\Pages\Page;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel; 

use App\Exports\ExportProductionByProducer;
use App\Models\Batch;


class ReportProducer extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static string $view = 'filament.pages.report-producer';

    protected static ?string $title = 'Relatório de Produtores';
 
    protected static ?string $navigationLabel = 'Relatório de Produtores';
 
    protected static ?string $slug = 'reports-producers';

    protected static ?string $model = Batch::class;

    protected static ?string $navigationGroup = 'Relatórios';

    protected function getActions(): array
    {
        return [
            Action::make('Periodo')
                ->action(function (array $data): void {
                    session()->put('REPORT_PRODUCER_DATE_FILTER', $data);
                })
                ->form([
                Forms\Components\DateTimePicker::make('date-start')
                    ->format('Y-m-d')
                    ->displayFormat('d/m/Y')
                    ->label('Data Inicial')
                    ->required(),
                Forms\Components\DateTimePicker::make('date-end')
                    ->format('Y-m-d')
                    ->displayFormat('d/m/Y')
                    ->label('Data Final')
                    ->required(),
                ])
                ->icon('heroicon-o-calendar'),
            ActionGroup::make([
                Action::make('Excel')->action(function () {
                    $date = session()->get('REPORT_PRODUCT_AND_PRODUCER_DATE_FILTER') ?? 
                    array('date-start' => Carbon::now()->format('Y-m-d'), 'date-end' => Carbon::now()->format('Y-m-d'));

                    return Excel::download(new ExportProductionByProducer($date), 'ExportProductionByProducer_'.Carbon::now().'.xlsx');
                })->icon('heroicon-s-download'),
            ])->label('Exportação')           
        ];
    }

    protected function getData() {

        $filter_date =  session()->get('REPORT_PRODUCER_DATE_FILTER') ?? 
                        array('date-start' => Carbon::now()->format('Y-m-d'), 'date-end' => Carbon::now()->format('Y-m-d'));

        $production =   Batch::
                            select(
                                'users.name as producer',
                                DB::raw('sum(batches.amount) as amount'),
                                DB::raw('sum(batches.discard) as discard'),
                                DB::raw('sum(batches.defect) as defect'),
                                DB::raw('sum(batches.approved) as approved'),
                            )
                            ->join('users', 'users.id', 'batches.producer_id')
                            ->whereBetween('batches.date', array( $filter_date['date-start'], $filter_date['date-end']))
                            ->groupBy('batches.producer_id')
                            ->get();

        return $production;

    }
}
