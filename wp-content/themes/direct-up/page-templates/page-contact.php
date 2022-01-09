<?php
/* Template Name: Contact us */

use Timber\Timber;

$context          = Timber::context();
$context['page_title']=get_the_title();
$templates        = array( 'views/page-templates/page-contact.twig' );

Timber::render( $templates, $context );