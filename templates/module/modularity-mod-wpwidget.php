<?php

    //Get settings for widget
    $settings = Modularity\Module\WpWidget\WpWidget::createSettingsArray(get_field('mod_standard_widget_type', $module->ID), $module->ID);

    // Widget before
    echo apply_filters('Modularity/Module/WpWidget/before', '<div class="box">', $args, $module);

    //Show widget with these settings
    Modularity\Module\WpWidget\WpWidget::displayWidget(
        get_field('mod_standard_widget_type', $module->ID),
        $settings
    );

    echo apply_filters('Modularity/Module/WpWidget/after', '</div>', $args, $module);
