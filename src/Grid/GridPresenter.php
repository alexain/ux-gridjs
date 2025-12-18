<?php

namespace Alexain\UxGridjs\Grid;

final class GridPresenter
{
    public function toArray(GridDefinition $grid): array
    {
        return [
            'dataUrl' => $grid->dataUrl,
            'pageSize' => $grid->pageSize,
            'search' => $grid->search,
            'sort' => $grid->sort,
            'columns' => array_map(static fn (GridColumn $c) => [
                'id' => $c->id,
                'name' => $c->name,
                'type' => $c->type,
                'sortable' => $c->sortable,
                'searchable' => $c->searchable,
                'width' => $c->width,
                'options' => $c->options,
            ], $grid->columns),
            'options' => $grid->options,
        ];
    }
}
