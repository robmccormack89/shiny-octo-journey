<?php
/**
 * Timber theme class & other functions for Twig.
 *
 * @package Vigilant_Octo_Telegram
 */

// Define paths to Twig templates
Timber::$dirname = array(
  'views',
  'views/wp',
  'views/wp/archive',
  'views/wp/parts',
  'views/wp/parts/comments',
  'views/wp/parts/footer',
  'views/wp/parts/header',
  'views/wp/singular',
  'views/wp/templates',
  'views/woo',
  'views/woo/parts',
  'views/woo/parts/tease',
  'views/woo/parts/shop',
);

// set the $autoescape value
Timber::$autoescape = false;

// Define Vigilant_Octo_Telegram Child Class
class Vigilant_Octo_Telegram extends Timber\Site
{
  public function __construct()
  {
    // timber stuff
    add_filter('timber_context', array( $this, 'add_to_context' ));
    add_filter('get_twig', array( $this, 'add_to_twig' ));
    add_action('after_setup_theme', array( $this, 'theme_supports' ));
    add_action('wp_enqueue_scripts', array( $this, 'vigilant_octo_telegram_enqueue_assets'));
    add_action('widgets_init', array( $this, 'vigilant_octo_telegram_custom_uikit_widgets_init'));
    add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
    add_filter( 'timber/context', array( $this, 'add_to_context' ) );
    add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
    add_filter( 'query_vars', array( $this, 'vigilant_octo_telegram_gridlist_query_vars_filter'));
    add_action( 'init', array( $this, 'register_post_types' ) );
    add_action( 'init', array( $this, 'register_taxonomies' ) );
    add_action('init', array( $this, 'register_widget_areas' ));
    add_action('init', array( $this, 'register_navigation_menus' ));
    parent::__construct();
  }

  public function register_post_types()
  {
    $labels_one = array(
  		'name'                  => _x( 'Banner Slides', 'Post Type General Name', 'vigilant-octo-telegram' ),
  		'singular_name'         => _x( 'Banner Slide', 'Post Type Singular Name', 'vigilant-octo-telegram' ),
  		'menu_name'             => __( 'Home Banner Slides', 'vigilant-octo-telegram' ),
  		'name_admin_bar'        => __( 'Banner Slide', 'vigilant-octo-telegram' ),
  		'archives'              => __( 'Banner Slide Archives', 'vigilant-octo-telegram' ),
  		'attributes'            => __( 'Item Attributes', 'vigilant-octo-telegram' ),
  		'parent_item_colon'     => __( 'Parent Item:', 'vigilant-octo-telegram' ),
  		'all_items'             => __( 'All Slides', 'vigilant-octo-telegram' ),
  		'add_new_item'          => __( 'Add New Item', 'vigilant-octo-telegram' ),
  		'add_new'               => __( 'Add New', 'vigilant-octo-telegram' ),
  		'new_item'              => __( 'New Item', 'vigilant-octo-telegram' ),
  		'edit_item'             => __( 'Edit Item', 'vigilant-octo-telegram' ),
  		'update_item'           => __( 'Update Item', 'vigilant-octo-telegram' ),
  		'view_item'             => __( 'View Item', 'vigilant-octo-telegram' ),
  		'view_items'            => __( 'View Items', 'vigilant-octo-telegram' ),
  		'search_items'          => __( 'Search Item', 'vigilant-octo-telegram' ),
  		'not_found'             => __( 'Not found', 'vigilant-octo-telegram' ),
  		'not_found_in_trash'    => __( 'Not found in Trash', 'vigilant-octo-telegram' ),
  		'featured_image'        => __( 'Featured Image', 'vigilant-octo-telegram' ),
  		'set_featured_image'    => __( 'Set featured image', 'vigilant-octo-telegram' ),
  		'remove_featured_image' => __( 'Remove featured image', 'vigilant-octo-telegram' ),
  		'use_featured_image'    => __( 'Use as featured image', 'vigilant-octo-telegram' ),
  		'insert_into_item'      => __( 'Insert into item', 'vigilant-octo-telegram' ),
  		'uploaded_to_this_item' => __( 'Uploaded to this item', 'vigilant-octo-telegram' ),
  		'items_list'            => __( 'Items list', 'vigilant-octo-telegram' ),
  		'items_list_navigation' => __( 'Items list navigation', 'vigilant-octo-telegram' ),
  		'filter_items_list'     => __( 'Filter items list', 'vigilant-octo-telegram' ),
  	);
  	$args_one = array(
  		'label'                 => __( 'Banner Slide', 'vigilant-octo-telegram' ),
  		'description'           => __( 'Banner Slides for the Home Page Banner', 'vigilant-octo-telegram' ),
  		'labels'                => $labels_one,
  		'supports'              => array( 'title', 'editor', 'thumbnail' ),
  		'hierarchical'          => false,
  		'public'                => true,
  		'show_ui'               => true,
  		'show_in_menu'          => true,
  		'menu_position'         => 5,
  		'show_in_admin_bar'     => true,
  		'show_in_nav_menus'     => false,
  		'can_export'            => true,
  		'has_archive'           => false,
  		'exclude_from_search'   => true,
  		'publicly_queryable'    => false,
  		'capability_type'       => 'page',
  		'show_in_rest'          => false,
  	);
  	register_post_type( 'slide', $args_one );
  }

