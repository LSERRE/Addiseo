<?php
/*
Plugin Name: Ateliers
Description: Display Atliers widget
*/

// Creating the widget 
class atelier_widget extends WP_Widget {

function __construct() {
  parent::__construct(
  // Base ID of your widget
  'atelier_widget', 

  // Widget name will appear in UI
  __('Atelier', 'atelier_widget_domain'), 

  // Widget description
  array( 'description' => __( 'Sample widget which display forum registration', 'atelier_widget_domain' ), ) 
  );
}

// Creating widget front-end
// This is where the action happens
public function widget( $args, $instance ) {
    $title = apply_filters( 'widget_title', $instance['title'] );
    $subtitle = apply_filters( 'widget_title', $instance['subtitle'] ); ?>
    
        <div class="widget widget-ateliers">
            <?php if ( ! empty( $title ) ) { ?>
                <div class="title-wrapper"><h3 class="widget-title"> <?php echo $title; ?> </h3><div class="border"></div></div>
            <?php } ?>
        
            <?php 
                $args = array(
                    'post_type' => 'atelier',
                );
        
                $query = new WP_Query( $args ); 
            ?>
        
            <form action="<?php bloginfo('url'); ?>/wordpress/wp-admin/admin-post.php" method="POST">
                <?php 
                    $current_user = wp_get_current_user();
                    $user_id = $current_user->data->ID;
                    $repeater_field_key = "horraires";
        
                    $values0 = get_field($repeater_field_key, 76);
                    $values1 = get_field($repeater_field_key, 79);
                    $values2 = get_field($repeater_field_key, 80);
        
                    $value0 = "";
                    $value1 = "";
                    $value2 = "";
        
                    if ( ! empty( $subtitle ) )
                    echo '<p class="widget-subtitle"> '.$subtitle.' </p>' ;
                ?>
        
                <?php 
                foreach ($values0 as $key1 => $value) {
                    foreach ($values0[$key1]["collaborateurs"] as $key2  => $collaborateur) {
                        if($values0[$key1]["collaborateurs"][$key2]["collaborateur"]["ID"] == $user_id ) {
                            $value0 = $key1;
                        }
                    }
                } 
                ?>
        
                <?php 
                foreach ($values1 as $key1 => $value) {
                    foreach ($values1[$key1]["collaborateurs"] as $key2  => $collaborateur) {
                        if($values1[$key1]["collaborateurs"][$key2]["collaborateur"]["ID"] == $user_id ) {
                            $value1 = $key1;
                        }
                    }
                } 
                ?>
        
                <?php 
                foreach ($values2 as $key1 => $value) {
                    foreach ($values2[$key1]["collaborateurs"] as $key2  => $collaborateur) {
                        if($values2[$key1]["collaborateurs"][$key2]["collaborateur"]["ID"] == $user_id ) {
                            $value2 = $key1;
                        }
                    }
                }

                ?>


                
                <?php $index1 = 0; ?>

                <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                    <select class="<?php if($value0 !== "" && $index1 == 0) { echo 'active'; } if($value1 !== "" && $index1 == 1) { echo 'active'; } if($value2 !== "" && $index1 == 2) { echo 'active'; } ?>" name="horraire-<?php echo $index1; ?>" id="horraire">
                        <option disabled="disabled" selected="selected" value=""><?php the_title(); ?></option>
                            <?php $index2 = 0; ?>
                            <?php if( have_rows('horraires') ): while ( have_rows('horraires') ) : the_row(); ?>
                                <option <?php 
                                            if($index1 === 0){
                                                if($index2 === $value0){
                                                    echo "selected class='active'";
                                                }
                                            }
                                            if($index1 === 1){
                                                if($index2 === $value1){
                                                    echo "selected class='active'";
                                                }
                                            }
                                            if($index1 === 2){
                                                if($index2 === $value2){
                                                    echo "selected class='active'";
                                                }
                                            }
                                        ?>
                                value="<?php echo $index2; ?>"><?php the_sub_field('date'); ?></option>
                                <?php $index2 = $index2 + 1; ?>
                            <?php endwhile; endif; ?>
                        </optgroup>
                    </select>
                    <?php $index1 = $index1 + 1; ?>
                <?php endwhile;?>
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                <input type="hidden" name="action" value="atelier_submission">
                <input type="submit" value="Confirm">
            </form>
        </div>

<?php }
    
public function form( $instance ) {
  if ( isset( $instance[ 'title' ] ) ) {
    $title = $instance[ 'title' ];
    }
  else {
    $title = __( 'New title', 'wpb_widget_domain' );
    }

    if ( isset( $instance[ 'subtitle' ] ) ) {
    $subtitle = $instance[ 'subtitle' ];
    }
  else {
    $subtitle = __( 'New subtitle', 'wpb_widget_domain' );
    }
  // Widget admin form
  ?>


  <p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
  </p>

    <p>
    <label for="<?php echo $this->get_field_id( 'subtitle' ); ?>"><?php _e( 'Subtitle:' ); ?></label> 
    <input class="widefat" id="<?php echo $this->get_field_id( 'subtitle' ); ?>" name="<?php echo $this->get_field_name( 'subtitle' ); ?>" type="text" value="<?php echo esc_attr( $subtitle ); ?>" />
  </p>
  <?php 
  }
    
