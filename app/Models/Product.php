<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model
{
    use HasFactory, SoftDeletes,LogsActivity;

    protected $fillable = [
        'barcode',
        'image',
        'serial_number',
        'product_type_id',
        'employee_id',
        // 'establishment_id',
        'rental_price',
        'acquisition_cost',
        'siga_code',
        'accounting_account',
        'order_number',
        'pecosa_number',
        'dimensions',
        'license_plate',
        'manufacture_year',
        'color',
        'chassis',
        'engine',
        'historical_value',
        'status',
        'responsible_employee_id',
        'establishment_location_id'
    ];

    protected $appends = ['full_path'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function responsible()
    {
        return $this->belongsTo(Employee::class, 'responsible_employee_id','id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class,'brand_id');
    }

    public function product_type()
    {
        return $this->belongsTo(ProductType::class);
    }

    public function establishment_location()
    {
        return $this->belongsTo(EstablishmentLocation::class);
    }


    public function detail_kardex()
    {
        return $this->hasMany(DetailKardex::class, 'product_id');
    }

    public function stock()
    {
        // return $this->detail_kardex()->sum('quantity');
        $details = $this->detail_kardex()->with('movement_type')->get();

        $stock = 0;

        foreach ($details as $detail) {
            if ($detail->movement_type->name == 'SALIDA') {
                $stock -= $detail->quantity;
            } else {
                $stock += $detail->quantity;
            }
        }

        return $stock;
    }

    public static function generateUniqueBarcode()
    {
        do {
            $barcode = str_pad((string)self::max('id') + 1, 11, '0', STR_PAD_LEFT);
        } while (self::where('barcode', $barcode)->exists());

        return $barcode;
    }

    public function product_location()
    {
        return $this->hasMany(ProductLocation::class);
    }

    protected function fullPath() : Attribute
    {
        return Attribute::make(
            get : fn ($value,$attributes) => config('extravars.storage')."/".$attributes['image']
        );
    }

    //Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'barcode',
                'image',
                'serial_number',
                'product_type_id',
                'employee_id',
                'establishment_id',
                'acquisition_cost',
                'siga_code',
                'accounting_account',
                'order_number',
                'pecosa_number',
                'dimensions',
                'license_plate',
                'manufacture_year',
                'color',
                'chassis',
                'engine',
                'historical_value',
                'status',
                'responsible_employee_id',
            ])
            ->useLogName('Product Log');
        }

}
