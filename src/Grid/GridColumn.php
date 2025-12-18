<?php

namespace Alexain\UxGridjs\Grid;

final class GridColumn
{
    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly string $type = 'text',
        public readonly bool $sortable = true,
        public readonly bool $searchable = true,
        public readonly ?string $width = null,
        public readonly array $options = [],
    ) {
    }
}
