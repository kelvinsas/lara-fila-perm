<?php

namespace App\Exports;

use App\Models\Batch;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class ExportProductionByProducer implements FromCollection, WithHeadings
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
                                'users.name as producer',
                                DB::raw('sum(batches.amount) as amount'),
                                DB::raw('sum(batches.discard) as discard'),
                                DB::raw('sum(batches.defect) as defect'),
                                DB::raw('sum(batches.approved) as approved'),
                            )
                            ->join('users', 'users.id', 'batches.producer_id')
                            ->whereBetween('batches.date', array( $this->date['date-start'], $this->date['date-end']))
                            ->groupBy('batches.producer_id')
                            ->get();

        return $production;
    }

    public function headings(): array
    {
        return [
            'Produtor',
            'Produzidos',
            'Descartados',
            'Defeitos',
            'Aprovados',
        ];
    }
}
