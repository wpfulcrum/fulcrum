<?php

namespace Fulcrum\Custom\Widget;

interface WidgetContract
{
    /**
     * Echo the widget content.
     *
     * @since 3.0.0
     *
     * @param array $args Display arguments including
     *                          before_title, after_title, before_widget, & after_widget.
     * @param array $instance The settings for the particular instance of the widget
     *
     * @return void
     */
    public function widget($args, $instance);

    /**
     * Echo the settings update form.
     *
     * @since 3.0.0
     *
     * @param array $instance Current settings.
     *
     * @return void
     */
    public function form($instance);

    /**
     * Update a particular instance.
     *
     * This function should check that $new_instance is set correctly.
     * The newly calculated value of $instance should be returned.
     * If false is returned, the instance won't be saved / updated.
     *
     * @since 3.0.0
     *
     * @param array $newInstance New settings for this instance as input by the user via form().
     * @param array $oldInstance Old settings for this instance.
     *
     * @return array    Settings to save or bool false to cancel saving
     */
    public function update($newInstance, $oldInstance);
}
