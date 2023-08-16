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
        $todayBatch = Batch::whereDate('date', $today)->get();
        $weekBatch = Batch::whereBetWeen('date', array( $startWeek, $endWeek))->get();        
        $monthBatch = Batch::whereBetween('date', array( $startMonth, $endMonth))->get();
        $openBatch = Batch::where('status', '<', 3)->get();


       


        //Exibicao dos cards
        return [
            Card::make('Produção do Dia', $todayBatch->sum('approved'))
                ->description('Itens produzidos e conferidos no dia.')
                ->descriptionIcon('heroicon-s-trending-up'),
            Card::make('Produção da Semana', $weekBatch->sum('approved'))
                ->description('Itens produzidos e conferidos na semana.')
                ->descriptionIcon('heroicon-s-trending-up'),
            Card::make('Produção do Mes', $monthBatch->sum('approved'))
                ->description('Itens produzidos e conferidos no mes.')
                ->descriptionIcon('heroicon-s-trending-up'),
            Card::make('Descartados do Dia', $todayBatch->sum('discard'))
                ->description('Itens produzidos e conferidos no dia.')
                ->descriptionIcon('heroicon-s-trending-up'),  
            Card::make('Descartados do Mes', $monthBatch->sum('discard'))
                ->description('Itens produzidos e conferidos no mes.')
                ->descriptionIcon('heroicon-s-trending-up')
                ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('danger'),    
            Card::make('Abertos ou Conferindo', $openBatch->sum('amount'))
                ->description('Itens produzidos e conferidos no mes.')
                ->descriptionIcon('heroicon-s-trending-up'),    

                
        ];
    }
}
