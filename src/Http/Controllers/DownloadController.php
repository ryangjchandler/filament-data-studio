<?php

namespace RyanChandler\EasyExport\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use RyanChandler\EasyExport\Models\Export;

class DownloadController
{
    public function __invoke(Request $request, Export $export)
    {
        abort_if(! $export->owner->is($request->user()), 403);

        $path = Storage::disk($export->disk)->path($export->directory . '/' . $export->name . '.csv');

        return response()->download($path, $export->name . '.csv');
    }
}