  // Updating widget replacing old instances with new
  public function update( $new_instance, $old_instance ) {
    $instance = array();
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
    $instance['subtitle'] = ( ! empty( $new_instance['subtitle'] ) ) ? strip_tags( $new_instance['subtitle'] ) : '';
  return $instance;
  }
} // Class wpb_widget ends here

// Register and load the widget
function atelier_load_widget() {
  register_widget( 'atelier_widget' );
}
add_action( 'widgets_init', 'atelier_load_widget' );

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

add_action('admin_post_atelier_submission', 'check_for_event_submissions');

function check_for_event_submissions(){
    $repeater_field_key = "horraires";

    $horraire0 = $_POST['horraire-0'];
    $horraire1 = $_POST['horraire-1'];
    $horraire2 = $_POST['horraire-2'];
    $user_id   = $_POST['user_id'];
    $user = get_user_by( 'id', $user_id );
    $values0 = get_field($repeater_field_key, 76);
    $values1 = get_field($repeater_field_key, 79);
    $values2 = get_field($repeater_field_key, 80);
    $flag;
    $array = ( Array( "collaborateur" => $user ) );
    foreach ($values0 as $key1 => $value) {
        foreach ($values0[$key1]["collaborateurs"] as $key2  => $collaborateur) {
            if($values0[$key1]["collaborateurs"][$key2]["collaborateur"]["ID"] == $user_id ) {
                unset($values0[$key1]["collaborateurs"][$key2]);
            }
        }
        if( $key1 == $horraire0 && $horraire0 != "" ){
            array_push($values0[$key1]["collaborateurs"], $array);
        }
    }
    foreach ($values1 as $key1 => $value) {
        foreach ($values1[$key1]["collaborateurs"] as $key2  => $collaborateur) {
            if($values1[$key1]["collaborateurs"][$key2]["collaborateur"]["ID"] == $user_id ) {
                unset($values1[$key1]["collaborateurs"][$key2]);
            }
        }
        if( $key1 == $horraire1 && $horraire1 != "" ){
            array_push($values1[$key1]["collaborateurs"], $array);
        }
    }
    foreach ($values2 as $key1 => $value) {
        foreach ($values2[$key1]["collaborateurs"] as $key2  => $collaborateur) {
            if($values2[$key1]["collaborateurs"][$key2]["collaborateur"]["ID"] == $user_id ) {
                unset($values2[$key1]["collaborateurs"][$key2]);
            }
        }
        if( $key1 == $horraire2 && $horraire2 != "" ){
            array_push($values2[$key1]["collaborateurs"], $array);
        }
    }
    $valuesIndex0 = array_values($values0); // 'reindex' array
    $valuesIndex1 = array_values($values1); // 'reindex' array
    $valuesIndex2 = array_values($values2); // 'reindex' array
    update_field('horraires', $valuesIndex0, 76);
    update_field('horraires', $valuesIndex1, 79);
    update_field('horraires', $valuesIndex2, 80);

    wp_redirect( home_url() ); exit;
}


?>