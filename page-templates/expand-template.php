<?php
/**
 * Template Name: Expanded Width Template
 *
 * @package Shiny_Octo_Journey
 */

$context = Timber::context();
$post = Timber::query_post();
$context['post'] = $post;
Timber::render(  'expand.twig' , $context );