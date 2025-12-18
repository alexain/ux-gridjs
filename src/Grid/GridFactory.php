<?php

namespace Alexain\UxGridjs\Grid;

final class GridFactory
{
    /**
     * @param GridColumn[] $columns
     */
    public function create(
        string $dataUrl,
        array $columns,
        int $pageSize = 10,
        bool $search = true,
        bool $sort = true,
        array $options = [],
    ): GridDefinition {
        return new GridDefinition($dataUrl, $columns, $pageSize, $search, $sort, $options);
    }
}
