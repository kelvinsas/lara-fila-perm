<?php

namespace App\Filament\Pages;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

use Filament\Pages\Actions\Action;
use Filament\Pages\Actions\ActionGroup;
use Maatwebsite\Excel\Facades\Excel; 
use Filament\Pages\Page;
use Filament\Forms;
use Carbon\Carbon;


use App\Exports\ExportProductionByProducerAndProduct;
use App\Models\Batch;

class ReportProducerAndProduct extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-report';

    protected static string $view = 'filament.pages.report-producer-and-product';

    protected static ?string $title = 'Relatório de Produtores e Produtos';
 
    protected static ?string $navigationLabel = 'Relatório de Produtores e Produtos';
 
    protected static ?string $slug = 'reports-producers-products';

    protected static ?string $model = Batch::class;

    protected static ?string $navigationGroup = 'Relatórios';

    protected function getActions(): array
    {
        return [
            Action::make('Periodo')
                ->action(function (array $data): void {
                    session()->put('REPORT_PRODUCT_AND_PRODUCER_DATE_FILTER', $data);
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
                    return Excel::download(new ExportProductionByProducerAndProduct($this->getDateNow()), 'ExportProductionByProducerAndProduct_'.Carbon::now().'.xlsx');
                })->icon('heroicon-s-download'),
            ])->label('Exportação')           
        ];
    }

    protected function getData() {

        $production =   Batch::
                            select(
                                'users.name as producer',
                                'products.name as product',
                                DB::raw('sum(batches.amount) as amount'),
                                DB::raw('sum(batches.discard) as discard'),
                                DB::raw('sum(batches.defect) as defect'),
                                DB::raw('sum(batches.approved) as approved'),
                            )
                            ->join('users', 'users.id', 'batches.producer_id')
                            ->join('products', 'products.id', 'batches.product_id')
                            ->whereBetween('batches.date', $this->getDateNow())
                            ->groupBy('batches.producer_id', 'batches.product_id')
                            ->orderBy('batches.producer_id', 'asc')
                            ->get();

        return $production;

    }

    protected function getDateNow($format = 'Y-m-d') {
        $dateSession = session()->get('REPORT_PRODUCT_AND_PRODUCER_DATE_FILTER');

        if ($format !== 'Y-m-d' && $dateSession){
            return  array(
                        'date-start' => Carbon::parse($dateSession['date-start'])->format($format), 
                        'date-end' => Carbon::parse($dateSession['date-end'])->format($format)
                    );
        }

        return $dateSession ?? array('date-start' => Carbon::now()->format($format), 'date-end' => Carbon::now()->format($format));
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasPermissionTo('report_producer_and_product');
    }

    public function mount(): void
    {
        abort_unless(auth()->user()->hasPermissionTo('report_producer_and_product'), 403);
    }
}
