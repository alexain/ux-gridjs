<?php

namespace Alexain\UxGridjs\Grid;

final class GridDefinition
{
    /**
     * @param GridColumn[] $columns
     */
    public function __construct(
        public readonly string $dataUrl,
        public readonly array $columns,
        public readonly int $pageSize = 10,
        public readonly bool $search = true,
        public readonly bool $sort = true,
        public array $options = [],
    ) {
    }

    public function withToolbar(array $buttons): self
    {
        $this->options['toolbar_buttons'] = $buttons;

        return $this;
    }
}
