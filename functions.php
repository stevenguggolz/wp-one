<?php

/**
 * Child theme Stylesheet einbinden 
 */

function child_theme_styles()
{
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-theme-css', get_stylesheet_directory_uri() . '/style.css', array('parent-style'));
}
add_action('wp_enqueue_scripts', 'child_theme_styles');



/**
 * Load functions to secure your WP install.
 */
require_once(get_stylesheet_directory() . '/includes/security.php');

/**
 * base enqueue scripts
 */

if (!function_exists('onedot_scripts_styles')) {
    /**
     * Theme Scripts & Styles.
     *
     * @return void
     */
    function onedot_scripts_styles()
    {
        $enqueue_basic_style = apply_filters_deprecated('onedot_theme_enqueue_style', [true], '1.0', 'onedot_enqueue_style');
        $min_suffix          = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        if (apply_filters('onedot_enqueue_style', $enqueue_basic_style)) {
            $the_theme = wp_get_theme();
            wp_enqueue_style(
                'onedot-styles',
                get_stylesheet_directory_uri() . '/dist/styles/theme' . $min_suffix . '.css',
                ['elementor-frontend'],
                $the_theme->get('Version')
            );

            wp_enqueue_script(
                'onedot-scripts',
                get_stylesheet_directory_uri() . '/dist/scripts/theme' . $min_suffix . '.js',
                ['jquery'],
                $the_theme->get('Version'),
                true
            );
        }
    }
}
add_action('wp_enqueue_scripts', 'onedot_scripts_styles', 100);

/**
 * Custom functions that adds shortcode php.
 */
require_once(get_stylesheet_directory() . '/includes/shortcode.php');

/**
 * Onedot Elementor Adds
 */

function admin_notice_missing_main_plugin()
{

    if (isset($_GET['activate'])) unset($_GET['activate']);

    $message = sprintf(
        /* translators: 1: Plugin Name 2: Elementor */
        esc_html__('%1$s requires "%2$s" to be installed and activated.', 'text-domain'),
        '<strong>' . esc_html__('Elementor', 'text-domain') . '</strong>'
    );

    printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
}

if (!did_action('elementor/loaded')) {
    add_action('admin_notices', 'admin_notice_missing_main_plugin');
} else {
    require_once(get_stylesheet_directory() . '/includes/elementor/elementor.php');
    require_once(get_stylesheet_directory() . '/includes/elementor/widgets/widgets.php');
}



// Removes from admin menu
add_action( 'admin_menu', 'my_remove_admin_menus' );
function my_remove_admin_menus() {
    remove_menu_page( 'edit-comments.php' );
}
// Removes from post and pages
add_action('init', 'remove_comment_support', 100);

function remove_comment_support() {
    remove_post_type_support( 'post', 'comments' );
    remove_post_type_support( 'page', 'comments' );
}
// Removes from admin bar
function mytheme_admin_bar_render() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('comments');
}
add_action( 'wp_before_admin_bar_render', 'mytheme_admin_bar_render' );
