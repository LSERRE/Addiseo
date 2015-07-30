<?php
/*
 Template Name: Participants
 *
 * This is your custom page template. You can create as many of these as you need.
 * Simply name is "page-whatever.php" and in add the "Template Name" title at the
 * top, the same way it is here.
 *
 * When you create your page, you can just select the template and viola, you have
 * a custom page template to call your very own. Your mother would be so proud.
 *
 * For more info: http://codex.wordpress.org/Page_Templates
*/
?>

<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap cf">

					<main id="main" class="m-all t-2of3 d-5of7 cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">
						<?php 
						global $paged;
                      	global $wp_query;
					    global $wpdb;

					    $letter = "";

					    if( isset( $_GET['letter'] )) {

					      $letter = esc_attr($_GET['letter']);

							$postids = $wpdb->get_col($wpdb->prepare("
								SELECT      ID
								FROM        $wpdb->posts
								WHERE       SUBSTR($wpdb->posts.post_title,1,1) = %s
								AND 		$wpdb->posts.post_type = 'participant'
								ORDER BY    $wpdb->posts.post_title",$letter));
	
							if ( $postids ) {
								$args = array(
									'post__in' => $postids,
									'post_type' => 'participant',
									'post_status' => 'publish',
							        'posts_per_page' => 5,
									'paged' => $paged,
									'ignore_sticky_posts' => 1
								);
							}else {
								$args = array(  );
							}
						} else {
							// Define the query
				    		$args = array(
					    	    'post_type' => 'participant',
					    	    'posts_per_page' => 5,
								'paged' => $paged
					    	);
						}

						$field_key = "field_55b69653c9163";
				        $field = get_field_object($field_key);

                      	$temp = $wp_query; 
                      	$wp_query = null; 
                      	$wp_query = new WP_Query($args); 

		    			?>

		    			<div class="filters">
		    				<div class="filter_by"> Filtrer par :</div>
		    				
				            <select name="" id="" class="area-select">';
				                <?php foreach( $field['choices'] as $k => $v ){ ?>
				                    <option value='<?php echo $k; ?>'> <?php echo $v; ?> </option>;
				                <?php } ?>
				           	</select>

		    				<select name="" id="" class="letter-select">
		    					<?php foreach(range('a', 'z') as $letter2) { ?>
		    						<optgroup>[a-z]</optgroup>
   									<option <?php if(strtolower($letter) == $letter2){ echo "selected"; }?> > <?php echo $letter2; ?></option>'; 
								<?Php } ?>
		    				</select>

		    			</div>
	
		        		<?php while ( $wp_query->have_posts() ) : $wp_query->the_post(); ?>
							<?php $user = get_field( 'utilisateur' ); ?>
							<?php $userFirstLetter = substr($user["user_firstname"], 0, 1);  ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf participant' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

								<?php $user = get_field( 'utilisateur' ); ?>
								<?php // print_r($user); ?>

								<header class="article-header">

									<?php $image = get_field( 'visuel' ); ?>

									<img src="<?php echo $image['url']; ?>" title="<?php echo $title; ?>" alt="<?php echo 'logo '.$title; ?>">

									<div class="content-wrapper">
										<h1 class="bold page-title"><?php echo $user["user_firstname"]." <span class='lastname'>".$user['user_lastname']."</span>"; ?></h1>
										<h2 class="lieu"><?php the_field('lieu'); ?></h2>
										<h3 class="position"><?php the_field('position'); ?></h2>
									</div> 	
								</header>

								<div class="clearfix"></div>

								<section class="entry-content cf" itemprop="articleBody">
									<ul class="questions">
										<li>
											<p class="question">Your favorite qualities in a person?</p>
											<p class="answer"><?php the_field('qualities'); ?></p>
										</li>
										<li>
											<p class="question">Your favorite occupation/hobby</p>
											<p class="answer"><?php the_field('occupation'); ?></p>
										</li>
										<li>
											<p class="question">If not yourself, who would you be?</p>
											<p class="answer"><?php the_field('who'); ?></p>
										</li>
										<li>
											<p class="question">The natural talent you would like to be gifted with?</p>
											<p class="answer"><?php the_field('talen'); ?></p>
										</li>
										<li>
											<p class="question">Your favorite motto</p>
											<p class="answer"><?php the_field('motto'); ?></p>
										</li>
									</ul>
								</section>

								<?php comments_template(); ?>

							</article>


						<?php endwhile; ?>

						<?php bones_page_navi(); ?>
						<?php 
                    	  	$wp_query = null; 
                    	  	$wp_query = $temp; 
                    	?>
					</main>

					<?php get_sidebar(); ?>

				</div>

			</div>


<?php get_footer(); ?>
