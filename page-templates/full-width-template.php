<?php
/**
 * Template Name: Full Width Template
 *
 * @package Shiny_Octo_Journey
 */

$context = Timber::context();
$post = Timber::query_post();
$context['post'] = $post;
Timber::render(  'full.twig' , $context );