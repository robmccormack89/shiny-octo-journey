<?php
/**
 * Timber theme class & other functions for Twig.
 *
 * @package Shiny_Octo_Journey
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

// Define Shiny_Octo_Journey Child Class
class Shiny_Octo_Journey extends Timber\Site
{
  public function __construct()
  {
    // timber stuff
    add_filter('timber_context', array( $this, 'add_to_context' ));
    add_filter('get_twig', array( $this, 'add_to_twig' ));
    add_action('after_setup_theme', array( $this, 'theme_supports' ));
    add_action('wp_enqueue_scripts', array( $this, 'shiny_octo_journey_enqueue_assets'));
    add_action('widgets_init', array( $this, 'shiny_octo_journey_custom_uikit_widgets_init'));
    add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
    add_filter( 'timber/context', array( $this, 'add_to_context' ) );
    add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
    add_filter( 'query_vars', array( $this, 'shiny_octo_journey_gridlist_query_vars_filter'));
    add_action( 'init', array( $this, 'register_post_types' ) );
    add_action( 'init', array( $this, 'register_taxonomies' ) );
    add_action('init', array( $this, 'register_widget_areas' ));
    add_action('init', array( $this, 'register_navigation_menus' ));
    parent::__construct();
  }
  
  // this makes custom taxonomy (status) work with archive.php->archive.twig templates with pre_get_post filter added to class construct above
  public function add_custom_types_to_tax($query)
  {
    if( is_category() || is_tax('tractor_type') || is_tag() && empty( $query->query_vars['suppress_filters'] ) ) {
      // Get all your post types
      $post_types = get_post_types();
      $query->set( 'post_type', $post_types );
      return $query;
    }
  }

  public function register_post_types()
  {
    $labels_one = array(
  		'name'                  => _x( 'Banner Slides', 'Post Type General Name', 'shiny-octo-journey' ),
  		'singular_name'         => _x( 'Banner Slide', 'Post Type Singular Name', 'shiny-octo-journey' ),
  		'menu_name'             => __( 'Home Banner Slides', 'shiny-octo-journey' ),
  		'name_admin_bar'        => __( 'Banner Slide', 'shiny-octo-journey' ),
  		'archives'              => __( 'Banner Slide Archives', 'shiny-octo-journey' ),
  		'attributes'            => __( 'Item Attributes', 'shiny-octo-journey' ),
  		'parent_item_colon'     => __( 'Parent Item:', 'shiny-octo-journey' ),
  		'all_items'             => __( 'All Slides', 'shiny-octo-journey' ),
  		'add_new_item'          => __( 'Add New Item', 'shiny-octo-journey' ),
  		'add_new'               => __( 'Add New', 'shiny-octo-journey' ),
  		'new_item'              => __( 'New Item', 'shiny-octo-journey' ),
  		'edit_item'             => __( 'Edit Item', 'shiny-octo-journey' ),
  		'update_item'           => __( 'Update Item', 'shiny-octo-journey' ),
  		'view_item'             => __( 'View Item', 'shiny-octo-journey' ),
  		'view_items'            => __( 'View Items', 'shiny-octo-journey' ),
  		'search_items'          => __( 'Search Item', 'shiny-octo-journey' ),
  		'not_found'             => __( 'Not found', 'shiny-octo-journey' ),
  		'not_found_in_trash'    => __( 'Not found in Trash', 'shiny-octo-journey' ),
  		'featured_image'        => __( 'Featured Image', 'shiny-octo-journey' ),
  		'set_featured_image'    => __( 'Set featured image', 'shiny-octo-journey' ),
  		'remove_featured_image' => __( 'Remove featured image', 'shiny-octo-journey' ),
  		'use_featured_image'    => __( 'Use as featured image', 'shiny-octo-journey' ),
  		'insert_into_item'      => __( 'Insert into item', 'shiny-octo-journey' ),
  		'uploaded_to_this_item' => __( 'Uploaded to this item', 'shiny-octo-journey' ),
  		'items_list'            => __( 'Items list', 'shiny-octo-journey' ),
  		'items_list_navigation' => __( 'Items list navigation', 'shiny-octo-journey' ),
  		'filter_items_list'     => __( 'Filter items list', 'shiny-octo-journey' ),
  	);
  	$args_one = array(
  		'label'                 => __( 'Banner Slide', 'shiny-octo-journey' ),
  		'description'           => __( 'Banner Slides for the Home Page Banner', 'shiny-octo-journey' ),
  		'labels'                => $labels_one,
  		'supports'              => array( 'title', 'thumbnail' ),
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
    // 
  }

  public function register_widget_areas()
  {
    // Register widget areas
    if (function_exists('register_sidebar')) {
      register_sidebar(array(
        'name' => esc_html__('Footer Left Area', 'shiny-octo-journey'),
        'id' => 'sidebar-footer-left',
        'description' => esc_html__('Main Footer Widget Area; works best with the current widget only.', 'shiny-octo-journey'),
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
      'categories' => __('Categories Menu', 'shiny-octo-journey'),
      'main_menu' => __('Main Menu', 'shiny-octo-journey'),
      'mobile_menu' => __('Mobile Menu', 'shiny-octo-journey'),
      'accessories_menu' => __('Accessories Menu', 'shiny-octo-journey'),
      'parts_menu' => __('Parts Menu', 'shiny-octo-journey'),
      'footer_nav_menu' => __('Footer Nav Menu', 'shiny-octo-journey'),
      'footer_customers_menu' => __('Footer Customers Menu', 'shiny-octo-journey'),
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
    $context['contact_page_link'] = get_field('contact_page_link', 'option');
    
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
    add_image_size('shiny-octo-journey-featured-image-archive', 800, 300, true);
    add_image_size('shiny-octo-journey-featured-image-single-post', 1200, 450, true);
    add_image_size('shiny-octo-journey-product-main-image', 1200, 700, true);
    add_image_size('shiny-octo-journey-cart-image', 80, 80, true);
    // stop the br tag madness in the content editor
    // remove_filter( 'the_content', 'wpautop' );
    // remove_filter( 'the_excerpt', 'wpautop' );
    load_theme_textdomain('shiny-octo-journey', get_template_directory() . '/languages');
  }
  
  // add grid-list url paramater key
  public function shiny_octo_journey_gridlist_query_vars_filter($vars)
  {
    $vars[] .= 'grid_list';
    return $vars;
  }
  
  public function shiny_octo_journey_enqueue_assets()
  {
    // theme base scripts
    wp_enqueue_script(
      'shiny-octo-journey',
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
      'shiny-octo-journey',
      get_template_directory_uri() . '/assets/css/base.css'
    );
    // theme stylesheet
    wp_enqueue_style(
      'shiny-octo-journey-styles', get_stylesheet_uri()
    );
    wp_enqueue_style(
      'shiny-octo-journey-woo',
      get_template_directory_uri() . '/assets/css/woo.css'
    );
  }
  
  public function shiny_octo_journey_custom_uikit_widgets_init()
  {
    register_widget("Shiny_Octo_Journey_Custom_UIKIT_Widget_Class");
  }

  public function add_to_twig($twig)
  {
    /* this is where you can add your own functions to twig */
    $twig->addExtension(new Twig_Extension_StringLoader());
    return $twig;
  }
  
}

new Shiny_Octo_Journey();
