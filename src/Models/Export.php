<?php

namespace RyanChandler\DataStudio\Models;

use Illuminate\Database\Eloquent\Model;
use RyanChandler\DataStudio\DataStudioPlugin;
use RyanChandler\DataStudio\EasyExportPlugin;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Export extends Model
{
    protected $table = 'easy_export_exports';

    protected $guarded = [];

    protected $casts = [
        'columns' => 'array',
        'filters' => 'array',
        'finished_at' => 'datetime',
        'total_rows' => 'int',
        'rows' => 'int',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(DataStudioPlugin::get()->getOwnerModelClass(), 'owner_id');
    }

    public function progress(): Attribute
    {
        return Attribute::get(fn () => $this->total_rows ? ($this->rows / $this->total_rows) * 100 : 0);
    }
}
