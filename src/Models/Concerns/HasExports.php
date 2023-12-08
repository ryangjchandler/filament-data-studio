<?php

namespace RyanChandler\DataStudio\Models\Concerns;

use Illuminate\Database\Eloquent\Relations\HasMany;
use RyanChandler\DataStudio\DataStudioPlugin;
use RyanChandler\DataStudio\Models\Export;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait HasExports
{
    public function exports(): HasMany
    {
        return $this->hasMany(DataStudioPlugin::get()->getExportModel()::class, 'owner_id');
    }
}
