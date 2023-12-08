<?php

namespace RyanChandler\DataStudio\Actions;

use Filament\Actions\Action as Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Actions\Action as NotificationAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\Action as BaseAction;
use Filament\Tables\Columns\Column;
use Filament\Tables\Table;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use RyanChandler\DataStudio\DataStudioPlugin;
use RyanChandler\DataStudio\Jobs\ProcessExport;
use RyanChandler\DataStudio\Models\Export;
use RyanChandler\DataStudio\Resources\ExportResource;

class ExportAction extends BaseAction
{
    protected ?string $disk = null;

    protected ?string $directory = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->form(fn (ExportAction $action) => [
            TextInput::make('name')
                ->required(),
            CheckboxList::make('columns')
                ->options($action->getToggleableColumnOptions())
                ->columns(3),
            Group::make($action->getTable()->getFiltersForm()->getComponents(withHidden: true))
                ->statePath('filters'),
        ]);

        $this->fillForm(function (ExportAction $action, Table $table): array {
            $pluralModelLabel = $table->getPluralModelLabel();
            $filters = $table->getFiltersForm()->getRawState();

            return [
                'name' => $action->getActiveTableTab() ? sprintf('%s (%s) - %s', Str::headline($pluralModelLabel), $action->getActiveTableTabLabel(), now()->format('Y-m-d H:i:s')) : sprintf('%s - %s', Str::headline($pluralModelLabel), now()->format('Y-m-d H:i:s')),
                'columns' => $action->getVisibleColumns(),
                'filters' => $filters,
            ];
        });

        $this->modalHeading(function (Table $table): string {
            return sprintf('Export %s', $table->getPluralModelLabel());
        });

        $this->modalSubmitActionLabel('Export');

        $this->modalFooterActions(fn (ExportAction $action) => [
            $action->getModalSubmitAction(),
            $action->getModalCancelAction(),
        ]);

        $this->action(function (ExportAction $action, array $data) {
            $export = DataStudioPlugin::get()->getExportModel()->create([
                'owner_id' => Auth::id(),
                'name' => $data['name'],
                'columns' => $data['columns'],
                'filters' => $data['filters'],
                'tab' => $action->getActiveTableTab(),
                'disk' => $action->getDisk(),
                'directory' => $action->getDirectory(),
                'page_class' => $action->getTable()->getLivewire()::class,
                'panel_id' => Filament::getCurrentPanel()->getId(),
            ]);

            dispatch(new ProcessExport($export));

            Notification::make()
                ->title('Export started.')
                ->body(sprintf(<<<'HTML'
                Export <span class="font-medium">"%s"</span> has started and will be generated in the background.
                HTML, $data['name']))
                ->duration(7500)
                ->success()
                ->actions([
                    NotificationAction::make('progress')
                        ->button()
                        ->size(ActionSize::Small)
                        ->label('View progress')
                        ->color('gray')
                        ->url(ExportResource::getUrl('index')),
                ])
                ->send();
        });
    }

    public function disk(string $disk): static
    {
        $this->disk = $disk;

        return $this;
    }

    public function directory(string $directory): static
    {
        $this->directory = $directory;

        return $this;
    }

    public function getDisk(): ?string
    {
        return $this->disk;
    }

    public function getDirectory(): ?string
    {
        return $this->directory;
    }

    protected function getVisibleColumns(): array
    {
        $columns = $this->getTable()->getColumns();
        $visible = [];

        foreach ($columns as $column) {
            if (! $column->isToggleable() || ! $column->isToggledHidden()) {
                $visible[] = $column->getName();
            }
        }

        return $visible;
    }

    protected function getActiveTableTab(): ?string
    {
        $livewire = $this->getLivewire();

        if ($livewire instanceof ListRecords) {
            return $livewire->activeTab;
        }

        return null;
    }

    protected function getActiveTableTabLabel(): ?string
    {
        $tab = $this->getActiveTableTab();

        if (! $tab) {
            return null;
        }

        return $this->getLivewire()->generateTabLabel($tab);
    }

    protected function getToggleableColumnOptions(): array
    {
        return Arr::mapWithKeys($this->getTable()->getColumns(), fn (Column $column) => [
            $column->getName() => $column->getLabel(),
        ]);
    }

    protected function getPreviousExportsAction(): Action
    {
        return Action::make('previousExports')
            ->label('Previous Exports')
            ->link();
    }

    public static function getDefaultName(): ?string
    {
        return 'export';
    }
}
