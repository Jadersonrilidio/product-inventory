<?php

declare(strict_types=1);

namespace Jayrods\ProductInventory\Http\Core;

class View
{
    /**
     * Render content to base template.
     *
     * @param string $title   Page title.
     * @param string $content Page content to be loaded.
     *
     * @return string Returns the rendered page to be send.
     */
    public function renderTemplate(string $title = 'Jayrods MVC Framework', string $content = ''): string
    {
        $template = file_get_contents(TEMPLATE_DIR . 'template.html');

        $this->loadContent(
            $template,
            [
                'title' => $title,
                'content' => $content
            ]
        );

        return $template;
    }

    /**
     * Render content to a view.
     *
     * @param string $viewTemplate View file name.
     * @param string $content      Content to be added to view.
     *
     * @return string Rendered view.
     */
    public function renderView(string $viewTemplate, array $content = []): string
    {
        $view = file_get_contents(VIEW_DIR . $viewTemplate . '.html');

        $this->loadContent($view, $content);

        return $view;
    }

    /**
     * Render content into a component.
     *
     * @param string $componentTemplate Component file name.
     * @param string $content           Content to be added to component.
     *
     * @return string Rendered component.
     */
    public function renderComponent(string $componentTemplate, array $content = []): string
    {
        $component = file_get_contents(COMPONENTS_DIR . $componentTemplate . '.html');

        $this->loadContent($component, $content);

        return $component;
    }

    /**
     * Inject content into a HTML file placeholders.
     *
     * Insertion points are defined in the HTML by double moustache syntax. Ex: {{ placeholder }}.
     *
     * @param string $page    target HTML content.
     * @param array  $content Associative array of contents in the form 'placeholder' => 'content'.
     *
     * @return void
     */
    private function loadContent(string &$page, array $content): void
    {
        foreach ($content as $placeholder => $value) {
            $page = str_replace('{{' . $placeholder . '}}', $value, $page);
        }
    }
}
