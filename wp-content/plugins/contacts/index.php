<?php
/*
Plugin Name: contacts
Description: Display contacts widget
*/

// Creating the widget 
class Contacts_widget extends WP_Widget {

function __construct() {
  parent::__construct(
  // Base ID of your widget
  'contacts_widget', 

  // Widget name will appear in UI
  __('contacts widget', 'who_widget_domain'), 

  // Widget description
  array( 'description' => __( 'Sample widget contacts', 'contacts_widget_domain' ), ) 
  );
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) { ?>
    <?php if( have_rows('contacts', 10) ):?>
      <div class="widget widget-contacts">
        <?php $title = apply_filters( 'widget_title', $instance['title'] ); ?>
        <h4 class="widgettitle"><?php echo $title; ?></h4>
        <div class="container">
              <?php while ( have_rows('contacts', 10) ) : the_row(); ?>
                <ul>
                   <li><?php the_sub_field('name'); ?></li>
                   <li><a href="mailto:<?php the_sub_field('mail'); ?>"> <?php the_sub_field('mail'); ?></a></li>
                   <li><?php the_sub_field('telephone'); ?></li>
                 </ul>
              <?php endwhile; ?>
        </div>
     </div>
   <?php endif; ?>
<?php }
    
// Widget Backend 
public function form( $instance ) {
  if ( isset( $instance[ 'title' ] ) ) {
    $title = $instance[ 'title' ];
    }
  else {
    $title = __( 'New title', 'contacts_widget_domain' );
    }
  // Widget admin form
  ?>
  <p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
  </p>
  <?php 
  }
    
  // Updating widget replacing old instances with new
  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
  return $instance;
  }
} // Class contacts_widget ends here

// Register and load the widget
function contacts_load_widget() {
  register_widget( 'contacts_widget' );
}
add_action( 'widgets_init', 'contacts_load_widget' );

if( !function_exists( 'wp_get_post_type_link' )  ){
    function wp_get_post_type_link( &$post_type ){

        global $wp_rewrite; 

        if ( ! $post_type_obj = get_post_type_object( $post_type ) )
            return false;

        if ( get_option( 'permalink_structure' ) && is_array( $post_type_obj->rewrite ) ) {

            $struct = $post_type_obj->rewrite['slug'] ;
            if ( $post_type_obj->rewrite['with_front'] )
                $struct = $wp_rewrite->front . $struct;
            else
                $struct = $wp_rewrite->root . $struct;

            $link = home_url( user_trailingslashit( $struct, 'post_type_archive' ) );       

        } else {
            $link = home_url( '?post_type=' . $post_type );
        }

        return apply_filters( 'the_permalink', $link );
    }
}

?>