<?php

namespace App\Filament\Pages;

use App\Models\Batch;
use Filament\Pages\Page;
use Illuminate\Contracts\View\View;
use Filament\Pages\Actions\Action;
 
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Reports extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.reports';

    protected static ?string $title = 'Relatório Diário';
 
    protected static ?string $navigationLabel = 'Relatório Diário';
 
    protected static ?string $slug = 'reports-day';

    protected static ?string $model = Batch::class;

    protected static ?string $navigationGroup = 'Relatórios';


    protected function getActions(): array
    {
        return [
            Action::make('settings')->icon('heroicon-s-cog')
                ->action(fn () => var_dump($this))
                ->requiresConfirmation(),
        ];
    }

    protected function getProductionDayByProduct() {

        $today = Carbon::now()->format('Y-m-d');

        $production =   Batch::
                            select(
                                'products.name as product',
                                DB::raw('sum(batches.amount) as amount'),
                                DB::raw('sum(batches.discard) as discard'),
                                DB::raw('sum(batches.defect) as defect'),
                                DB::raw('sum(batches.approved) as approved'),
                            )
                            ->join('products', 'products.id', 'batches.product_id')
                            //->whereDate('batches.date', $today)
                            ->groupBy('batches.product_id')
                            ->get();

        return $production;

    }

    protected function getProductionDayByProducer() {

        $today = Carbon::now()->format('Y-m-d');

        $production =   Batch::
                            select(
                                'users.name as producer',
                                DB::raw('sum(batches.amount) as amount'),
                                DB::raw('sum(batches.discard) as discard'),
                                DB::raw('sum(batches.defect) as defect'),
                                DB::raw('sum(batches.approved) as approved'),
                            )
                            ->join('users', 'users.id', 'batches.producer_id')
                            //->whereDate('batches.date', $today)
                            ->groupBy('batches.producer_id')
                            ->get();

        return $production;

    }

    protected function getProductionDayByProducerAndProduct() {

        $today = Carbon::now()->format('Y-m-d');

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
                            //->whereDate('batches.date', $today)
                            ->groupBy('batches.producer_id', 'batches.product_id')
                            ->orderBy('batches.producer_id', 'asc')
                            ->get();

        return $production;

    }

    
}
