<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ModuleGroup extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $table = 'module_groups';
    protected $fillable = ['name'];


    public function system_modules(){
        return $this->hasMany(SystemModule::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'name',
            ])
            ->useLogName('module_group');
    }
}
