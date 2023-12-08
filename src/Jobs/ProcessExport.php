<?php

namespace RyanChandler\EasyExport\Jobs;

use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use RyanChandler\EasyExport\ExportProcessor;
use RyanChandler\EasyExport\Models\Export;
use Spatie\SimpleExcel\SimpleExcelWriter;

final class ProcessExport implements ShouldQueue
{
    use Dispatchable;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private Export $export,
    ) {
    }

    public function handle(ExportProcessor $processor)
    {
        $processor->setUp($this->export);

        $path = Storage::disk($this->export->disk)->path($this->export->directory . '/' . $this->export->name . '.csv');
        $writer = SimpleExcelWriter::create($path);

        $writer->addHeader($processor->getFormattedColumnNames());

        $query = $processor->getQuery();

        $this->export->update([
            'total_rows' => $query->count(),
        ]);

        $query->chunkById(100, function (Collection $records) use ($processor, $writer) {
            $writer->addRows(
                $records->map(fn (Model $record) => $processor->formatRow($record, $this->export->columns))->all(),
            );

            $this->export->update([
                'rows' => $this->export->rows + $records->count(),
            ]);
        });

        $this->export->update([
            'finished_at' => now(),
        ]);

        Notification::make()
            ->icon('heroicon-o-arrow-down-tray')
            ->title('Your export is ready!')
            ->body(sprintf('Export "%s" is now ready to download!', $this->export->name))
            ->actions([
                Action::make('download')
                    ->label('Download')
                    ->color('success')
                    ->url(route('filament.' . $this->export->panel_id . '.easy-export.download', $this->export))
                    ->openUrlInNewTab(),
            ])
            ->sendToDatabase($this->export->owner);
    }
}
