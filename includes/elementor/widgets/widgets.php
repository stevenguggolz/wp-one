<?php

class Onedot_Elementor_Widgets
{

    protected static $instance = null;

    public static function get_instance()
    {
        if (!isset(static::$instance)) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    protected function __construct()
    {
        require_once('spacer.php');
        require_once('image.php');
        add_action('elementor/widgets/widgets_registered', [$this, 'register_widgets']);
    }

    public function register_widgets()
    {
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor\od_spacer());
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \Elementor\OD_Image());
    }
}

add_action('init', 'my_elementor_init');
function my_elementor_init()
{
    Onedot_Elementor_Widgets::get_instance();
}

/*
 * Elementor Add Custom Categories
 * https://developers.elementor.com/widget-categories/
 *
 */

function add_elementor_widget_categories($elements_manager)
{

    $elements_manager->add_category(
        'onedot-category',
        [
            'title' => __('ONEDOT Widgets', 'onedot'),
            'icon'  => 'fa fa-plug',
        ]
    );
}

add_action('elementor/elements/categories_registered', 'add_elementor_widget_categories');
