<?php
/**
 * Search results page
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

$templates = array( 'search.twig');

$context          = Timber::context();
$context['title'] = get_search_query();
$posts = new Timber\PostQuery();
$ads_count=$posts->found_posts;
$context['posts'] = $posts;
$context['ads_count'] = $ads_count;
Timber::render( $templates, $context );
