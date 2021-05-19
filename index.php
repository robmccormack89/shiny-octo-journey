<?php
/**
* The main index template file
* Will function as an archive when no other templates apply (which shouldn't happen anyways)
*
* @package Shiny_Octo_Journey
*/

$context = Timber::context();
$context['posts'] = new Timber\PostQuery();

$context['pagination'] = Timber::get_pagination();
$context['paged'] = $paged;

Timber::render( array( 'index.twig' ), $context );