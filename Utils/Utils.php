<?php

namespace OriggamiWpProgrammingUtils\Utils;

/**
 * Description of Utils
 *
 * @author Pablo Pacheco <pablo.pacheco@origgami.com.br>
 */
if ( !class_exists('\OriggamiWpProgrammingUtils\Utils\Utils') ) {

	class Utils {

		//put your code here


		function getCurrentPostType() {
			global $post, $typenow, $current_screen;

			//we have a post so we can just get the post type from that
			if ( $post && $post->post_type )
				return $post->post_type;

			//check the global $typenow - set in admin.php
			elseif ( $typenow )
				return $typenow;

			//check the global $current_screen object - set in sceen.php
			elseif ( $current_screen && $current_screen->post_type )
				return $current_screen->post_type;

			//lastly check the post_type querystring
			elseif ( isset($_REQUEST['post_type']) )
				return sanitize_key($_REQUEST['post_type']);

			elseif ( isset($_REQUEST['post']) )
				return get_post_type($_REQUEST['post']);

			//we do not know the post type!
			return null;
		}

		function getCurrentPostId() {
			global $post;

			//we have a post so we can just get the post type from that
			if ( $post )
				return $post->ID;

			elseif ( isset($_REQUEST['post']) )
				return $_REQUEST['post'];

			//we do not know the post
			return null;
		}

	}

}
