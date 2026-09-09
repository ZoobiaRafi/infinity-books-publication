<?php

namespace App\Models;

use App\Models\Concerns\ResolvesUploadedImageUrl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PortfolioItem extends Model
{
    use ResolvesUploadedImageUrl;

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

    protected function getImageUrlAttribute(): ?string
    {
        return $this->resolveImageUrl($this->image);
    }
}
