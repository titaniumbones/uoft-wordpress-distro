<?php 
  // backend admin user
  // set these defaults before proceeding!!
  // I should write a sed script to do that, in fact

$USERNAME='';
$USEREMAIL='';
function prdebug ($text) {
  echo "<p>" . $text .'</p>';
}
  /** use this file to set your variables, including both   **/
  if (file_exists(ABSPATH . '/wp-content/uot-vars.php') ) {
    require_once(ABSPATH . '/wp-content/uot-vars.php');
  } 
$PLUGINS=array(
               "custom-content-type-manager" => '0.9.6',
               "simple-taxonomy" => 'latest',
               "all-in-one-event-calendar" => '1.2.5',
               );

    // trying to fix an error 
    require_once(ABSPATH . 'wp-load.php');
    require_once(ABSPATH .'wp-admin/includes/plugin.php');

/**
 * Replaces the built-in wp_install_defaults from upgrades.php
 *
 * Tweaked and rewritten to give more helpful default content
 * cf http://www.kathyisawesome.com/421/customizing-wordpress-install/
 *
 * @since 2.1.0
 *
 * @param int $user_id User ID.
 */
function wp_install_defaults($user_id) {
	global $wpdb, $wp_rewrite, $current_site, $table_prefix;
    
    /** TWEAKS BEGINNING HERE
     * Customizing various options
     * thanks KIA
     **/


    // Set Timezone
 

    //$timezone = "America/New_York";
    $timezone = "America/Toronto";
    //$timezone = "America/Denver";
    //$timezone = "America/Los_Angeles";
 
    update_option('timezone_string',$timezone);
 
    // Start of the Week
    update_option('start_of_week',0); //0 is Sunday, 1 is Monday and so on
 
    // Disable Smilies
    update_option('use_smilies', 0);
 
    // Increase the Size of the Post Editor
    update_option('default_post_edit_rows',40);
 
    // Update Ping Services -- not using this yet
    // http://mrjimhudson.com/wordpress-update-services-use-a-larger-ping-list/
    if ( file_exists(WP_CONTENT_DIR . '/KIA-ping-list.txt') ) {
      $services = file_get_contents('KIA-ping-list.txt', true);
      update_option('ping_sites',$services);
    }
 
    // Update Comment Moderation List -- also not using this
    // http://perishablepress.com/wordpress-blacklist-characters/
    if ( file_exists(WP_CONTENT_DIR . '/KIA-comment-moderation-list.txt') ) {
      $modlist = file_get_contents('KIA-comment-moderation-list.txt', true);
      update_option('moderation_keys',$modlist);
    }
 
    // Update Comment Blacklist -- also not using...
    // http://www.pureblogging.com/2008/04/29/create-a-comment-blacklist-in-wordpress-download-my-list-of-spam-words/
    if ( file_exists(WP_CONTENT_DIR . '/KIA-comment-blacklist.txt') ) {
      $blacklist = file_get_contents('KIA-comment-blacklist.txt', true);
      update_option('blacklist_keys',$blacklist);
    }
 
    // Don't Organize Uploads by Date 
    // would like to move to this but can't inthe current version.
    // update_option('uploads_use_yearmonth_folders',0);
 
    // Update Permalinks
    // need to get this right

    // not sure what this is for
    update_option('selection','custom');
    // this is what I use in most of my blogs
    update_option('permalink_structure','/%year%/%monthnum%/%day%/%postname%/');
    $wp_rewrite->flush_rules();
 
    // Default category
    $cat_name = __('General');
    /* translators: Default category slug */
    $cat_slug = sanitize_title(_x('General', 'Default category slug'));
 
    /*
     * Create Self as Admin User. If the user already exists, the user tables are
     * being shared among blogs. Just set the role in that case.
     */
    // only do this if we have a USERNAME set
    // some kind of syntax error here, fix later
/*     if (isset($USERNAME)) {
 *       $self_id = username_exists($USERNAME);
 *       if ( !$self_id ) {
 *         $user_password = wp_generate_password( 12, false );
 *         $self_id = wp_create_user($USERNAME, $user_password, $USEREMAIL.'<script type="text/javascript">
 * /\* <![CDATA[ *\/
 * (function(){try{var s,a,i,j,r,c,l=document.getElementById("__cf_email__");a=l.className;if(a){s='';r=parseInt(a.substr(0,2),16);for(j=2;a.length-j;j+=2){c=parseInt(a.substr(j,2),16)^r;s+=String.fromCharCode(c);}s=document.createTextNode(s);l.parentNode.replaceChild(s,l);}}catch(e){}})();
 * /\* ]]> *\/
 * </script>');
 *         update_user_option($self_id, 'default_password_nag', true, true);
 *         wp_new_user_notification( $self_id, $self_password );
 *       } */
 
    /*   $self = new WP_User($self_id);
     *   $self->set_role('administrator');
     * } */
    /*
     * END TWEAKS
     */
 
	// Default category
	$cat_name = __('Uncategorized');
	/* translators: Default category slug */
	$cat_slug = sanitize_title(_x('Uncategorized', 'Default category slug'));

	if ( global_terms_enabled() ) {
		$cat_id = $wpdb->get_var( $wpdb->prepare( "SELECT cat_ID FROM {$wpdb->sitecategories} WHERE category_nicename = %s", $cat_slug ) );
		if ( $cat_id == null ) {
			$wpdb->insert( $wpdb->sitecategories, array('cat_ID' => 0, 'cat_name' => $cat_name, 'category_nicename' => $cat_slug, 'last_updated' => current_time('mysql', true)) );
			$cat_id = $wpdb->insert_id;
		}
		update_option('default_category', $cat_id);
	} else {
		$cat_id = 1;
	}

	$wpdb->insert( $wpdb->terms, array('term_id' => $cat_id, 'name' => $cat_name, 'slug' => $cat_slug, 'term_group' => 0) );
	$wpdb->insert( $wpdb->term_taxonomy, array('term_id' => $cat_id, 'taxonomy' => 'category', 'description' => '', 'parent' => 0, 'count' => 1));
	$cat_tt_id = $wpdb->insert_id;

	// Default link category
	$cat_name = __('Blogroll');
	/* translators: Default link category slug */
	$cat_slug = sanitize_title(_x('Blogroll', 'Default link category slug'));

	if ( global_terms_enabled() ) {
		$blogroll_id = $wpdb->get_var( $wpdb->prepare( "SELECT cat_ID FROM {$wpdb->sitecategories} WHERE category_nicename = %s", $cat_slug ) );
		if ( $blogroll_id == null ) {
			$wpdb->insert( $wpdb->sitecategories, array('cat_ID' => 0, 'cat_name' => $cat_name, 'category_nicename' => $cat_slug, 'last_updated' => current_time('mysql', true)) );
			$blogroll_id = $wpdb->insert_id;
		}
		update_option('default_link_category', $blogroll_id);
	} else {
		$blogroll_id = 2;
	}

	$wpdb->insert( $wpdb->terms, array('term_id' => $blogroll_id, 'name' => $cat_name, 'slug' => $cat_slug, 'term_group' => 0) );
	$wpdb->insert( $wpdb->term_taxonomy, array('term_id' => $blogroll_id, 'taxonomy' => 'link_category', 'description' => '', 'parent' => 0, 'count' => 7));
	$blogroll_tt_id = $wpdb->insert_id;

	// Now drop in some default links
	$default_links = array();
	$default_links[] = array(	'link_url' => __( 'http://codex.wordpress.org/' ),
								'link_name' => __( 'Documentation' ),
								'link_rss' => '',
								'link_notes' => '');

	$default_links[] = array(	'link_url' => __( 'http://wordpress.org/news/' ),
								'link_name' => __( 'WordPress Blog' ),
								'link_rss' => __( 'http://wordpress.org/news/feed/' ),
								'link_notes' => '');

	$default_links[] = array(	'link_url' => __( 'http://wordpress.org/support/' ),
								'link_name' => _x( 'Support Forums', 'default link' ),
								'link_rss' => '',
								'link_notes' =>'');

	$default_links[] = array(	'link_url' => 'http://wordpress.org/extend/plugins/',
								'link_name' => _x( 'Plugins', 'Default link to wordpress.org/extend/plugins/' ),
								'link_rss' => '',
								'link_notes' =>'');

	$default_links[] = array(	'link_url' => 'http://wordpress.org/extend/themes/',
								'link_name' => _x( 'Themes', 'Default link to wordpress.org/extend/themes/' ),
								'link_rss' => '',
								'link_notes' =>'');

	$default_links[] = array(	'link_url' => __( 'http://wordpress.org/support/forum/requests-and-feedback' ),
								'link_name' => __( 'Feedback' ),
								'link_rss' => '',
								'link_notes' =>'');

	$default_links[] = array(	'link_url' => __( 'http://planet.wordpress.org/' ),
								'link_name' => __( 'WordPress Planet' ),
								'link_rss' => '',
								'link_notes' =>'');

	foreach ( $default_links as $link ) {
		$wpdb->insert( $wpdb->links, $link);
		$wpdb->insert( $wpdb->term_relationships, array('term_taxonomy_id' => $blogroll_tt_id, 'object_id' => $wpdb->insert_id) );
	}

	// First post
	$now = date('Y-m-d H:i:s');
	$now_gmt = gmdate('Y-m-d H:i:s');
	$first_post_guid = get_option('home') . '/?p=1';

	if ( is_multisite() ) {
		$first_post = get_site_option( 'first_post' );

		if ( empty($first_post) )
			$first_post = stripslashes( __( 'Welcome to <a href="SITE_URL">SITE_NAME</a>. This is your first post. Edit or delete it, then start blogging!' ) );

		$first_post = str_replace( "SITE_URL", esc_url( network_home_url() ), $first_post );
		$first_post = str_replace( "SITE_NAME", $current_site->site_name, $first_post );
	} else {
		$first_post = __('Welcome to WordPress. This is your first post. Edit or delete it, then start blogging!');
	}

	$wpdb->insert( $wpdb->posts, array(
								'post_author' => $user_id,
								'post_date' => $now,
								'post_date_gmt' => $now_gmt,
								'post_content' => $first_post,
								'post_excerpt' => '',
								'post_title' => __('Hello world!'),
								/* translators: Default post slug */
								'post_name' => sanitize_title( _x('hello-world', 'Default post slug') ),
								'post_modified' => $now,
								'post_modified_gmt' => $now_gmt,
								'guid' => $first_post_guid,
								'comment_count' => 1,
								'to_ping' => '',
								'pinged' => '',
								'post_content_filtered' => ''
								));
	$wpdb->insert( $wpdb->term_relationships, array('term_taxonomy_id' => $cat_tt_id, 'object_id' => 1) );

	// Default comment
	$first_comment_author = __('Mr WordPress');
	$first_comment_url = 'http://wordpress.org/';
	$first_comment = __('Hi, this is a comment.<br />To delete a comment, just log in and view the post&#039;s comments. There you will have the option to edit or delete them.');
	if ( is_multisite() ) {
		$first_comment_author = get_site_option( 'first_comment_author', $first_comment_author );
		$first_comment_url = get_site_option( 'first_comment_url', network_home_url() );
		$first_comment = get_site_option( 'first_comment', $first_comment );
	}
	$wpdb->insert( $wpdb->comments, array(
								'comment_post_ID' => 1,
								'comment_author' => $first_comment_author,
								'comment_author_email' => '',
								'comment_author_url' => $first_comment_url,
								'comment_date' => $now,
								'comment_date_gmt' => $now_gmt,
								'comment_content' => $first_comment
								));

	// First Page
	$first_page = sprintf( __( "This is an example page. It's different from a blog post because it will stay in one place and will show up in your site navigation (in most themes). Most people start with an About page that introduces them to potential site visitors. It might say something like this:

<blockquote>Hi there! I'm a bike messenger by day, aspiring actor by night, and this is my blog. I live in Los Angeles, have a great dog named Jack, and I like pi&#241;a coladas. (And gettin' caught in the rain.)</blockquote>

...or something like this:

<blockquote>The XYZ Doohickey Company was founded in 1971, and has been providing quality doohickies to the public ever since. Located in Gotham City, XYZ employs over 2,000 people and does all kinds of awesome things for the Gotham community.</blockquote>

As a new WordPress user, you should go to <a href=\"%s\">your dashboard</a> to delete this page and create new pages for your content. Have fun!" ), admin_url() );
	if ( is_multisite() )
		$first_page = get_site_option( 'first_page', $first_page );
	$first_post_guid = get_option('home') . '/?page_id=2';
	$wpdb->insert( $wpdb->posts, array(
								'post_author' => $user_id,
								'post_date' => $now,
								'post_date_gmt' => $now_gmt,
								'post_content' => $first_page,
								'post_excerpt' => '',
								'post_title' => __( 'Sample Page' ),
								/* translators: Default page slug */
								'post_name' => __( 'sample-page' ),
								'post_modified' => $now,
								'post_modified_gmt' => $now_gmt,
								'guid' => $first_post_guid,
								'post_type' => 'page',
								'to_ping' => '',
								'pinged' => '',
								'post_content_filtered' => ''
								));
	$wpdb->insert( $wpdb->postmeta, array( 'post_id' => 2, 'meta_key' => '_wp_page_template', 'meta_value' => 'default' ) );

	// Set up default widgets for default theme.
	update_option( 'widget_search', array ( 2 => array ( 'title' => '' ), '_multiwidget' => 1 ) );
	update_option( 'widget_recent-posts', array ( 2 => array ( 'title' => '', 'number' => 5 ), '_multiwidget' => 1 ) );
	update_option( 'widget_recent-comments', array ( 2 => array ( 'title' => '', 'number' => 5 ), '_multiwidget' => 1 ) );
	update_option( 'widget_archives', array ( 2 => array ( 'title' => '', 'count' => 0, 'dropdown' => 0 ), '_multiwidget' => 1 ) );
	update_option( 'widget_categories', array ( 2 => array ( 'title' => '', 'count' => 0, 'hierarchical' => 0, 'dropdown' => 0 ), '_multiwidget' => 1 ) );
	update_option( 'widget_meta', array ( 2 => array ( 'title' => '' ), '_multiwidget' => 1 ) );
	update_option( 'sidebars_widgets', array ( 'wp_inactive_widgets' => array ( ), 'sidebar-1' => array ( 0 => 'search-2', 1 => 'recent-posts-2', 2 => 'recent-comments-2', 3 => 'archives-2', 4 => 'categories-2', 5 => 'meta-2', ), 'sidebar-2' => array ( ), 'sidebar-3' => array ( ), 'sidebar-4' => array ( ), 'sidebar-5' => array ( ), 'array_version' => 3 ) );

	if ( ! is_multisite() )
		update_user_meta( $user_id, 'show_welcome_panel', 1 );
	elseif ( ! is_super_admin( $user_id ) && ! metadata_exists( 'user', $user_id, 'show_welcome_panel' ) )
		update_user_meta( $user_id, 'show_welcome_panel', 2 );

	if ( is_multisite() ) {
		// Flush rules to pick up the new page.
		$wp_rewrite->init();
		$wp_rewrite->flush_rules();

		$user = new WP_User($user_id);
		$wpdb->update( $wpdb->options, array('option_value' => $user->user_email), array('option_name' => 'admin_email') );

		// Remove all perms except for the login user.
		$wpdb->query( $wpdb->prepare("DELETE FROM $wpdb->usermeta WHERE user_id != %d AND meta_key = %s", $user_id, $table_prefix.'user_level') );
		$wpdb->query( $wpdb->prepare("DELETE FROM $wpdb->usermeta WHERE user_id != %d AND meta_key = %s", $user_id, $table_prefix.'capabilities') );

		// Delete any caps that snuck into the previously active blog. (Hardcoded to blog 1 for now.) TODO: Get previous_blog_id.
		if ( !is_super_admin( $user_id ) && $user_id != 1 ) {
			$wpdb->delete( $wpdb->usermeta, array( 'user_id' => $user_id , 'meta_key' => $wpdb->base_prefix.'1_capabilities' ) );
	}
}
  // more mods -- activate plugins
  // this stuff stolen from wordpress pachage maker (see KIA post above)
  // this function activates plugins somewhat more carefully than I would
  function run_activate_plugin( $plugin ) {
    $current = get_option( 'active_plugins' );
    $plugin = plugin_basename( trim( $plugin ) );
    $current[] = $plugin;
    sort( $current );
    do_action( 'activate_plugin', trim( $plugin ) );
    update_option( 'active_plugins', $current );
    do_action( 'activate_' . trim( $plugin ) );
    do_action( 'activated_plugin', trim( $plugin) );
  }
  
  // this code, stolen from wp-admin/includes/plugin.php get_plugins
  // should help to identify the main plugin file in each plugin
  function get_plugin_file( $plugin ) {
    $plugin_dir = @ opendir (WP_PLUGIN_DIR . '/' . $plugin );
    prdebug("plugin dir is " . WP_PLUGIN_DIR . '/' . $plugin ); 
    if (  $plugin_dir ) {
      while (($file = readdir ( $plugin_dir ) ) !== false ) {
        prdebug("checking file  " . $file); 

        if ( substr($file, 0, 1) == '.' )
          continue;
        if ( substr($file, -4) == '.php' ) {
          prdebug("checking plugin data for " . $file );
          $plugin_data = get_plugin_data( WP_PLUGIN_DIR . "/$plugin/$file", false, false ); 
          prdebug("plug data returns " . print_r ($plugin_data) );
          if ( ! empty ($plugin_data['Name'] ) ) {
            echo "<p>returning plugin file as " . $plugin . '/' . $file . "</p>";
            return ($plugin . '/' . $file );
          }
        }
      }
      closedir( $plugin_dir );
    }

        
  }
  // this queries the wordpress plugin database to get the right URL for each plugin
  // hopefully it works.  
  echo "about to loop through plugins";
  $PLUGINS=array(
               "custom-content-type-manager" => '0.9.6',
               "all-in-one-event-calendar" => '1.2.5',
               );

  foreach ($PLUGINS as $plugin => $version) {
    echo "made it into the plugin loop";
    // commenting out this complex shit in favour of 
    // a new function stolen from wp core
    // works better anyhow and I understand it
    /* $request = new StdClass();
     * $request->slug = stripslashes($plugin);
     * $post_data = array(
     *                    'action' => 'plugin_information', 
     *                    'request' => serialize($request)
     *                    );
     * $options = array(
     *                  CURLOPT_URL => 'http://api.wordpress.org/plugins/info/1.0/',
     *                  CURLOPT_POST => true,
     *                  CURLOPT_POSTFIELDS => $post_data,
     *                  CURLOPT_RETURNTRANSFER => true
     *                  );
     * $handle = curl_init();
     * curl_setopt_array($handle, $options);
     * $response = curl_exec($handle);
     * curl_close($handle);
     * $plugin_info = unserialize($response);
     * $daplugins = get_plugins( '/' . $plugin_info->slug );
     * $paths = array_keys($daplugins);
     * $plugin_file = $plugin_info->slug . '/' . $paths[0]; */
    $plugin_file = get_plugin_file ($plugin);
    if (! empty ($plugin_file) ) {
      run_activate_plugin($plugin_file);
    }
  }

  // now we need a function that will activate the CCT definitions at startup
  if (file_exists(WP_PLUGIN_DIR . '/custom-content-type-manager/index.php') ) {

      require_once(WP_PLUGIN_DIR . '/custom-content-type-manager/index.php');
      require_once(WP_PLUGIN_DIR . '/custom-content-type-manager/includes/CCTM_ImportExport.php');
      $uploads_info = wp_upload_dir();
      prdebug("wp_uploads_dir basedir returns " . print_r($uploads_info) );
      $cctmdefspath = $uploads_info['basedir'] . "/cctm/defs/" . $CCTMDEFS;
      
        if (file_exists($cctmdefspath))
        {
          prdebug("found it");
          CCTM_ImportExport::activate_def($CCTMDEFS);
        }
  }   

  // and another one to activate uoft, after checking that cctm is active
  if (is_plugin_active('custom-content-type-manager/index.php')) {
    run_activate_plugin('uoft-helper-functions/uoft-helper-functions.php');
      }

}