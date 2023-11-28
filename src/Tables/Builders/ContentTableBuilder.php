<?php

namespace Thinmoto\LunarContent\Tables\Builders;

use App\Models\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Lunar\Hub\Tables\TableBuilder;
use Lunar\LivewireTables\Components\Columns\TextColumn;
use Thinmoto\LunarContent\Models\Content;

class ContentTableBuilder extends TableBuilder
{
    public function getColumns(): Collection
    {
        $baseColumns = collect([
            TextColumn::make('name')->heading(__('lunarcontent::content.hub.name'))->value(function ($record) {
                return Str::words($record->name, 5);
            })->url(function ($record) {
                return route('lunar-content.content.show', $record->id);
            }),
        ]);

        return $this->resolveColumnPositions(
            $baseColumns,
            $this->columns
        );
    }
    /**
     * Return the query data.
     *
     * @param  string|null  $searchTerm
     * @param  array  $filters
     * @param  string  $sortField
     * @param  string  $sortDir
     * @return LengthAwarePaginator
     */
    public function getData(): iterable
    {
        $query = Content::query()->orderBy('id');

        // if ($this->searchTerm) {
        //     $query->whereIn('id', Content::search($this->searchTerm)->keys());
        // }

        // $filters = collect($this->queryStringFilters)->filter(function ($value) {
        //     return (bool) $value;
        // });
        //
        // foreach ($this->queryExtenders as $qe) {
        //     call_user_func($qe, $query, $this->searchTerm, $filters);
        // }
        //
        // // Get the table filters we want to apply.
        // $tableFilters = $this->getFilters()->filter(function ($filter) use ($filters) {
        //     return $filters->has($filter->field);
        // });
        //
        // foreach ($tableFilters as $filter) {
        //     if ($closure = $filter->getQuery()) {
        //         call_user_func($filter->getQuery(), $filters, $query);
        //     }
        // }

        return $query->paginate($this->perPage);
    }
}
