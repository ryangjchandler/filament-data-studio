<?php

namespace RyanChandler\EasyExport\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\HasMany;
use RyanChandler\EasyExport\Models\Export;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait HasExports
{
    public function exports(): HasMany
    {
        return $this->hasMany(Export::class, 'owner_id');
    }
}