  public function register_taxonomies()
  {
  	$labels_series = array(
  		'name'                       => _x( 'Series/Models', 'Taxonomy General Name', 'vigilant-octo-telegram' ),
  		'singular_name'              => _x( 'Series/Model', 'Taxonomy Singular Name', 'vigilant-octo-telegram' ),
  		'menu_name'                  => __( 'Series/Models', 'vigilant-octo-telegram' ),
  		'all_items'                  => __( 'All Series/Models', 'vigilant-octo-telegram' ),
  		'parent_item'                => __( 'Parent (Series)', 'vigilant-octo-telegram' ),
  		'parent_item_colon'          => __( 'Parent (Series):', 'vigilant-octo-telegram' ),
  		'new_item_name'              => __( 'New Series/Model Name', 'vigilant-octo-telegram' ),
  		'add_new_item'               => __( 'Add New Series/Model', 'vigilant-octo-telegram' ),
  		'edit_item'                  => __( 'Edit Series/Model', 'vigilant-octo-telegram' ),
  		'update_item'                => __( 'Update Series/Model', 'vigilant-octo-telegram' ),
  		'view_item'                  => __( 'View Series/Model', 'vigilant-octo-telegram' ),
  		'separate_items_with_commas' => __( 'Separate items with commas', 'vigilant-octo-telegram' ),
  		'add_or_remove_items'        => __( 'Add or remove Series/Model', 'vigilant-octo-telegram' ),
  		'choose_from_most_used'      => __( 'Choose from the most used', 'vigilant-octo-telegram' ),
  		'popular_items'              => __( 'Popular Series/Models', 'vigilant-octo-telegram' ),
  		'search_items'               => __( 'Search Series/Models', 'vigilant-octo-telegram' ),
  		'not_found'                  => __( 'Not Found', 'vigilant-octo-telegram' ),
  		'no_terms'                   => __( 'No items', 'vigilant-octo-telegram' ),
  		'items_list'                 => __( 'Items list', 'vigilant-octo-telegram' ),
  		'items_list_navigation'      => __( 'Items list navigation', 'vigilant-octo-telegram' ),
  	);
  	$rewrite_series = array(
  		'slug'                       => 'product-series-model',
  		'with_front'                 => true,
  		'hierarchical'               => true,
  	);
  	$args_series = array(
  		'labels'                     => $labels_series,
  		'hierarchical'               => true,
  		'public'                     => true,
  		'show_ui'                    => true,
  		'show_admin_column'          => true,
  		'show_in_nav_menus'          => true,
  		'show_tagcloud'              => true,
  		'rewrite'                    => $rewrite_series,
  		'update_count_callback'      => 'count_product_series',
  		'show_in_rest'               => true,
      'update_count_callback' => '_update_post_term_count',
  	);
  	register_taxonomy( 'product_series', array( 'product' ), $args_series );
  }

