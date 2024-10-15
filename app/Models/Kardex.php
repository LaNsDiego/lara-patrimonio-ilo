<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Kardex extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'kardex';

    protected $fillable = [
        'date',
        'product_type_id',
        'quantity',
        'unit_cost',
        'total_cost',
        'establishment_id',
        'supplier_id',
    ];

    public function product_type()
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }

    public function establishment()
    {
        return $this->belongsTo(Establishment::class, 'establishment_id');
    }

    public function detail_kardex()
    {
        return $this->hasMany(DetailKardex::class, 'kardex_id')->orderBy('id', 'DESC');
    }

    //Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'date',
                'product_type_id',
                'quantity',
                'unit_cost',
                'total_cost',
                'establishment_id',
                'supplier_id',
            ])
            ->useLogName('Kardex Log');
    }

}
