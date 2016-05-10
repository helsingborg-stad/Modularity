<?php

    //Get settings for widget
    $settings = Modularity\Module\WpWidget\WpWidget::createSettingsArray(get_field('mod_standard_widget_type', $module->ID), $module->ID);

    var_dump($settings);

    //Show widget with these settings
    Modularity\Module\WpWidget\WpWidget::displayWidget(
        get_field('mod_standard_widget_type', $module->ID),
        $settings
    );
