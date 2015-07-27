<?php
/*
Author: Eddie Machado
URL: http://themble.com/bones/

This is where you can drop your custom functions or
just edit things like thumbnail sizes, header images,
sidebars, comments, etc.
*/

// LOAD BONES CORE (if you remove this, the theme will break)
require_once( 'library/bones.php' );

// CUSTOMIZE THE WORDPRESS ADMIN (off by default)
require_once( 'library/admin.php' );

/*********************
LAUNCH BONES
Let's get everything up and running.
*********************/

function bones_ahoy() {

  //Allow editor style.
  add_editor_style( get_stylesheet_directory_uri() . '/library/css/editor-style.css' );

  // let's get language support going, if you need it
  load_theme_textdomain( 'bonestheme', get_template_directory() . '/library/translation' );

  // USE THIS TEMPLATE TO CREATE CUSTOM POST TYPES EASILY
  require_once( 'library/custom-post-type.php' );

  // launching operation cleanup
  add_action( 'init', 'bones_head_cleanup' );
  // A better title
  add_filter( 'wp_title', 'rw_title', 10, 3 );
  // remove WP version from RSS
  add_filter( 'the_generator', 'bones_rss_version' );
  // remove pesky injected css for recent comments widget
  add_filter( 'wp_head', 'bones_remove_wp_widget_recent_comments_style', 1 );
  // clean up comment styles in the head
  add_action( 'wp_head', 'bones_remove_recent_comments_style', 1 );
  // clean up gallery output in wp
  add_filter( 'gallery_style', 'bones_gallery_style' );

  // enqueue base scripts and styles
  add_action( 'wp_enqueue_scripts', 'bones_scripts_and_styles', 999 );
  // ie conditional wrapper

  // launching this stuff after theme setup
  bones_theme_support();

  // adding sidebars to Wordpress (these are created in functions.php)
  add_action( 'widgets_init', 'bones_register_sidebars' );

  // cleaning up random code around images
  add_filter( 'the_content', 'bones_filter_ptags_on_images' );
  // cleaning up excerpt
  add_filter( 'excerpt_more', 'bones_excerpt_more' );

} /* end bones ahoy */

// let's get this party started
add_action( 'after_setup_theme', 'bones_ahoy' );


/************* OEMBED SIZE OPTIONS *************/

if ( ! isset( $content_width ) ) {
	$content_width = 680;
}

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
add_image_size( 'bones-thumb-600', 600, 150, true );
add_image_size( 'bones-thumb-300', 300, 100, true );

/*
to add more sizes, simply copy a line from above
and change the dimensions & name. As long as you
upload a "featured image" as large as the biggest
set width or height, all the other sizes will be
auto-cropped.

To call a different size, simply change the text
inside the thumbnail function.

For example, to call the 300 x 100 sized image,
we would use the function:
<?php the_post_thumbnail( 'bones-thumb-300' ); ?>
for the 600 x 150 image:
<?php the_post_thumbnail( 'bones-thumb-600' ); ?>

You can change the names and dimensions to whatever
you like. Enjoy!
*/

add_filter( 'image_size_names_choose', 'bones_custom_image_sizes' );

function bones_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'bones-thumb-600' => __('600px by 150px'),
        'bones-thumb-300' => __('300px by 100px')
    ) );
}

/*
The function above adds the ability to use the dropdown menu to select
the new images sizes you have just created from within the media manager
when you add media to your content blocks. If you add more image sizes,
duplicate one of the lines in the array and name it according to your
new image size.
*/

/************* THEME CUSTOMIZE *********************/

/* 
  A good tutorial for creating your own Sections, Controls and Settings:
  http://code.tutsplus.com/series/a-guide-to-the-wordpress-theme-customizer--wp-33722
  
  Good articles on modifying the default options:
  http://natko.com/changing-default-wordpress-theme-customization-api-sections/
  http://code.tutsplus.com/tutorials/digging-into-the-theme-customizer-components--wp-27162
  
  To do:
  - Create a js for the postmessage transport method
  - Create some sanitize functions to sanitize inputs
  - Create some boilerplate Sections, Controls and Settings
*/

function bones_theme_customizer($wp_customize) {
  // $wp_customize calls go here.
  //
  // Uncomment the below lines to remove the default customize sections 

  // $wp_customize->remove_section('title_tagline');
  // $wp_customize->remove_section('colors');
  // $wp_customize->remove_section('background_image');
  // $wp_customize->remove_section('static_front_page');
  // $wp_customize->remove_section('nav');

  // Uncomment the below lines to remove the default controls
  // $wp_customize->remove_control('blogdescription');
  
  // Uncomment the following to change the default section titles
  // $wp_customize->get_section('colors')->title = __( 'Theme Colors' );
  // $wp_customize->get_section('background_image')->title = __( 'Images' );
}

