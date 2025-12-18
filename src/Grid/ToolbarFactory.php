<?php

namespace Alexain\UxGridjs\Grid;

final class ToolbarFactory
{
    public static function default(): array
    {
        return [
            self::resetSort(),
            self::refresh(),
        ];
    }

    public static function resetSort(string $label = 'Reset sort'): array
    {
        return [
            'id' => 'resetSort',
            'label' => $label,
            'class' => 'btn btn-sm',
            'action' => 'click->grid#resetSort',
        ];
    }

    public static function refresh(string $label = 'Aggiorna'): array
    {
        return [
            'id' => 'refresh',
            'label' => $label,
            'class' => 'btn btn-sm',
            'action' => 'click->grid#refresh',
        ];
    }
}
