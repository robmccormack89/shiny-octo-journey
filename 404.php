<?php
/**
* The 404 wrror template
*
* @package Shiny_Octo_Journey
*/

$context = Timber::context();

$context['title'] =  'Page not found';

Timber::render( '404.twig', $context );