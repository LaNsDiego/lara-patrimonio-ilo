<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Establishment extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    const NAME_ESTABLISHMENT_TARGET = 'PATRIMONIO';
    const ESTABLISHMENT_TARGET_ID = 1;

    protected $fillable = [
        'parent_id',
        'code',
        'acronym',
        'name',
        'establishment_type_id',
        'location_id',
        'responsible_id',
    ];

    public function establishment_type()
    {
        return $this->belongsTo(EstablishmentType::class);
    }

    public function parent()
    {
        return $this->belongsTo(Establishment::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Establishment::class, 'parent_id');
    }

    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function responsible()
    {
        return $this->belongsTo(Employee::class, 'responsible_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function kardex()
    {
        return $this->hasMany(Kardex::class);
    }

    //Activity Log
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'parent_id',
                'code',
                'acronym',
                'name',
                'establishment_type_id',
                'location_id',
                'responsible_id',
            ])
            ->useLogName('Establishment Log');
    }
}
