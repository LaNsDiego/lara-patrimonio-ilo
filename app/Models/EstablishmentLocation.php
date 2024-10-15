<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstablishmentLocation extends Model
{
    use HasFactory;
    use SoftDeletes;

    public $fillable = [
        'id',
        'code',
        'name',
        'establishment_id',
    ];


}
