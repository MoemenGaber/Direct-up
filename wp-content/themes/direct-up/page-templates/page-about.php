<?php
/* Template Name: About us */

$context = Timber::context();
$context['page_title']=get_the_title();

$context['about_it_arm']=get_field('about_it_arm');
$context['about_directup']=get_field('about_directup');


$templates = array('views/page-templates/page-about.twig');

Timber::render($templates, $context);