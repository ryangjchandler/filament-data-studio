<?php

namespace RyanChandler\EasyExport\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use RyanChandler\EasyExport\EasyExportPlugin;

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
        return $this->belongsTo(EasyExportPlugin::get()->getOwnerModelClass(), 'owner_id');
    }

    public function progress(): Attribute
    {
        return Attribute::get(fn () => $this->total_rows ? ($this->rows / $this->total_rows) * 100 : 0);
    }
}
