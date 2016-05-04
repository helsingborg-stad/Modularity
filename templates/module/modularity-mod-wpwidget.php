<?php
    $args = "";
    $instance = "";

    $moduleInstance = Modularity\Module\WpWidget\WpWidget::displayWidget(get_field('mod_standard_widget_type',$module->ID), $args, $instance);
