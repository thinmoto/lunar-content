<?php

namespace Thinmoto\LunarContent\Http\Livewire\Lunar\Hub\Content;

use Lunar\LivewireTables\Components\Table;
use Thinmoto\LunarContent\Tables\Builders\ContentTableBuilder;

class ContentTable extends Table
{
    /**
     * {@inheritDoc}
     */
    protected $tableBuilderBinding = ContentTableBuilder::class;

    /**
     * {@inheritDoc}
     */
    public function getData()
    {
        $filters = $this->filters;
        $query = $this->query;

        return $this->tableBuilder->searchTerm($query)->queryStringFilters($filters)->perPage($this->perPage)->sort($this->sortField ?: 'created_at', $this->sortDir ?: 'desc',)->getData();
    }
}
