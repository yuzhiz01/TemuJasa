<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceOption extends Model
{
    protected $fillable = ['service_id', 'name', 'price', 'description'];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
