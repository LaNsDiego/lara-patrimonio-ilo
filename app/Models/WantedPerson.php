<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class WantedPerson extends Model
{
    use HasFactory,SoftDeletes,LogsActivity;
    protected $table = 'wanted_people';
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'dni',
        'photo'
    ];
    protected $appends = ['full_path'];

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }

    protected function fullPath() : Attribute
    {
        return Attribute::make(
            get : fn ($value,$attributes) => config('extravars.storage')."/".$attributes['photo']
        );
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'first_name',
                'last_name',
                'dni',
                'photo',
            ])
            ->useLogName('WantedPerson Log');
    }
}
