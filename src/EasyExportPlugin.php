<?php

namespace RyanChandler\EasyExport;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Illuminate\Support\Facades\Route;
use RyanChandler\EasyExport\Http\Controllers\DownloadController;
use RyanChandler\EasyExport\Resources\ExportResource;

class EasyExportPlugin implements Plugin
{
    protected string $ownerModelClass = 'App\\Models\\User';

    public function getId(): string
    {
        return 'filament-easy-export';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->authenticatedRoutes(function () {
                Route::get('/easy-export/{export}/download', DownloadController::class)
                    ->name('easy-export.download');
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
