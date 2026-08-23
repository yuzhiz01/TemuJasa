<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'provider_id', 'category_id', 'title', 'shop_name',
        'description', 'price', 'location', 'image', 'is_active',
        'latitude', 'longitude',
    ];

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function options()
    {
        return $this->hasMany(ServiceOption::class);
    }
}
