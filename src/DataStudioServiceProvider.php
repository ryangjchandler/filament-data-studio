<?php

namespace RyanChandler\DataStudio;

use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Schema;
use Livewire\Features\SupportTesting\Testable;
use RyanChandler\DataStudio\Testing\TestsEasyExport;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class DataStudioServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-data-studio';

    public static string $viewNamespace = 'data-studio';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->startWith(function (InstallCommand $command) {
                        if (Schema::hasTable('notifications')) {
                            return;
                        }

                        $command->warn('This package requires database notifications. Publishing the notifications table migrations now.');
                        $command->call('notifications:table');

                        $command->warn('Make sure you enable Filament support for database notifications too: https://filamentphp.com/docs/3.x/panels/notifications');
                    })
                    ->publishMigrations()
                    ->askToRunMigrations();
            });

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void
    {
    }

    public function packageBooted(): void
    {
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/filament-data-studio/{$file->getFilename()}"),
                ], 'filament-data-studio-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsEasyExport());
    }

    protected function getAssetPackageName(): ?string
    {
        return 'ryangjchandler/filament-data-studio';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('filament-data-studio', __DIR__ . '/../resources/dist/components/filament-data-studio.js'),
            // Css::make('filament-data-studio-styles', __DIR__ . '/../resources/dist/filament-data-studio.css'),
            // Js::make('filament-data-studio-scripts', __DIR__ . '/../resources/dist/filament-data-studio.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            'create_exports_table',
            'add_disk_columns_to_exports_table',
            'add_panel_id_to_exports_table',
            'add_tab_to_exports_table',
        ];
    }
}
