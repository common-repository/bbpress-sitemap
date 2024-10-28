<?php
/*
Plugin Name: bbPress Sitemap
Plugin URI: http://www.matthewstevenkelly.com/
Description: A bbPress sitemap. Good for people and search engines.
Author: Matthew Steven Kelly
Version: 1.0
Author URI: http://www.matthewstevenkelly.com
*/

$bbpress_sitemap = new bbpress_sitemap();

add_shortcode("bbpress_sitemap",  array($bbpress_sitemap, 'shortcodes'));

class bbpress_sitemap
{	
	private function recursive_bbpress_loop($forum_id)
	{
		$forum_output = '';
	 	$forums = get_posts(array( 'post_parent' => $forum_id, 'post_type' => 'forum', 'order' => 'ASC', 'order_by' => 'menu_order' ));
		if ($forums) 
		{	
			foreach ($forums as $forum) 
			{			
				$forum_output .= '<li><a href="'.get_permalink( $forum->ID ).'"><b>'.$forum->post_title.'</b></a>'
						. '<ol>'
						. $this->recursive_bbpress_loop($forum->ID)
	  					. '</ol>'
	  					. '</li>';
			}
		}
		
		$topics = get_posts(array( 'post_parent' => $forum_id, 'post_type' => 'topic', 'order' => 'ASC', 'order_by' => 'menu_order' ));
	  	if ($topics) 
		{
			foreach($topics as $topic) 
			{ 			
				$forum_output .= '<li><a href="'.get_permalink( $topic->ID ).'"><b>'.$topic->post_title.'</b></a>'
		  				. '<ol>';
				// get replies
	  			$replies = get_posts(array( 'post_parent' => $topic->ID, 'post_type' => 'reply', 'order' => 'ASC', 'order_by' => 'menu_order' ));
				if ($replies) 
		    		{
					foreach($replies as $reply) 
					{ 
		       				$forum_output .= '<li><a href="'.get_permalink( $reply->ID ).'">'.$reply->post_title.'</a></li>';
		      			}
				}
		  		$forum_output .= '</ol>'
		  				. '</li>';
			}
		}

		return $forum_output;
	}
	
	function shortcodes($atts) {
		extract(shortcode_atts(array(
			"forum_id" => 0
		), $atts));
		
		return  '<ul class="wp-sitemap">'
			. $this->recursive_bbpress_loop($forum_id)
			. '</ul>';
	}
}

?>