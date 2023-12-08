<?php

namespace RyanChandler\EasyExport;

use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Livewire\Livewire;
use RyanChandler\EasyExport\Models\Export;

class ExportProcessor
{
    protected Export $export;

    protected ListRecords $page;

    protected Table $table;

    protected array $columnFormatters = [];

    public function setUp(Export $export): void
    {
        $this->export = $export;

        $this->page = Livewire::new($export->page_class);
        $this->page->tableFilters = $export->filters ?? [];
        $this->page->bootedInteractsWithTable();

        $this->table = $this->page->getTable();
    }

    public function getFormattedColumnNames(): array
    {
        $names = [];

        foreach ($this->export->columns as $column) {
            $names[] = $this->table->getColumn($column)->getLabel();
        }

        return $names;
    }

    public function formatRow(Model $record, array $columns): array
    {
        $row = [];

        foreach ($columns as $column) {
            $row[] = $this->formatColumn($record, $column);
        }

        return $row;
    }

    public function formatColumn(Model $record, string $column): mixed
    {
        $column = $this->table->getColumn($column)->record($record);
        $state = $column->getState();

        if ($column instanceof ImageColumn) {
            $images = Arr::wrap($column->getState());
            $values = [];

            foreach ($images as $image) {
                $values[] = $column->getImageUrl($image);
            }

            return implode(', ', $values);
        }

        if (method_exists($column, 'formatState')) {
            return $column->formatState($state);
        }

        return $state;
    }

    public function getQuery(): Builder
    {
        $query = $this->page->getFilteredSortedTableQuery();

        if ($this->export->tab !== null) {
            $this->page->activeTab = $this->export->tab;

            invade($this->page)->modifyQueryWithActiveTab($query);
        }

        return $query;
    }

    public function getTable(): Table
    {
        return $this->table;
    }
}
