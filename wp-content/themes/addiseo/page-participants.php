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
						<?php $paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1; ?>

						<?php // Define the query
					    	$args = array(
						        'post_type' => 'participant',
						        'posts_per_page' => 5,
								'paged' => $paged
						    );

						    $query = new WP_Query( $args );
		    			?>

			        	<?php while ( $query->have_posts() ) : $query->the_post(); ?>

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

						</main>

						<?php get_sidebar(); ?>

				</div>

			</div>


<?php get_footer(); ?>
