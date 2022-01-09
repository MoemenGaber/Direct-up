<?php
/**
 * The template for displaying Author Archive pages
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

global $wp_query;

$context          = Timber::context();
$author_id= get_the_author_meta('ID');

$args = array(
    'author'        =>  $author_id,
    'post_status' =>'publish',
    'orderby'       =>  'post_date',
    'order'         =>  'ASC',
    'posts_per_page' => -1,
    'post_type' => 'ads',
);

$context['author_published_ads']= Timber::query_posts($args);


$templates=array('views/author.twig');
Timber::render( $templates, $context );
