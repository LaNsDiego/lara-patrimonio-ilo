<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncidentPhoto extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'incident_photos';
    protected $fillable = ['id','incident_id', 'path'];

    protected $appends = ['full_path'];
    protected function fullPath() : Attribute
    {
        return Attribute::make(
            get : fn ($value,$attributes) => config('extravars.storage')."/".$attributes['path']
        );
    }
}
