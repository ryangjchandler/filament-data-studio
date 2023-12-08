<?php

namespace RyanChandler\DataStudio\Resources;

use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use RyanChandler\DataStudio\DataStudioPlugin;
use RyanChandler\DataStudio\Models\Export;
use RyanChandler\DataStudio\Resources\ExportResource\ManageExports;
use RyanChandler\FilamentProgressColumn\ProgressColumn;

class ExportResource extends Resource
{
    protected static ?string $path = 'data-studio/exports';

    protected static ?string $navigationGroup = 'Data Studio';

    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-tray';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                ProgressColumn::make('progress')
                    ->progress(fn (Export $export) => $export->progress)
                    ->poll(fn (Export $export) => $export->progress < 100 ? 5000 : null)
                    ->extraCellAttributes(['style' => 'width: 75%']),
                TextColumn::make('finished_at')
                    ->label('Finished at')
                    ->dateTime(),
            ])
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(function (Builder $query) {
                $query->where('owner_id', Auth::id());
            })
            ->actions([
                Action::make('download')
                    ->color('success')
                    ->url(fn (Export $export) => route('filament.' . Filament::getCurrentPanel()->getId() . '.data-studio.exports.download', $export))
                    ->openUrlInNewTab(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageExports::route('/'),
        ];
    }

    public static function getModel(): string
    {
        return DataStudioPlugin::get()->getExportModel()::class;
    }
}
