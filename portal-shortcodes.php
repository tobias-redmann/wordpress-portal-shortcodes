<?php
/*
Plugin Name: Portal Shortcodes
Plugin URI: http://www.tricd.de
Version:  0.1
Description: Provides some Shortcode for Portal like functions (Sitemaps, Indexes, Internal Links...)
Author: Tobias Redmann
Author URI: http://www.tricd.de
*/

add_shortcode('tag_index',        'portal_tag_index');
add_shortcode('category_sitemap', 'portal_category_sitemap');
add_sortcode('a'				, 'portal_get_internal_url');


function portal_get_internal_url($attr, $content) {
	
	extract(shortcode_atts(array(
                               'id' => false 
                               ), 
                         $attr));
                         
    if ($id === false) {
    	
    	return $content;
    	
    } else {
    	
    	return '<a href="'. get_permalink($id) .'">'. $content .'</a>';
    	
    }
	
}


function portal_category_sitemap($attr, $content) {
  
  $categories=  get_categories(); 
  
  foreach ($categories as $cat) {
  
    $cat_link = get_category_link( $cat->cat_ID );
    
    $o = $o. '<h3><a href="'.$cat_link.'">'. $cat->name .'</a></h3>';
    
    $ps = array();
    
    $ps = query_posts("cat=". $cat->cat_ID ."&posts_per_page=-1");
    
    if (count($ps) > 0) $o = $o.'<ul>';
    
    foreach($ps as $p) {
      
      $post_link = get_permalink( $p->ID );
      
      $o = $o . '<li><a href="'.$post_link.'">'.$p->post_title.'</a></li>';
      
    }
    
    if (count($ps) > 0) $o = $o.'</ul>';

    wp_reset_query();
  
  }

  return $o;

}


function portal_tag_index($attr, $content) {
  
  // FIXME: Only show tag heading when contains posts
  
  $o = '';
  
  $tags = get_terms("post_tag");
    
  foreach($tags as $tag) {
  
    $tag_link = get_tag_link($tag->term_id);
    
    $o = $o. '<h3><a href="'.$tag_link.'">'. $tag->name .'</a></h3>';
    
    $ps = array();
    
    $ps = query_posts("tag=". $tag->slug);
    
    if (count($ps) > 0) $o = $o.'<ul>';
    
    foreach($ps as $p) {
      
      $post_link = get_permalink( $p->ID );
      
      $o = $o . '<li><a href="'.$post_link.'">'.$p->post_title.'</a></li>';
      
    }
    
    if (count($ps) > 0) $o = $o.'</ul>';
    
    wp_reset_query();
    
  }
  
  return $o;
}

?>
