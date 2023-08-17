<?php

namespace App\Filament\Pages;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\ActionGroup;
use Maatwebsite\Excel\Facades\Excel; 
use Filament\Pages\Page;
use Carbon\Carbon;

use App\Exports\ExportProductionByProduct;
use App\Models\Batch;
use Filament\Forms;

class ReportProduct extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-color-swatch';

    protected static string $view = 'filament.pages.report-product';

    protected static ?string $title = 'Relatório de Produtos';
 
    protected static ?string $navigationLabel = 'Relatório de Produtos';
 
    protected static ?string $slug = 'reports-products';

    protected static ?string $model = Batch::class;

    protected static ?string $navigationGroup = 'Relatórios';

    protected function getActions(): array
    {
        return [
            Action::make('Periodo')
                ->action(function (array $data): void {
                    session()->put('REPORT_PRODUCT_DATE_FILTER', $data);
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

                    return Excel::download(new ExportProductionByProduct($date), 'ExportProductionByProducer_'.Carbon::now().'.xlsx');
                })->icon('heroicon-s-download'),
            ])->label('Exportação')           
        ];
    }

    protected function getData() {

        $filter_date =  session()->get('REPORT_PRODUCT_DATE_FILTER') ?? 
                        array('date-start' => Carbon::now()->format('Y-m-d'), 'date-end' => Carbon::now()->format('Y-m-d'));

        $production =   Batch::
                            select(
                                'products.name as product',
                                DB::raw('sum(batches.amount) as amount'),
                                DB::raw('sum(batches.discard) as discard'),
                                DB::raw('sum(batches.defect) as defect'),
                                DB::raw('sum(batches.approved) as approved'),
                            )
                            ->join('products', 'products.id', 'batches.product_id')
                            ->whereBetween('batches.date', array( $filter_date['date-start'], $filter_date['date-end']))
                            ->groupBy('batches.product_id')
                            ->get();

        return $production;

    }
}