<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $customer_id
 * @property int|null $provider_id
 * @property string $service_name
 * @property string $provider_name
 * @property int $total
 * @property string $status
 */
class Order extends Model
{
    protected $fillable = [
        'customer_id',
        'provider_id',
        'service_id',
        'option_id',
        'service_name',
        'provider_name',
        'total',
        'status',
        'notes',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'provider_id');
    }
}
