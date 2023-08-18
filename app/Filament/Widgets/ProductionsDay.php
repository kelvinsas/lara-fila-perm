<?php

namespace App\Filament\Widgets;

use Filament\Widgets\LineChartWidget;

use App\Models\Batch;

use Carbon\Carbon;

class ProductionsDay extends LineChartWidget
{
    protected static ?string $heading = 'Grafico Produção Diaria';

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    protected static ?string $pollingInterval = '10s';

    public static function canView(): bool
    {
        return auth()->user()->hasPermissionTo('access_panel');
    }


    protected function filter($collection){
        if(auth()->user()->hasRole(['Admin', 'Manager', 'Lecturer'])){
            return $collection;
        }else{
            return $collection->where('producer_id', auth()->user()->id);
        }
    }

    protected function getData(): array
    {

       //Trabalhando com as datas
       $now = Carbon::now();
       $startMonth = $now->startOfMonth()->format('Y-m-d');
       $endMonth = $now->endOfMonth()->format('Y-m-d');

       // Consultas no banco  
       $monthBatch = $this->filter(Batch::whereBetween('date', array( $startMonth, $endMonth))->get());

       $groupDay = $monthBatch->groupBy('date');
       
       $dataLabel = [];
       $dataAmount = [];
       $dataApproved = [];
       $dataDiscard = [];

       foreach($groupDay as $day){
            array_push($dataLabel, Carbon::parse($day[0]->date)->format('d'));
            array_push($dataAmount, $day->sum('amount'));
            array_push($dataApproved, $day->sum('approved'));
            array_push($dataDiscard, $day->sum('discard'));

            
       }

       return [
            'datasets' => [
                [
                    'label' => 'Itens Produzidos',
                    'data' => $dataAmount,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => '#f59e0bb5',
                ],
                [
                    'label' => 'Itens Aprovados',
                    'data' => $dataApproved,
                    'borderColor' => '#16a34a',
                    'backgroundColor' => '#16a34aab',
                ],
                [
                    'label' => 'Itens Descartados',
                    'data' => $dataDiscard,
                    'borderColor' => '#e11d48',
                    'backgroundColor' => '#e11d48bf',
                ]
            ],
            'labels' => $dataLabel,
        ];
    }
}
