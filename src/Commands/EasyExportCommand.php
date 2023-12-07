<?php

namespace RyanChandler\EasyExport\Commands;

use Illuminate\Console\Command;

class EasyExportCommand extends Command
{
    public $signature = 'filament-easy-export';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
