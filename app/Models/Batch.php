<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'amount',
        'date',
        'discard',
        'approved',
        'defect',
        'status',
        'product_id',
        'producer_id', 
        'lecturer_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        //
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        //
    ];

    public function Producers()
    {
        return $this->belongsTo('App\Models\User',  'producer_id', 'id');
    }

    public function Products()
    {
        return $this->belongsTo('App\Models\Product',  'product_id', 'id');
    }

    public function Lecturers()
    {
        return $this->belongsTo('App\Models\User',  'lecturer_id', 'id');
    }

    public function getProductionDay(){
        
    }




    
}