  public function register_widget_areas()
  {
    // Register widget areas
    if (function_exists('register_sidebar')) {
      register_sidebar(array(
        'name' => esc_html__('Footer Left Area', 'vigilant-octo-telegram'),
        'id' => 'sidebar-footer-left',
        'description' => esc_html__('Main Footer Widget Area; works best with the current widget only.', 'vigilant-octo-telegram'),
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<span hidden>',
        'after_title' => '</span>'
      ));
    }
  }

  public function register_navigation_menus()
  {
    // This theme uses wp_nav_menu() in one locations.
    register_nav_menus(array(
      'categories' => __('Categories Menu', 'vigilant-octo-telegram'),
      'main_menu' => __('Main Menu', 'vigilant-octo-telegram'),
      'mobile_menu' => __('Mobile Menu', 'vigilant-octo-telegram'),
      'accessories_menu' => __('Accessories Menu', 'vigilant-octo-telegram'),
      'parts_menu' => __('Parts Menu', 'vigilant-octo-telegram'),
      'footer_nav_menu' => __('Footer Nav Menu', 'vigilant-octo-telegram'),
      'footer_customers_menu' => __('Footer Customers Menu', 'vigilant-octo-telegram'),
    ));
  }

  public function add_to_context( $context )
  {
    // global site context
    $context['site'] = $this;
    // general conditionals
    $context['is_user_logged_in'] = is_user_logged_in();
    $context['is_shop'] = is_shop();
    $context['is_category'] = is_category();
    $context['is_single_product'] = is_singular( 'product' );
    $context['is_product_category'] = is_product_category();
    $context['is_posts'] = is_blog();
    // get the wp logo
    $theme_logo_id = get_theme_mod( 'custom_logo' );
    $theme_logo_url = wp_get_attachment_image_url( $theme_logo_id , 'full' );
    $context['theme_logo_url'] = $theme_logo_url;
    // menu register & args
    $main_menu_args = array( 'depth' => 3 );
    $context['menu_cats'] = new \Timber\Menu( 'categories' );
    $context['has_menu_cats'] = has_nav_menu( 'categories' );
    $context['menu_main'] = new Timber\Menu( 'main_menu' );
    $context['has_menu_main'] = has_nav_menu( 'main_menu' );
    $context['menu_mobile'] = new Timber\Menu('mobile_menu');
    $context['has_menu_mobile'] = has_nav_menu( 'mobile_menu' );
    $context['footer_nav_menu'] = new Timber\Menu( 'footer_nav_menu' );
    $context['has_footer_nav_menu'] = has_nav_menu( 'footer_nav_menu' );
    $context['footer_customers_menu'] = new Timber\Menu( 'footer_customers_menu' );
    $context['has_footer_customers_menu'] = has_nav_menu( 'footer_customers_menu' );
    // sidebar areas
    $context['sidebar_footer_left'] = Timber::get_widgets('Footer Left Area');
    // woo my account endpoints
    $context['dashboard_endpoint'] = wc_get_account_endpoint_url( 'dashboard' );
    $context['address_endpoint'] = wc_get_account_endpoint_url( 'edit-address' );
    $context['edit_endpoint'] = wc_get_account_endpoint_url( 'edit-account' );
    $context['payment_endpoint'] = wc_get_account_endpoint_url( 'payment-methods' );
    $context['lost_endpoint'] = wc_get_account_endpoint_url( 'lost-password' );
    $context['orders_endpoint'] = wc_get_account_endpoint_url( 'orders' );
    $context['logout_endpoint'] = wc_get_account_endpoint_url( 'customer-logout' );
    //woo endpoints
    $context['shop_url'] = get_permalink(woocommerce_get_page_id('shop'));
    // the backend address
    $context['base_address'] = WC()->countries->get_base_address();
    $context['base_address_2'] = WC()->countries->get_base_address_2();
    $context['base_city'] = WC()->countries->get_base_city();
    $context['base_eircode'] = WC()->countries->get_base_postcode();
    $context['base_county'] = WC()->countries->get_base_state();
    $context['base_country'] = WC()->countries->get_base_country();
    // acf data globals
    $context['company_phone_number'] = get_field('company_phone_number', 'option');
    $context['facebook_link'] = get_field('facebook_link', 'option');
    $context['display_email'] = get_field('display_email', 'option');
    $context['above_footer_text'] = get_field('above_footer_text', 'option');
    
    // get the woo cart url
    global $woocommerce;
    $context['cart_url'] = $woocommerce->cart->get_cart_url();
    // return context
    return $context;    
  }
  
