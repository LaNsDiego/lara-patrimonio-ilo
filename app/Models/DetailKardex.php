<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class DetailKardex extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;


    protected $table = 'detail_kardex';

    protected $fillable = [
        'kardex_id',
        'product_id',
        'responsible_employee_id',
        'establishment_id',
        'movement_type_id',
        'papeleta_id',
        'quantity',
        'unit_price',
        'total_price',
        'comment'
    ];

    public function kardex()
    {
        return $this->belongsTo(Kardex::class, 'kardex_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

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
        return $this->hasMany(DetailKardex::class, 'kardex_id');
    }

    public function movement_type()
    {
        return $this->belongsTo(MovementType::class, 'movement_type_id');
    }

         //Activity Log
         public function getActivitylogOptions(): LogOptions
         {
             return LogOptions::defaults()
                 ->logOnly([
                     'kardex_id',
                     'product_id',
                     'responsible_employee_id',
                     'establishment_id',
                     'movement_type_id',
                     'quantity',
                     'unit_price',
                     'total_price',
                     'comment'
                 ])
                ->useLogName('DetailKardex Log');
        }
}
