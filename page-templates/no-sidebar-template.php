<?php
/**
 * Template Name: No Sidebar Template
 *
 * @package Starter_Theme
 */

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;
Timber::render(  'no-sidebar-template.twig' , $context );