  public function theme_supports()
  {
    // theme supports
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('menus');
    add_theme_support('post-formats', array(
      'gallery',
      'quote',
      'video',
      'aside',
      'image',
      'link'
    ));
    add_theme_support('align-wide');
    add_theme_support('responsive-embeds');
    // Switch default core markup for search form, comment form, and comments to output valid HTML5.
    add_theme_support('html5', array(
      'search-form',
      'comment-form',
      'comment-list',
      'gallery',
      'caption'
    ));
    // Add support for core custom logo
    add_theme_support('custom-logo', array(
      'height' => 30,
      'width' => 261,
      'flex-width' => true,
      'flex-height' => true
    ));
    // woo supports
    add_theme_support( 'woocommerce' );
    add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
    // custom thumbnail sizes
    add_image_size('vigilant-octo-telegram-featured-image-archive', 800, 300, true);
    add_image_size('vigilant-octo-telegram-featured-image-single-post', 1200, 450, true);
    add_image_size('vigilant-octo-telegram-product-main-image', 1200, 700, true);
    add_image_size('vigilant-octo-telegram-cart-image', 80, 80, true);
    // stop the br tag madness in the content editor
    // remove_filter( 'the_content', 'wpautop' );
    // remove_filter( 'the_excerpt', 'wpautop' );
    load_theme_textdomain('vigilant-octo-telegram', get_template_directory() . '/languages');
  }
  
  // add grid-list url paramater key
  public function vigilant_octo_telegram_gridlist_query_vars_filter($vars)
  {
    $vars[] .= 'grid_list';
    return $vars;
  }
  
  public function vigilant_octo_telegram_enqueue_assets()
  {
    // theme base scripts
    wp_enqueue_script(
      'vigilant-octo-telegram',
      get_template_directory_uri() . '/assets/js/base.js',
      '',
      '',
      false
    );
    
    // enqueue wp jquery
    wp_enqueue_script( 'jquery' );
    
    // global (site wide) scripts; uses jquery
    wp_enqueue_script(
      'global',
      get_template_directory_uri() . '/assets/js/global.js',
      'jquery',
      '1.0.0',
      true
    );
    // localize theme scripts for ajax
    wp_localize_script(
      'global',
      'myAjax',
      array(
        'ajaxurl' => admin_url( 'admin-ajax.php')
      )
    );
    
    // theme base scripts
    wp_enqueue_script(
      'inf-scroll',
      get_template_directory_uri() . '/assets/js/lib/infinite-scroll.pkgd.min.js',
      '',
      '',
      false
    );
    
    // theme base scripts
    wp_enqueue_script(
      'theme-woo',
      get_template_directory_uri() . '/assets/js/woo/woo.js',
      '',
      '',
      true
    );
    
    wp_enqueue_style(
      'theme-google-fonts',
      'https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap',
      false
    );
    
    // font awesome
    wp_enqueue_style(
      'fontawesome-theme',
      get_template_directory_uri() . '/assets/css/lib/all.min.css'
    );
    // theme base css
    wp_enqueue_style(
      'vigilant-octo-telegram',
      get_template_directory_uri() . '/assets/css/base.css'
    );
    // theme stylesheet
    wp_enqueue_style(
      'vigilant-octo-telegram-styles', get_stylesheet_uri()
    );
    wp_enqueue_style(
      'vigilant-octo-telegram-woo',
      get_template_directory_uri() . '/assets/css/woo.css'
    );
  }
  
  public function vigilant_octo_telegram_custom_uikit_widgets_init()
  {
    register_widget("Vigilant_Octo_Telegram_Custom_UIKIT_Widget_Class");
  }

  public function add_to_twig($twig)
  {
    /* this is where you can add your own functions to twig */
    $twig->addExtension(new Twig_Extension_StringLoader());
    return $twig;
  }
  
}

new Vigilant_Octo_Telegram();
