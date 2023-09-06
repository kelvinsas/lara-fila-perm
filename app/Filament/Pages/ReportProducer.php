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
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

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
            Action::make('Filtros')
                ->action(function (array $data): void {
                    session()->put('REPORT_PRODUCER_DATA_FILTER', $data);
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
                    return Excel::download(new ExportProductionByProducer($this->getFilterDate()), 'ExportProductionByProducer_'.Carbon::now().'.xlsx');
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
                                'users.name as producer',
                                DB::raw('sum(batches.amount) as amount'),
                                DB::raw('sum(batches.discard) as discard'),
                                DB::raw('sum(batches.defect) as defect'),
                                DB::raw('sum(batches.approved) as approved'),
                                DB::raw('sum(batches.liberted) as liberted'),
                            )
                            ->join('users', 'users.id', 'batches.producer_id')
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
                            ->groupBy('batches.producer_id')
                            ->get();

        return $production;

    }


     protected function getFilterProduct():array {
        $dataSession = session()->get('REPORT_PRODUCER_DATA_FILTER');

            return  $dataSession['product'] ?? array();
    }

    protected function getFilterProducer():array {
        $dataSession = session()->get('REPORT_PRODUCER_DATA_FILTER');

            return  $dataSession['producer'] ?? array();
    }

    protected function getFilterLecturer():array {
        $dataSession = session()->get('REPORT_PRODUCER_DATA_FILTER');

            return  $dataSession['lecturer'] ?? array();
    }

    protected function getFilterDate($format = 'Y-m-d') {
        $dateSession = session()->get('REPORT_PRODUCER_DATA_FILTER');

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
        return auth()->user()->hasPermissionTo('report_producer');
    }

    public function mount(): void
    {
        abort_unless(auth()->user()->hasPermissionTo('report_producer'), 403);
    }

}
