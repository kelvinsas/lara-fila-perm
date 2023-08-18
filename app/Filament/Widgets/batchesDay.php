<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

use App\Models\Batch;

use Carbon\Carbon;

class batchesDay extends BaseWidget
{

    protected static ?int $sort = 2;

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

    protected function getCards(): array
    {

        //Trabalhando com as datas
        $now = Carbon::now();
        $today = $now->format('Y-m-d');
        $startWeek = $now->startOfWeek()->format('Y-m-d');
        $endWeek = $now->endOfWeek()->format('Y-m-d');
        $startMonth = $now->startOfMonth()->format('Y-m-d');
        $endMonth = $now->endOfMonth()->format('Y-m-d');

        // Consultas no banco  
        $monthBatch = $this->filter(Batch::whereBetween('date', array( $startMonth, $endMonth))->get());
        $openBatch =  $monthBatch->where('status', '<', 3);
        $todayBatch = $monthBatch->where('date', $today);
        $weekBatch =  $monthBatch->whereBetween('date', array( $startWeek, $endWeek));

       
        //Exibicao dos cards
        return [
            Card::make('Produção do Dia', $todayBatch->sum('amount'))
                ->description('Itens produzidos no dia.')
                ->descriptionIcon('heroicon-s-trending-up')
                ->chart([17, 2, 10, 3, 15, 4, 17])
                ->color('primary'),
            Card::make('Produção da Semana', $weekBatch->sum('amount'))
                ->description('Itens produzidos na semana.')
                ->descriptionIcon('heroicon-s-trending-up')
                ->chart([17, 2, 10, 3, 15, 4, 17])
                ->color('primary'),
            Card::make('Produção do Mes', $monthBatch->sum('amount'))
                ->description('Itens produzidos no mes.')
                ->descriptionIcon('heroicon-s-trending-up')
                ->chart([17, 2, 10, 3, 15, 4, 17])
                ->color('primary'),
            Card::make('Aprovados no Mes', $monthBatch->sum('approved'))
                ->description('Itens conferidos no mes.')
                ->descriptionIcon('heroicon-s-trending-up')
                ->chart([17, 2, 10, 3, 15, 4, 17])
                ->color('success'),  
            Card::make('Descartados do Mes', $monthBatch->sum('discard'))
                ->description('Itens descartados no mes.')
                ->descriptionIcon('heroicon-s-trending-up')
                ->chart([17, 2, 10, 3, 15, 4, 17])
                ->color('danger'),    
            Card::make('Abertos ou Conferindo', ($openBatch->sum('amount') - $openBatch->sum('approved')))
                ->description('Itens ainda não conferidos no mes.')
                ->descriptionIcon('heroicon-s-trending-up')
                ->chart([17, 2, 10, 3, 15, 4, 17])
                ->color('secondary'),

                
        ];
    }
}
