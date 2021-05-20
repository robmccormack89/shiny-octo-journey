<?php
/**
 * The template for making woocommerce work with timber/twig. sets the templates & context for woo's archive & singular views
 *
 * @package Shiny_Octo_Journey
 */

// make sure timber is activated first
if ( ! class_exists( 'Timber' ) ) {
  echo 'Timber not activated. Make sure you activate the plugin in <a href="/wp-admin/plugins.php#timber">/wp-admin/plugins.php</a>';
  return;
}

// get the main context
$context = Timber::context();

if ( is_singular( 'product' ) ) {
  
  $context['post'] = Timber::get_post();
  $product = wc_get_product( $context['post']->ID );
  $context['product'] = $product;
  
  // Get related products
  $related_limit = 12;
  $related_ids = wc_get_related_products( $context['post']->id, $related_limit );
  $context['related_products_title'] = apply_filters( 'woocommerce_product_related_products_heading', __( 'Related products', 'woocommerce' ) );
  $context['related_products'] = Timber::get_posts( $related_ids );
  
  // Get upsells
  $new_upsell_ids = $context['post']->_upsell_ids;
  
  $context['up_sells_title'] = apply_filters( 'woocommerce_product_upsells_products_heading', __( 'You may also like&hellip;', 'woocommerce' ) );
  
  if ($new_upsell_ids) {
    $context['up_sells'] = Timber::get_posts( $new_upsell_ids );
  } else {
    $context['up_sells'] = '';
  }
  
  wp_reset_postdata();
  
  Timber::render( 'single-product.twig', $context );
  
} else { // is not singular, then it must be an archive!
  
  // get the main posts object via the standard wp archive query & assign as variable 'products'
  $posts = new Timber\PostQuery();
  $context['products'] = $posts;
  
  // define our archive & tease templates as arrays, which can be unshifted later depending on context
  $templates = array('shop.twig');
  $tease_template = array('tease-product.twig');
  $tease_term_template = array('tease-term.twig');
  
  // gets the woocommerce columns per row setting
  $context['products_grid_columns'] = wc_get_loop_prop('columns');
  
  $context['pagination'] = Timber::get_pagination();
  $context['paged'] = $paged;
  
  // if is list-view
  if (get_query_var('grid_list') == 'list-view') {
    // reset the woo archive columns setting
    $context['products_grid_columns'] = '1';
    // unshit the tease template variable with the new list tease template
  	array_unshift( $tease_template, 'tease-product-list.twig' );
    // then Restore the context and loop back to the main query loop.
    wp_reset_postdata();
  };
  
  // if is any new taxonomy, see is_tax wp dev handbook for details
  if (is_tax('')) {
    // get queried object stuff
    $queried_object = get_queried_object();
    $term_id = $queried_object->term_id;
    $context['term_slug'] = $queried_object->slug;
    $context['term_id'] = $term_id;
    // set the archive title
    $context['title'] = single_term_title( '', false );
    // get term thumbs
    // get product category thumbnail
    $thumbnail_id = get_term_meta( $term_id, 'thumbnail_id', true );
    $archive_header_bg = wp_get_attachment_url( $thumbnail_id );
    if (!empty($archive_header_bg)) {
      $context['archive_header_bg'] = $archive_header_bg;
    } else {
      $context['archive_header_bg'] = get_template_directory_uri() . '/assets/images/field.jpg';
    };
    // then Restore the context and loop back to the main query loop.
    wp_reset_postdata();
  };
  
  // if is main shop archive page
  if (is_shop()) {
    
    // set shop page archive title
    $context['title'] = get_bloginfo( 'name' );
    // get shop main thumbnail
    $context['archive_header_bg'] = get_template_directory_uri() . '/assets/images/field.jpg';
    
    // then Restore the context and loop back to the main query loop.
    wp_reset_postdata();
  };
  
  $context['tease_template'] = $tease_template; 
  $context['tease_term_template'] = $tease_term_template; 
  
  Timber::render( $templates, $context );
}