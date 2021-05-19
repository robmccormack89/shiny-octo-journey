<?php
/**
* The search results template
*
* @package Shiny_Octo_Journey
*/

$context = Timber::context();
$context['posts'] = new Timber\PostQuery();

$context['title'] = 'Search results for ' . get_search_query();

$context['pagination'] = Timber::get_pagination();
$context['paged'] = $paged;

Timber::render( array( 'search.twig', 'archive.twig', 'index.twig' ), $context );