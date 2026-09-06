<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortfolioItem extends Model
{
    protected $fillable = [
        'service_id',
        'order',
        'title',
        'category_label',
        'subtitle',
        'image',
        'image_alt',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}
