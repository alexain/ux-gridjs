<?php

namespace Alexain\UxGridjs\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class GridJsExtension extends AbstractExtension
{
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

        $wrapperClass = (string) ($options['wrapper_class'] ?? '');
        $containerClass = (string) ($options['container_class'] ?? '');

        $wrapperClassAttr = '' !== $wrapperClass
            ? ' class="'.htmlspecialchars($wrapperClass, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8').'"'
            : '';

        $containerClassAttr = '' !== $containerClass
            ? ' class="'.htmlspecialchars($containerClass, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8').'"'
            : '';

        $toolbarButtons = is_array($options['toolbar_buttons'] ?? null) ? $options['toolbar_buttons'] : [];
        $toolbarHtml = $this->renderToolbar($toolbarButtons);

        return sprintf(
            '<div data-controller="grid" data-grid-config-value="%s"%s>%s<div data-grid-target="container"%s></div></div>',
            $json,
            $wrapperClassAttr,
            $toolbarHtml,
            $containerClassAttr
        );
    }

    /**
     * @param array<int, array<string, mixed>> $buttons
     */
    private function renderToolbar(array $buttons): string
    {
        if ([] === $buttons) {
            return '';
        }

        $html = '<div class="flex gap-2 mb-3">';

        foreach ($buttons as $btn) {
            if (!is_array($btn)) {
                continue;
            }

            $label = (string) ($btn['label'] ?? '');
            $action = (string) ($btn['action'] ?? '');
            if ('' === $label || '' === $action) {
                continue;
            }

            $class = (string) ($btn['class'] ?? 'btn btn-sm');
            $attrs = is_array($btn['attrs'] ?? null) ? $btn['attrs'] : [];

            $attrsStr = '';
            foreach ($attrs as $name => $value) {
                $attrsStr .= sprintf(
                    ' %s="%s"',
                    htmlspecialchars((string) $name, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
                    htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
                );
            }

            $html .= sprintf(
                '<button type="button" class="%s" data-action="%s"%s>%s</button>',
                htmlspecialchars($class, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
                htmlspecialchars($action, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'),
                $attrsStr,
                htmlspecialchars($label, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')
            );
        }

        $html .= '</div>';

        return $html;
    }
}
