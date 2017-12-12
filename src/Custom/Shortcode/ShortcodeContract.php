<?php

namespace Fulcrum\Custom\Shortcode;

interface ShortcodeContract
{
    /**
     * Shortcode callback which merges the attributes, calls the render() method to build
     * the HTML, and then returns it.
     *
     * @since 3.0.0
     *
     * @param array|string $attributes Shortcode attributes
     * @param string|null $content Content between the opening & closing shortcode declarations
     *
     * @return string Shortcode HTML
     */
    public function renderCallback($attributes, $content = null);
}
