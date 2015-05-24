<?php
/*
Plugin Name: Post Type for Article
Plugin URI: http://github.com/robynover
Description: Defines a post type for articles on chrgj.org
Version: 1.0
Author: Robyn
Author URI: http://robynoverstreet.com
License: GPL2
*/
//Constants
define('ARTICLE_URLPATH', trailingslashit( WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) ) );

//add_action('admin_print_scripts', 'article_javascript');
add_action('init', 'article_register');
// Add custom taxonomy
add_action( 'init', 'article_create_taxonomies',2);

//add support for tags
if( ! function_exists('articles_register_taxonomy') ){
    function articles_register_taxonomy()
    {
        register_taxonomy_for_object_type('post_tag', 'chrgj_article');
    }
    add_action('admin_init', 'articles_register_taxonomy');
}
 
function article_register() {
 
	$labels = array(
		'name' => _x('Articles', 'post type general name'),
		'singular_name' => _x('Article', 'post type singular name'),
		'add_new' => _x('Add New', 'Article'),
		'add_new_item' => __('Add New Article'),
		'edit_item' => __('Edit Article'),
		'new_item' => __('New Article'),
		'view_item' => __('View Article'),
		'search_items' => __('Search Articles'),
		'not_found' =>  __('Nothing found'),
		'not_found_in_trash' => __('Nothing found in Trash'),
		'parent_item_colon' => ''
	);
 
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'rewrite' => array( 'slug' => 'article'),
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => 5,
		'show_in_menu' => true,
		'has_archive' => true,
		'supports' => array('title','editor','thumbnail'),
		'taxonomies'=>array('article_type','category')
	  ); 
 
	register_post_type( 'chrgj_article' , $args );
}
/**********************************
3 = Metaboxes
**********************************/
add_action("admin_init", "chrgj_articles_mb_create");
add_action('save_post', 'chrgj_articles_mb_save');

function chrgj_articles_mb_create(){
	add_meta_box( 'chrgj_articles_mb_url', 'URL', 'article_url', 'chrgj_article', 'normal', 'high' );
	add_meta_box( 'chrgj_articles_mb_source', 'Source', 'article_source', 'chrgj_article', 'normal', 'high' );
	add_meta_box('chrgj_articles_mb_author', 'Author','article_author','chrgj_article','normal','high');
	add_meta_box('chrgj_articles_mb_pubdate', 'Publication Date','article_pubdate','chrgj_article','normal','high');
}

function chrgj_articles_mb_save(){
	global $post;
	update_post_meta($post->ID, 'article_url', $_POST['article_url']);
	//update_post_meta($post->ID, 'journals_description', $_POST['journals_description']);
	update_post_meta($post->ID, 'article_source', $_POST['article_source']);
	update_post_meta($post->ID, 'article_author', $_POST['article_author']);
	update_post_meta($post->ID, 'article_pubdate', $_POST['article_pubdate']);
}

/****************
URL
****************/
function article_url(){
	global $post;
	$custom = get_post_custom($post->ID);
	$article_url = $custom["article_url"][0];
	?>
	<input type="text" name="article_url" id="article_url" class="text" size="64" tabindex="1" value="<?php echo $article_url; ?>" />
	<p>Provide the link to the article.</p>
<?php
}
/****************
Source
****************/
function article_source(){
	global $post;
	$custom = get_post_custom($post->ID);
	$article_source = $custom["article_source"][0];
	?>
	<input type="text" name="article_source" id="article_source" class="text" size="64" value="<?php echo $article_source; ?>" />
	<p>The source of the article.</p>
<?php
}
/****************
Author
****************/
function article_author(){
	global $post;
	$custom = get_post_custom($post->ID);
	$article_author = $custom["article_author"][0];
	?>
	
	<input type="text" name="article_author" id="article_author" class="text" size="64" value="<?php echo $article_author; ?>" />
	<p>The author of the article.</p>
<?php
}

/****************
Publication Date
****************/
function article_pubdate(){
	global $post;
	$custom = get_post_custom($post->ID);
	$article_pubdate = $custom["article_pubdate"][0];
	?>
	<input type="text" name="article_pubdate" id="article_pubdate" class="regular-date datepicker" size="64" value="<?php echo $article_pubdate; ?>" />
	<p>The publication date of the article, in the format YYYY-mm-dd. For example 2012-03-28</p>
<?php
}

/****************
javascript
****************/
/*function article_javascript() {

	global $post;
	
	if(isset($post->post_type)) {
	
		if($post->post_type == 'chrgj_article') {
		
			wp_enqueue_script('jquery');
			//wp_enqueue_script( 'suggest' );
			
			wp_enqueue_script('article-jquery-ui', ARTICLE_URLPATH.'js/jquery-ui-1.7.3.custom.min.js', array('jquery'));
			//wp_enqueue_script('article-jquery-ui-datepicker', ARTICLE_URLPATH.'js/datepicker/jquery.ui.datepicker-sv.js', array('jquery'));
			
		}
	}
}
//css
add_action('admin_print_styles', 'article_css');
function article_css() {

	global $post;
	
	if(isset($post->post_type)) {
	
		if($post->post_type == 'chrgj_article') {
		
			wp_enqueue_style( 'jquery-ui-css', ARTICLE_URLPATH . 'css/datepicker.css');
			//wp_enqueue_style( 'event-css', EVENT_URLPATH.'css/event-post-type-admin.css', array() );
			//wp_enqueue_style( 'autosuggest-css', EVENT_URLPATH.'css/autosuggest.css', array() );
			
		}
	}
}*/

/****************
Custom Taxonomy: Article types
****************/
function article_create_taxonomies() {
	$labels = array(
	    'name' => _x( 'Article Types', 'taxonomy general name' ),
	    'singular_name' => _x( 'Article Type', 'taxonomy singular name' ),
	    'search_items' =>  __( 'Search Article Types' ),
	    'all_items' => __( 'All Article Types' ),
	    'edit_item' => __( 'Edit Article Type' ), 
	    'update_item' => __( 'Update Article Type' ),
	    'add_new_item' => __( 'Add New Article Type' ),
	    'new_item_name' => __( 'New Article Type Name' ),
	    'menu_name' => __( 'Article Type' ),
	  ); 	
	
	// Article type
    register_taxonomy('article_type',array('chrgj-article'),array(
        'hierarchical' => true,
        'labels' => $labels,
        'singular_name' => 'Article Type',
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'article-type' ),
		'capabilities' => array('manage_categories'.'edit_posts')
    ));
}

//rewrite rules to manage pagination
//add_action('generate_rewrite_rules', 'art_datearchives_rewrite_rules');
//add_action('generate_rewrite_rules', 'art_generate_date_archives');
/*
function art_datearchives_rewrite_rules($wp_rewrite) {
	$rules = art_generate_date_archives('chrgj_article', $wp_rewrite);
	$wp_rewrite->rules = $rules + $wp_rewrite->rules;
	return $wp_rewrite;
}
function art_generate_date_archives($cpt, $wp_rewrite) {
	$rules = array();
	//$query = 'index.php?post_type=chrgj_article&article_type=in-the-news';
	$query = 'index.php?';
	//$rule = $slug_archive.'/'.$data['rule'];
	$rules["page/([0-9]{1,})/?$"] = "&paged=".$wp_rewrite->preg_index(1);

	
	return $rules;

}*/