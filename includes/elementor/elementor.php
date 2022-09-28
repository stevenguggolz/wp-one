<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
/**
 * Elementor Settings
 */


add_action('elementor/element/before_section_end', function ($section, $section_id, $args) {
    if ($section->get_name() == 'button' && $section_id == 'section_button') {
        $section->update_control('button_type', ['type' => Elementor\Controls_Manager::HIDDEN]);
    }

    if ($section->get_name() == 'column' && $section_id == 'layout') {
        $section->update_control('_inline_size', ['type' => Elementor\Controls_Manager::HIDDEN]);
        $section->update_control('_inline_size_tablet', ['type' => Elementor\Controls_Manager::HIDDEN]);
        $section->update_control('_inline_size_mobile', ['type' => Elementor\Controls_Manager::HIDDEN]);

        $section->add_responsive_control(
            'size',
            [
                'label' => __('ONEDOT Column Width', 'elementor') . ' (%)',
                'type' => Elementor\Controls_Manager::NUMBER,
                'min' => 2,
                'max' => 100,
                'required' => true,
                'device_args' => [
                    Elementor\Controls_Stack::RESPONSIVE_TABLET => [
                        'max' => 100,
                        'required' => false,
                    ],
                    Elementor\Controls_Stack::RESPONSIVE_MOBILE => [
                        'max' => 100,
                        'required' => false,
                    ],
                ],
                'min_affected_device' => [
                    Elementor\Controls_Stack::RESPONSIVE_DESKTOP => Elementor\Controls_Stack::RESPONSIVE_TABLET,
                    Elementor\Controls_Stack::RESPONSIVE_TABLET => Elementor\Controls_Stack::RESPONSIVE_TABLET,
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'width: {{VALUE}}%',
                ],
            ]
        );
    }
}, 10, 3);

add_action('elementor/element/after_section_start', function ($section, $section_id, $args) {
    if ($section->get_name() == 'button' && $section_id == 'section_button') {
        $section->add_control(
            'container_size',
            [
                'label'        => 'Style',
                'type'         => Elementor\Controls_Manager::SELECT,
                'default'      => 'primary',
                'options' => [
                    'primary' => esc_html__('Primary', 'elementor'),
                    'secondary' => esc_html__('Secondary', 'elementor'),
                    'link' => esc_html__('Link', 'elementor'),
                ],
                'prefix_class' => 'childtheme-button-'
            ]
        );
    }
}, 10, 3);


add_action('elementor/widgets/widgets_registered', function ($widgets_manager) {
    $widgets_manager->unregister_widget_type('image');
}, 15);



/**
 * Elementor Widget addon Stylesheets
 */


function my_plugin_frontend_stylesheets()
{
    wp_register_style('od-button-styles', get_stylesheet_directory_uri() . '/dist/styles/widgets/buttons.css');
    wp_enqueue_style('od-button-styles');
}
add_action('elementor/frontend/after_enqueue_styles', 'my_plugin_frontend_stylesheets');