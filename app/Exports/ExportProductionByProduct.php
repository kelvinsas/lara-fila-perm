<?php

namespace App\Exports;

use App\Models\Batch;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class ExportProductionByProduct implements FromCollection, WithHeadings
{

    protected $date;

    public function __construct(array $date)
    {
        $this->date = $date;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
      
        $production =   Batch::select(
                                'products.name as product',
                                DB::raw('sum(batches.amount) as amount'),
                                DB::raw('sum(batches.discard) as discard'),
                                DB::raw('sum(batches.defect) as defect'),
                                DB::raw('sum(batches.approved) as approved'),
                            )
                            ->join('products', 'products.id', 'batches.product_id')
                            ->whereBetween('batches.date', array( $this->date['date-start'], $this->date['date-end']))
                            ->groupBy('batches.product_id')
                            ->get();
        return $production;    

    }

    public function headings(): array
    {
        return [
            'Produto',
            'Produzidos',
            'Descartados',
            'Defeitos',
            'Aprovados',
        ];
    }
}
