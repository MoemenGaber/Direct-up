<?php


/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

/**
 * If you are installing Timber as a Composer dependency in your theme, you'll need this block
 * to load your dependencies and initialize Timber. If you are using Timber via the WordPress.org
 * plug-in, you can safely delete this block.
 */


use Twig\TwigFunction;
add_filter('show_admin_bar', '__return_false');

$composer_autoload = __DIR__ . '/vendor/autoload.php';
if ( file_exists( $composer_autoload ) ) {
    require_once $composer_autoload;
    $timber = new Timber\Timber();
}

/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */
if ( ! class_exists( 'Timber' ) ) {

    add_action(
        'admin_notices',
        function() {
            echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
        }
    );

    add_filter(
        'template_include',
        function( $template ) {
            return get_stylesheet_directory() . '/static/no-timber.html';
        }
    );
    return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array( 'templates', 'views' );

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;


/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class StarterSite extends Timber\Site {
    /** Add timber support. */
    public function __construct() {
        add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
        add_filter( 'timber/context', array( $this, 'add_to_context' ) );
        add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
        add_action( 'init', array( $this, 'register_post_types' ) );
        add_action( 'init', array( $this, 'register_taxonomies' ) );
        parent::__construct();
    }
    /** This is where you can register custom post types. */
    public function register_post_types() {

    }
    /** This is where you can register custom taxonomies. */
    public function register_taxonomies() {

    }

    /** This is where you add some context
     *
     * @param string $context context['this'] Being the Twig's {{ this }}.
     */
    public function add_to_context( $context ) {
        $context['foo']   = 'bar';
        $context['stuff'] = 'I am a value set in your functions.php file';
        $context['notes'] = 'These values are available everytime you call Timber::context();';
        $context['menu']  = new Timber\Menu();
        $context['site']  = $this;
        return $context;
    }

    public function theme_supports() {
        // Add default posts and comments RSS feed links to head.
        add_theme_support( 'automatic-feed-links' );

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support( 'title-tag' );

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support( 'post-thumbnails' );

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support(
            'html5',
            array(
                'comment-form',
                'comment-list',
                'gallery',
                'caption',
            )
        );

        /*
         * Enable support for Post Formats.
         *
         * See: https://codex.wordpress.org/Post_Formats
         */
        add_theme_support(
            'post-formats',
            array(
                'aside',
                'image',
                'video',
                'quote',
                'link',
                'gallery',
                'audio',
            )
        );

        add_theme_support( 'menus' );
    }

    /** This Would return 'foo bar!'.
     *
     * @param string $text being 'foo', then returned 'foo bar!'.
     */
    public function myfoo( $text ) {
        $text .= ' bar!';
        return $text;
    }

    public function get_term_icon($term_id){
        $term_icon= get_field('icon','term_'.$term_id);

        return $term_icon;
    }
    public function get_favorite_btn($post_id,$user_id){
        $user_favorite= get_user_meta($user_id,'favorite_ads',true);
        if(!empty($user_favorite)){
        if(!in_array($post_id,$user_favorite)){
            ?>
                <a class="add_to_wish g_br_btn ml_1" href="#" data-ad-id="<?php echo $post_id; ?>" data-user-id="<?php echo $user_id; ?>">

                <svg class="me-2 vm" xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 0 24 24" width="20px" fill="#B7BCCA"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M16.5 3c-1.74 0-3.41.81-4.5 2.09C10.91 3.81 9.24 3 7.5 3 4.42 3 2 5.42 2 8.5c0 3.78 3.4 6.86 8.55 11.54L12 21.35l1.45-1.32C18.6 15.36 22 12.28 22 8.5 22 5.42 19.58 3 16.5 3zm-4.4 15.55l-.1.1-.1-.1C7.14 14.24 4 11.39 4 8.5 4 6.5 5.5 5 7.5 5c1.54 0 3.04.99 3.57 2.36h1.87C13.46 5.99 14.96 5 16.5 5c2 0 3.5 1.5 3.5 3.5 0 2.89-3.14 5.74-7.9 10.05z"/></svg><small>أضف إلى المفضلة</small>
            </a>
    <?php
        }else{
            ?>
            <a class="add_to_wish added g_br_btn ml_1" href="#" data-ad-id="<?php echo $post_id; ?>" data-user-id="<?php echo $user_id; ?>">

                <svg class="me-2 vm" xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 0 24 24" width="20px" fill="#B7BCCA"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M16.5 3c-1.74 0-3.41.81-4.5 2.09C10.91 3.81 9.24 3 7.5 3 4.42 3 2 5.42 2 8.5c0 3.78 3.4 6.86 8.55 11.54L12 21.35l1.45-1.32C18.6 15.36 22 12.28 22 8.5 22 5.42 19.58 3 16.5 3zm-4.4 15.55l-.1.1-.1-.1C7.14 14.24 4 11.39 4 8.5 4 6.5 5.5 5 7.5 5c1.54 0 3.04.99 3.57 2.36h1.87C13.46 5.99 14.96 5 16.5 5c2 0 3.5 1.5 3.5 3.5 0 2.89-3.14 5.74-7.9 10.05z"/></svg><small>إزالة من المفضلة</small>
            </a>
            <?php
        }
    }else{
            ?>
            <a class="add_to_wish g_br_btn ml_1" href="#" data-ad-id="<?php echo $post_id; ?>" data-user-id="<?php echo $user_id; ?>">

                <svg class="me-2 vm" xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 0 24 24" width="20px" fill="#B7BCCA"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M16.5 3c-1.74 0-3.41.81-4.5 2.09C10.91 3.81 9.24 3 7.5 3 4.42 3 2 5.42 2 8.5c0 3.78 3.4 6.86 8.55 11.54L12 21.35l1.45-1.32C18.6 15.36 22 12.28 22 8.5 22 5.42 19.58 3 16.5 3zm-4.4 15.55l-.1.1-.1-.1C7.14 14.24 4 11.39 4 8.5 4 6.5 5.5 5 7.5 5c1.54 0 3.04.99 3.57 2.36h1.87C13.46 5.99 14.96 5 16.5 5c2 0 3.5 1.5 3.5 3.5 0 2.89-3.14 5.74-7.9 10.05z"/></svg><small>أضف إلى المفضلة</small>
            </a>
            <?php
        }
    }
    public function get_user_profile_pic($user_id){
       if(!empty(wp_get_attachment_image_url(get_user_meta($user_id,'user_pic',true)))){
           $image =wp_get_attachment_image_url(get_user_meta($user_id,'user_pic',true));
       }else{
           $image= get_template_directory_uri().'/assets/img/user.svg';
       }
       return $image;

    }

    public function get_ad_thumb($post_id){
            return get_the_post_thumbnail_url($post_id);
    }

    public function get_category_id($category){
        $cat= get_term_by('slug', $category, 'ad_categories');
        return $cat->term_id;
    }
    public function get_category_posts($cat_id){
        $args= array(
            'post_type'=>'services',
            'posts_per_page'=>-1,
            'tax_query' => array(
                array(
                    'taxonomy' => 'services_categories',
                    'field' => 'term_id',
                    'terms' => $cat_id,
                )
            )
        );
        return \Timber\Timber::get_posts($args);

    }
    public function get_related_ads($post_id){
        $terms = get_the_terms($post_id,'ad_categories');
        $current_cat= $terms[0]->term_id;
        $args= array(
            'post_type'=>'ads',
            'posts_per_page'=>3,
            'post__not_in' =>array((int)$post_id),
            'tax_query' => array(
                array(
                    'taxonomy' => 'ad_categories',
                    'field' => 'term_id',
                    'terms' => (int)$current_cat,
                )
            )
        );
        return \Timber\Timber::get_posts($args);
    }

     public function print_all_subcategories(){
        $ad_categories = get_terms( 'ad_categories',array('hide_empty'=>0,'parent'=>0));
        foreach ($ad_categories as $ad_category){
            $termchildren = get_terms( 'ad_categories',array('hide_empty'=>0,'parent'=>$ad_category->term_id));
            echo '<ul class="sub_cat_list" id="sub-categories-list-'.$ad_category->term_id.'" style="display:none">';
            foreach ($termchildren as $subcategory){
                echo '<li class="border-bottom ptb_1" data-sub-cat-id="'.$subcategory->term_id.'" data="'.$subcategory->name.'">'.$subcategory->name.'</li>';
            }
            echo '</ul>';
        }

    }

    public function get_the_ad_date($post_id){
        $from=get_the_time('U',$post_id);
        $to=current_time('U');
       $date= human_time_diff($from, $to);
       $english_arr=array('weeks','month','hours','hour');
       $arabic_arr=array('اسابيع','شهر','ساعة','ساعة');
       $new_date=str_replace($english_arr,$arabic_arr,$date);
        echo $new_date;
    }


    /** This is where you can add your own functions to twig.
     *
     * @param string $twig get extension.
     */
    public function add_to_twig( $twig ) {
        $twig->addExtension( new Twig\Extension\StringLoaderExtension() );
        $twig->addFilter( new Twig\TwigFilter( 'myfoo', array( $this, 'myfoo' ) ) );
        $twig->addFilter( new Twig\TwigFilter( 'get_term_icon', array( $this, 'get_term_icon' ) ) );
        $twig->addFilter( new Twig\TwigFilter( 'get_the_ad_date', array( $this, 'get_the_ad_date' ) ) );
        $twig->addFilter( new Twig\TwigFilter( 'get_category_posts', array( $this, 'get_category_posts' ) ) );
        $twig->addFilter( new Twig\TwigFilter( 'get_favorite_btn', array( $this, 'get_favorite_btn' ) ) );
        $twig->addFilter( new Twig\TwigFilter( 'get_user_profile_pic', array( $this, 'get_user_profile_pic' ) ) );
        $twig->addFilter( new Twig\TwigFilter( 'get_category_id', array( $this, 'get_category_id' ) ) );
        $twig->addFilter( new Twig\TwigFilter( 'get_ad_thumb', array( $this, 'get_ad_thumb' ) ) );
        $twig->addFilter( new Twig\TwigFilter( 'get_related_ads', array( $this, 'get_related_ads' ) ) );
        $twig->addFilter( new Twig\TwigFilter( 'price_filter', array( $this, 'price_filter' ) ) );
        $twig->addFunction( new TwigFunction('print_all_subcategories', array( $this, 'print_all_subcategories' )
        ) );
        return $twig;
    }
    

}

new StarterSite();
