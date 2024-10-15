<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'inventories';
    public $fillable = [
        'id',
        'type',
        'start_date',
        'end_date',
        'description',
        'state',
        'progress',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = ['created_at_format_date'];


    public function createdAtFormatDate() : Attribute
    {
        return Attribute::make(
            get : fn($value,$attributes) => Carbon::parse($attributes['created_at'])->format('T-m-d'),
        );
    }
}