add_action( 'customize_register', 'bones_theme_customizer' );

/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas
function bones_register_sidebars() {
	register_sidebar(array(
		'id' => 'sidebar1',
		'name' => __( 'Sidebar 1', 'bonestheme' ),
		'description' => __( 'The first (primary) sidebar.', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

	/*
	to add more sidebars or widgetized areas, just copy
	and edit the above sidebar code. In order to call
	your new sidebar just use the following code:

	Just change the name to whatever your new
	sidebar's id is, for example:

	register_sidebar(array(
		'id' => 'sidebar2',
		'name' => __( 'Sidebar 2', 'bonestheme' ),
		'description' => __( 'The second (secondary) sidebar.', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

	To call the sidebar in your template, you can just copy
	the sidebar.php file and rename it to your sidebar's name.
	So using the above example, it would be:
	sidebar-sidebar2.php

	*/
} // don't remove this bracket!


/************* COMMENT LAYOUT *********************/

// Comment Layout
function bones_comments( $comment, $args, $depth ) {
   $GLOBALS['comment'] = $comment; ?>
  <div id="comment-<?php comment_ID(); ?>" <?php comment_class('cf'); ?>>
    <article  class="cf">
      <header class="comment-author vcard">
        <?php
        /*
          this is the new responsive optimized comment image. It used the new HTML5 data-attribute to display comment gravatars on larger screens only. What this means is that on larger posts, mobile sites don't have a ton of requests for comment images. This makes load time incredibly fast! If you'd like to change it back, just replace it with the regular wordpress gravatar call:
          echo get_avatar($comment,$size='32',$default='<path_to_url>' );
        */
        ?>
        <?php 
          $login = get_comment_author(); 
          $user = get_user_by( "login", $login);
          $user_id = $user->DATA->ID;
          $first_name = get_the_author_meta('first_name',$user_id);
          $last_name = get_the_author_meta('last_name',$user_id);
        ?>
        <p> <span class="author-name"><?php echo $first_name." ".$last_name; ?></span> <span class="date"> <?php echo get_the_time('d.m.Y, g:i'); ?></span> <?php edit_comment_link(__( '(Edit)', 'bonestheme' ),'  ',''); ?> </p>


      </header>
      <?php if ($comment->comment_approved == '0') : ?>
        <div class="alert alert-info">
          <p><?php _e( 'Your comment is awaiting moderation.', 'bonestheme' ) ?></p>
        </div>
      <?php endif; ?>
      <section class="comment_content cf">
        <?php comment_text() ?>
      </section>
      <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
    </article>
  <?php // </li> is added by WordPress automatically ?>
<?php
} // don't remove this bracket!


/*
This is a modification of a function found in the
twentythirteen theme where we can declare some
external fonts. If you're using Google Fonts, you
can replace these fonts, change it in your scss files
and be up and running in seconds.
*/

function bones_fonts() {
  wp_enqueue_style('googleFonts', 'http://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic');
  wp_enqueue_style('googleFontss', 'http://fonts.googleapis.com/css?family=Titillium+Web:300,300italic,400,400italic,600,700,700italic,600italic');
}

function add_js() {
    wp_enqueue_script( 'TweenMax', get_template_directory_uri() .'/library/js/libs/TweenMax.min.js' );
}

add_action('wp_enqueue_scripts', 'bones_fonts');
add_action('wp_enqueue_scripts', 'add_js');


add_action( 'init', 'blockusers_init' );
function blockusers_init() {
    if ( is_admin() && ! current_user_can( 'administrator' ) && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
        wp_redirect( home_url() );
        exit;
    }
}

add_action('after_setup_theme', 'remove_admin_bar');


function remove_admin_bar() {
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}

function my_login_logo() { ?>
    <style type="text/css">
        body.login {
            background-image: url('<?php echo get_template_directory_uri(); ?>/library/images/login-background.jpg');
            background-position: center 20px;
            border-bottom: 2px solid #fff;
            box-sizing: border-box;
        }
        .login h1 a {
            background-image: url('<?php echo get_template_directory_uri(); ?>/library/images/login-top.png');
            position: relative;
            top: 0;
            left: 0;
            background-size: cover;
            -webkit-background-size: cover;
            background-position: inherit;
            background-repeat: no-repeat;
            color: #999;
            height: 225px;
            font-size: 20px;
            font-weight: 400;
            line-height: 1.3em;
            margin: 0 auto;
            padding: 0;
            text-decoration: none;
            width: 520px;
            text-indent: -9999px;
            display: block;
        }
        #login {
          width: 520px;
        }

        .intro-login {
            text-align: center;
            color: #fff;
            width: 70%;
            margin: 0 auto;
            font-family: 'Titillium Web', sans-serif;
            padding-bottom: 20px;
            font-weight: 600;
            font-size: 14px;
        }

        #login .message {
            display: none;
        }

        #login #loginform{
            margin-top: 5px;
            background: none;
            margin-left: 0;
            padding: 25px;
            font-weight: 400;
            overflow: hidden;
            border: 2px solid #ffffff;
            box-shadow: none;
            border-radius: 3px;
        }
        .submit-wrapper {
            display: -webkit-flex;
            display: flex;
            justify-content: space-between;
            -webkit-justify-content: space-between;
        }

        .login form input[type=text], .login form input[type=password]{
            font-size: 16px;
            margin: 0;
            padding: 0;
            color: #6d6d6d;
            font-family: 'Titillium Web', sans-serif;
            margin-right: 10px;
            width: 40%;
            padding-left: 4px;
            border-radius: 3px;
            height: 30px;
        }

        .login form #wp-submit {
            background: none;
            border: 1px solid #fff;
            color: #fff;
            border-radius: 3px;
            text-transform: uppercase;
            float: none;
        }

        .login form .forgetmenot {
            display: none;
        }

        #login_error {
            display: none;
        }

        .login-botton {
            position:  absolute;
            right: 0;
            bottom: 0;
            width: 378px;
            height: 54px;
            background-image: url('<?php echo get_template_directory_uri(); ?>/library/images/login-bottom.png');
        }

        @media (max-width: 600px) {
            .login h1 a {
                max-width: 100%;
                background-image: url('<?php echo get_template_directory_uri(); ?>/library/images/login-top-mobile.png');
                -webkit-background-position: center, center;
                background-position: center, center;
                -webkit-background-size: 100%;
                background-size: 100%;
                height: 208px;
            }
            #login {
                max-width: 80%;
            }

            .submit-wrapper {
                flex-direction: column;
                -webkit-flex-direction: column
                justify-content: center;
                -webkit-justify-content: center;
            }

            .login form input[type=text], .login form input[type=password], .login form p.submit {
                width: 90%;
                margin: 0 auto 10px;
            }

            .login form #wp-submit { 
                width: 100%;
            }

            #login form p.submit {
              margin: 0 auto;
            }

            @media (max-width: 440px) {
                .login h1 a {
                    height: 148px;
                }
            }
        }

    </style>
