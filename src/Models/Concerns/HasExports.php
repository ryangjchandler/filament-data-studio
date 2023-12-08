<?php

namespace RyanChandler\DataStudio\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\HasMany;
use RyanChandler\DataStudio\Models\Export;

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
