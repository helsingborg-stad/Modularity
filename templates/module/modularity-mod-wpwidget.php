<?php

    //Get settings for widget
    $settings_array =   Modularity\Module\WpWidget\WpWidget::createSettingsArray(get_field('mod_standard_widget_type', $module->ID), $module->ID);

    //Show widget with these settings
    Modularity\Module\WpWidget\WpWidget::displayWidget(
        get_field('mod_standard_widget_type', $module->ID),
        $settings_array
    );
