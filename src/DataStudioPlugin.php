<?php

namespace RyanChandler\DataStudio;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Illuminate\Support\Facades\Route;
use RyanChandler\DataStudio\Http\Controllers\DownloadController;
use RyanChandler\DataStudio\Models\Export;
use RyanChandler\DataStudio\Resources\ExportResource;

class DataStudioPlugin implements Plugin
{
    protected string $ownerModelClass = 'App\\Models\\User';

    protected string $exportModelClass = Export::class;

    public function getId(): string
    {
        return 'filament-data-studio';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->authenticatedRoutes(function () {
                Route::get('/data-studio/exports/{export}/download', DownloadController::class)
                    ->name('data-studio.exports.download');
            })
            ->resources([
                ExportResource::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    /**
     * @param  class-string<Export>  $class
     */
    public function exportModelClass(string $class): static
    {
        $this->exportModelClass = $class;

        return $this;
    }

    public function getExportModel(): Export
    {
        return new $this->exportModelClass;
    }

    /**
     * @param  class-string<\Illuminate\Database\Eloquent\Model>  $class
     */
    public function ownerModelClass(string $class): static
    {
        $this->ownerModelClass = $class;

        return $this;
    }

    public function getOwnerModelClass(): string
    {
        return $this->ownerModelClass;
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }
}
