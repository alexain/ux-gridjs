<?php

namespace Alexain\UxGridjs\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class GridJsExtension extends AbstractExtension
{
    private const CONTROLLER = 'alexain--ux-gridjs--grid';

    public function getFunctions(): array
    {
        return [
            new TwigFunction('gridjs_render', [$this, 'render'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param array $grid Output di GridPresenter::toArray()
     */
    public function render(array $grid): string
    {
        $encoded = json_encode($grid, JSON_UNESCAPED_SLASHES);
        if (false === $encoded) {
            $encoded = '{}';
        }

        $json = htmlspecialchars($encoded, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

        $options = is_array($grid['options'] ?? null) ? $grid['options'] : [];
        $wrapperClass = (string)($options['wrapper_class'] ?? '');
        $containerClass = (string)($options['container_class'] ?? '');
        $toolbarButtons = is_array($options['toolbar_buttons'] ?? null) ? $options['toolbar_buttons'] : [];

        $wrapperClassAttr = $wrapperClass !== ''
            ? ' class="' . htmlspecialchars($wrapperClass, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '"'
            : '';

        $containerClassAttr = $containerClass !== ''
            ? ' class="' . htmlspecialchars($containerClass, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '"'
            : '';

        $toolbarHtml = $this->renderToolbar($toolbarButtons);

        return sprintf(
            '<div data-controller="%s" data-%s-config-value="%s"%s>%s<div data-%s-target="container"%s></div></div>',
            self::CONTROLLER,
            self::CONTROLLER,
            $json,
            $wrapperClassAttr,
            $toolbarHtml,
            self::CONTROLLER,
            $containerClassAttr
        );
    }

    /**
     * @param array<int, array<string, mixed>> $buttons
     */
    private function renderToolbar(array $buttons): string
    {
        if ($buttons === []) return '';

        $html = '<div class="flex gap-2 mb-3">';

        foreach ($buttons as $btn) {
            if (!is_array($btn)) continue;

            $label  = (string)($btn['label'] ?? '');
            $action = (string)($btn['action'] ?? '');
            if ($label === '' || $action === '') continue;

            // Se vuoi continuare a passare action "click->grid#resetSort",
            // normalizziamo qui sostituendo "grid" con l'id reale.
            $action = preg_replace('/^([^>]+)->grid#/', '$1->'.self::CONTROLLER.'#', $action) ?? $action;

            $class = (string)($btn['class'] ?? 'btn btn-sm');

            $html .= sprintf(
                '<button type="button" class="%s" data-action="%s">%s</button>',
                htmlspecialchars($class, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
                htmlspecialchars($action, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
                htmlspecialchars($label, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
            );
        }


        $html .= '</div>';

        return $html;
    }
}
