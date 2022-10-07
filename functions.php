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


/**
 * Elias WP Store Locator
 * START
 */

// Add the "konfession" field to meta box in the admin view
add_filter( 'wpsl_meta_box_fields', 'custom_meta_box_fields' );
function custom_meta_box_fields( $meta_fields ) {
    
    $meta_fields[__( 'Additional Information', 'wpsl' )] = array(
        'phone' => array(
            'label' => __( 'Tel', 'wpsl' )
        ),
        'ans_email' => array(
            'label' => __( 'Email', 'wpsl' )
        ),
		'Ansprechspartner' => array(
            'label' => __( 'Ansprechspartner', 'wpsl' )
        ),
		'firstname' => array(
            'label' => __( 'Vorname', 'wpsl' )
        ),
		'lastname' => array(
            'label' => __( 'Nachname', 'wpsl' )
        ),
		'function' => array(
            'label' => __( 'Funktion', 'wpsl' )
        ),
        'url' => array(
            'label' => __( 'Url', 'wpsl' )
        ),
        'Konfession' => array(
            'label' => __( 'Konfession', 'wpsl' )
        )
    );

    return $meta_fields;
}

// Add the "konfession" to json for the api
add_filter( 'wpsl_frontend_meta_fields', 'custom_frontend_meta_fields' );
function custom_frontend_meta_fields( $store_fields ) {
    $store_fields['wpsl_Konfession'] = array( 
        'name' => 'Konfession',
        'type' => 'text'
    );
    return $store_fields;
}

// Listing template with "konfession"
add_filter( 'wpsl_listing_template', 'custom_listing_template' );
function custom_listing_template() {

    global $wpsl_settings;

    $listing_template = '<li data-store-id="<%= id %>">' . "\r\n";
    $listing_template .= "\t\t" . '<div>' . "\r\n";
    $listing_template .= "\t\t\t" . '<p class="church"><%= thumb %>' . "\r\n";
    $listing_template .= "\t\t\t\t" . wpsl_store_header_template( 'listing' ) . "\r\n";
    $listing_template .= "\t\t\t\t" . '<span class="wpsl-street"><%= address %>,';
    $listing_template .= "\t\t\t\t" .  wpsl_address_format_placeholders() . '</span>' . "\r\n";
    $listing_template .= "\t\t\t\t" . '<span class="wpsl-country"><%= country %></span>' . "\r\n";
    $listing_template .= "\t\t\t" . '</p>' . "\r\n";
    
    // Check if the 'Konfession' contains data before including it.
    $listing_template .= "\t\t\t" . '<% if ( Konfession ) { %>' . "\r\n";
    $listing_template .= "\t\t\t" . '<p class="konfession"><%= Konfession %></p>' . "\r\n";
    $listing_template .= "\t\t\t" . '<% } %>' . "\r\n";
    
    $listing_template .= "\t\t" . '</div>' . "\r\n";

    // Check if we need to show the distance.
    if ( !$wpsl_settings['hide_distance'] ) {
        //$listing_template .= "\t\t" . '<%= distance %> ' . esc_html( $wpsl_settings['distance_unit'] ) . '(Luftlinie)' . "\r\n";
    	$listing_template .= "\t\t" . '<span><%= distance %> ' . esc_html( $wpsl_settings['distance_unit'] ) . ' (Luftlinie) <%= createDirectionUrl() %></span>' . "\r\n";
	}
 
    //$listing_template .= "\t\t" . '<%= createDirectionUrl() %>' . "\r\n"; 
    $listing_template .= "\t" . '</li>' . "\r\n"; 

    return $listing_template;
}

/**
 * END
 * Elias WP Store Locator 
 */

/*
 * Elias Add the elemementor form action for the church locator
 * START
*/


$file = ABSPATH.'wp-content/plugins/wp-store-locator/inc/wpsl-functions.php';
require_once($file);

add_action( 'elementor_pro/init', function() {
	// Here its safe to include our action class file
	Class Elementor_Form_Kirchenfinder_Action extends \ElementorPro\Modules\Forms\Classes\Action_Base {
		public function get_name() {
			return 'Kirchenfinder';
		}
		public function get_label() {
			return __( 'Kirchenfinder', 'text-domain' );
		}
		public function run( $record, $ajax_handler ) {
			// Get submitetd Form data
			$raw_fields = $record->get( 'fields' );
			// Normalize the Form Data
			$fields = [];
			foreach ( $raw_fields as $id => $field ) {
				$fields[ $id ] = $field['value'];
			}
			
            $latlng = explode(',', wpsl_check_latlng_transient( $fields[ 'street' ].' '.$fields[ 'nr' ] .', '.$fields[ 'plz' ].' '.$fields[ 'City' ].' '. $fields[ 'country' ] ));
			$post = array(
				'post_title'=>$fields[ 'name' ],
				'post_content'=>'',
				'post_status'=>'publish',
				'comment_status'=>'closed',
				'ping_status'=>'closed',
				'post_type'=>'wpsl_stores',
				'meta_input'=>array(
					'wpsl_address'    => $fields[ 'street' ].' '.$fields[ 'nr' ],
					'wpsl_city'       => $fields[ 'City' ],
					'wpsl_zip'        => $fields[ 'plz' ],
					'wpsl_country'    => $fields[ 'country' ],
					'wpsl_lat'        => $latlng[0],
					'wpsl_lng'        => $latlng[1],
					'wpsl_email'	  => '',
					'wpsl_ans_email'      => $fields[ 'email' ],
					'wpsl_firstname' => $fields[ 'firstname' ],
					'wpsl_lastname' => $fields[ 'lastname' ],
					'wpsl_function' => $fields[ 'function' ],
					'wpsl_url'        => $fields[ 'url' ],
					'wpsl_phone'      => '',
					'wpsl_Konfession' => $fields[ 'konfession' ],
				)
			);
			wp_insert_post( $post );
			$wpsl_admin = new WPSL_Admin();
			$wpsl_admin->delete_autoload_transient();
		}
		public function register_settings_section( $widget ) {}
		public function on_export( $element ) {}
	}

	// Instantiate the action class
	$church_action = new Elementor_Form_Kirchenfinder_Action();

	// Register the action with form widget
	\ElementorPro\Plugin::instance()->modules_manager->get_modules( 'forms' )->add_form_action( $church_action->get_name(), $church_action );
});


/*
 * Elias Add the elemementor form action for the church locator
 * END
*/