<?php }
add_action( 'login_enqueue_scripts', 'my_login_logo' );

/**
 * Hide editor for specific page templates.
 *
 */
add_action( 'admin_init', 'hide_editor' );
 
function hide_editor() {
    // Get the Post ID.
    $post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'] ;
    if( !isset( $post_id ) ) return;

 
    if( $post_id == 10 || $post_id == 12 ){ // edit the template name
        remove_post_type_support('page', 'editor');
    }
}

function wpse_setup_theme() {
   add_theme_support( 'post-thumbnails' );
   add_image_size( 'article-thumbnail', 206 , 133, true );
   add_image_size( 'article-full-thumbnail', 586, 210, true );
}

add_action( 'after_setup_theme', 'wpse_setup_theme' );

function my_image_sizes($sizes) {
    $addsizes = array(
        "article-thumbnail" => __( "Article thumbnail"),
        "article-full-thumbnail" => __( "Article full thumbnail")
    );
    $newsizes = array_merge($sizes, $addsizes);
    return $newsizes;
}

add_filter('image_size_names_choose', 'my_image_sizes');

function croppExcerpt() {
  global $post;
  $excerpt = get_the_content();
  $excerpt = preg_replace(" (\[.*?\])",'',$excerpt);
  $excerpt = strip_shortcodes($excerpt);
  $excerpt = strip_tags($excerpt);
  $excerpt = substr($excerpt, 0, 210);
  $excerpt = substr($excerpt, 0, strripos($excerpt, " "));
  $excerpt = trim(preg_replace( '/\s+/', ' ', $excerpt));
  $excerpt = $excerpt.' ... <a class="read-more bold" href="'.$permalink.'">Lire la suite</a>';
  return $excerpt;
}

function home_page_menu_args( $args ) {
  $args['show_home'] = true;
  return $args;
  }
add_filter( 'wp_page_menu_args', 'home_page_menu_args' );


/* DON'T DELETE THIS CLOSING TAG */ ?>
