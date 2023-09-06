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
use App\Models\Product;
use App\Models\User;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;

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
            Action::make('Filtros')
                ->action(function (array $data): void {
                    session()->put('REPORT_PRODUCT_DATA_FILTER', $data);
                })
                ->mountUsing(fn (Forms\ComponentContainer $form) => $form->fill([
                    'date-start' => $this->getFilterDate()['date-start'],
                    'date-end' => $this->getFilterDate()['date-end'],
                    'product' => $this->getFilterProduct(),
                    'producer' => $this->getFilterProducer(),
                    'lecturer' => $this->getFilterlecturer()
                ]))
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
                Forms\Components\Select::make('product')
                    ->label('Produto')
                    ->searchable()
                    ->multiple()
                    ->options(Product::query()->pluck('name', 'id'))
                    ->preload(),              
                Forms\Components\Select::make('producer')
                    ->label('Produtor')
                    ->searchable()
                    ->multiple()
                    ->options(User::query()->pluck('name', 'id'))
                    ->preload(),  
                Forms\Components\Select::make('lecturer')
                    ->label('Conferente')
                    ->multiple()
                    ->searchable()
                    ->options(User::query()->pluck('name', 'id'))
                    ->preload(), 
                ])
                ->icon('heroicon-o-filter'),
            ActionGroup::make([
                Action::make('Excel')->action(function () {
                    return Excel::download(new ExportProductionByProduct(), 'ExportProductionByProducer_'.Carbon::now().'.xlsx');
                })->icon('heroicon-s-download'),
            ])->label('Exportação')           
        ];
    }

    protected function getData() {

        $product = $this->getFilterProduct();
        $producer = $this->getFilterProducer();
        $lecturer = $this->getFilterLecturer();

        $production =   Batch::
                            select(
                                'products.name as product',
                                DB::raw('sum(batches.amount) as amount'),
                                DB::raw('sum(batches.discard) as discard'),
                                DB::raw('sum(batches.defect) as defect'),
                                DB::raw('sum(batches.approved) as approved'),
                            )
                            ->join('products', 'products.id', 'batches.product_id')
                            ->whereBetween('batches.date', $this->getFilterDate())
                            ->when($product, function (Builder $query, array $product) {
                                $query->whereIn('product_id', $product);
                            })
                            ->when($producer, function (Builder $query, array $producer) {
                                $query->whereIn('producer_id', $producer);
                            })
                            ->when($lecturer, function (Builder $query, array $lecturer) {
                                $query->whereIn('lecturer_id', $lecturer);
                            })
                            ->groupBy('batches.product_id')
                            ->get();

        return $production;

    }

    protected function getFilterProduct():array {
        $dataSession = session()->get('REPORT_PRODUCT_DATA_FILTER');

            return  $dataSession['product'] ?? array();
    }

    protected function getFilterProducer():array {
        $dataSession = session()->get('REPORT_PRODUCT_DATA_FILTER');

            return  $dataSession['producer'] ?? array();
    }

    protected function getFilterLecturer():array {
        $dataSession = session()->get('REPORT_PRODUCT_DATA_FILTER');

            return  $dataSession['lecturer'] ?? array();
    }


    protected function getFilterDate($format = 'Y-m-d') {
        $dateSession = session()->get('REPORT_PRODUCT_DATA_FILTER');

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
        return auth()->user()->hasPermissionTo('report_product');
    }

    public function mount(): void
    {
        abort_unless(auth()->user()->hasPermissionTo('report_product'), 403);
    }
}
