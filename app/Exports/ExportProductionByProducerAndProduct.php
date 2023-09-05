<?php

namespace App\Exports;

use App\Models\Batch;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class ExportProductionByProducerAndProduct implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        $dataSession = session()->get('REPORT_PRODUCT_AND_PRODUCER_DATA_FILTER');

        $date = $this->getFilterDate($dataSession);      

        $production =   Batch::select(
                                'users.name as producer',
                                'products.name as product',
                                DB::raw('sum(batches.amount) as amount'),
                                DB::raw('sum(batches.discard) as discard'),
                                DB::raw('sum(batches.defect) as defect'),
                                DB::raw('sum(batches.approved) as approved'),
                            )
                            ->join('users', 'users.id', 'batches.producer_id')
                            ->join('products', 'products.id', 'batches.product_id')
                            ->whereBetween('batches.date', array( $date['date-start'], $date['date-end']))
                            ->when( $dataSession['product'] ?? array(), function (Builder $query, array $product) {
                                $query->whereIn('product_id', $product);
                            })
                            ->when($dataSession['producer'] ?? array(), function (Builder $query, array $producer) {
                                $query->whereIn('producer_id', $producer);
                            })
                            ->when($dataSession['lecturer'] ?? array(), function (Builder $query, array $lecturer) {
                                $query->whereIn('lecturer_id', $lecturer);
                            })
                            ->groupBy('batches.producer_id', 'batches.product_id')
                            ->orderBy('batches.producer_id', 'asc')
                            ->get();

        return $production;
    }


    public function headings(): array
    {
        return [
            'Produto',
            'Produtor',
            'Produzidos',
            'Descartados',
            'Defeitos',
            'Aprovados',
        ];
    }



    protected function getFilterDate($dateSession, $format = 'Y-m-d') {

        if ($format !== 'Y-m-d' && $dateSession){
            return  array(
                        'date-start' => Carbon::parse($dateSession['date-start'])->format($format), 
                        'date-end' => Carbon::parse($dateSession['date-end'])->format($format)
                    );
        }

        return $dateSession ?? array('date-start' => Carbon::now()->format($format), 'date-end' => Carbon::now()->format($format));
    }
}
