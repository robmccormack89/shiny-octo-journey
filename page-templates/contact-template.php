<?php
/**
 * Template Name: Contact Page Template
 *
 * @package Shiny_Octo_Journey
 */

$context = Timber::context();
$post = Timber::query_post();
$context['post'] = $post;

// acf fields
$context['contact_forms'] = get_field('contact_form_select', 'option');

$context['hmm'] = 'Information';


Timber::render(  'contact.twig' , $context );