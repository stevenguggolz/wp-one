<?php

namespace Elementor;

class od_spacer extends Widget_Base
{

    public function get_name()
    {
        return 'od-spacer';
    }

    public function get_title()
    {
        return 'OD Spacer';
    }

    public function get_icon()
    {
        return 'eicon-spacer';
    }

    public function get_categories()
    {
        return ['basic'];
    }

    public function get_keywords()
    {
        return ['space'];
    }

    protected function _register_controls()
    {

        $this->start_controls_section(
            'section_spacer',
            [
                'label' => __('Spacer', 'elementor'),
            ]
        );

        $this->add_responsive_control(
            'size',
            [
                'label'          => __('Size', 'elementor'),
                'type'           => Controls_Manager::SELECT,
                'default'        => '0px',
                'options' => [
                    '0' => __('0px', 'elementor'),
                    '1' => __('10px', 'elementor'),
                    '2' => __('16px', 'elementor'),
                    '3' => __('30px', 'elementor'),
                    '4' => __('50px', 'elementor'),
                    '5' => __('80px', 'elementor'),
                    '6' => __('140px', 'elementor'),
                ],
                'style_transfer' => true,
                'devices' => ['desktop', 'tablet', 'mobile'],
                'prefix_class' => 'od-spacing%s-',
            ]
        );

        $this->add_control(
            'view',
            [
                'label' => __('View', 'elementor'),
                'type' => Controls_Manager::HIDDEN,
                'default' => 'traditional',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        ?>
            <div class="elementor-spacer">
                <div class="elementor-spacer-inner spacing--<?php echo $settings['size']; ?>"></div>
            </div>
        <?php
    }

    // ADD CUSTOM CSS
    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
        wp_register_style('widget-spacer-styles', get_stylesheet_directory_uri() . '/dist/styles/widgets/spacing.css');
    }
    public function get_style_depends()
    {
        return ['widget-spacer-styles'];
    }

    protected function _content_template()
    {
        ?>
            <div class="elementor-spacer">
                <div class="elementor-spacer-inner spacing--{{settings.size}}"></div>
            </div>
        <?php
    }
}