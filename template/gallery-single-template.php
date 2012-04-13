<?php get_header(); ?>
	<div id="container">
		<div role="main" id="content">
			<?php 
			global $post;
			$args = array(
				'post_type'					=> 'gallery',
				'post_status'				=> 'publish',
				'name'							=> substr(basename( $_SERVER['REQUEST_URI'] ), strpos( basename( $_SERVER['REQUEST_URI'] ), "=")),
				'posts_per_page'		=> 1
			);	
			$second_query = new WP_Query( $args ); 
			$gllr_options = get_option( 'gllr_options' );
			if ($second_query->have_posts()) : while ($second_query->have_posts()) : $second_query->the_post(); ?>
				<h1 class="home_page_title"><?php the_title(); ?></h1>
				<div class="gallery_box_single">
					<?php the_content(); 
					$posts = get_posts(array(
						"showposts"			=> -1,
						"what_to_show"	=> "posts",
						"post_status"		=> "inherit",
						"post_type"			=> "attachment",
						"orderby"				=> "post_date",
						"order"					=> "ASC",
						"post_mime_type"=> "image/jpeg,image/gif,image/jpg,image/png",
						"post_parent"		=> $post->ID
					));
					if( count( $posts ) > 0 ) {
						$count_image_block = 0; ?>
						<div class="gallery clearfix">
							<?php foreach( $posts as $attachment ) { 
								$key = "gllr_image_text";
								$image_attributes = wp_get_attachment_image_src( $attachment->ID, 'photo-thumb' );
								$image_attributes_large = wp_get_attachment_image_src( $attachment->ID, 'large' );
								if( $count_image_block % $gllr_options['custom_image_row_count'] == 0 ) { ?>
								<div class="gllr_image_row">
								<?php } ?>
									<div class="gllr_image_block">
										<p style="width:<?php echo $gllr_options['gllr_custom_size_px'][1][0]+20; ?>px;height:<?php echo $gllr_options['gllr_custom_size_px'][1][1]+20; ?>px;">
											<a rel="gallery_fancybox" href="<?php echo $image_attributes_large[0]; ?>" title="<?php echo get_post_meta( $attachment->ID, $key, true ); ?>">
												<img style="width:<?php echo $gllr_options['gllr_custom_size_px'][1][0]; ?>px;height:<?php echo $gllr_options['gllr_custom_size_px'][1][1]; ?>px;" alt="" title="<?php echo get_post_meta( $attachment->ID, $key, true ); ?>" src="<?php echo $image_attributes[0]; ?>" />
											</a>
										</p>
										<div  style="width:<?php echo $gllr_options['gllr_custom_size_px'][1][0]+20; ?>px;" class="gllr_single_image_text"><?php echo get_post_meta( $attachment->ID, $key, true ); ?>&nbsp;</div>
									</div>
								<?php if($count_image_block%$gllr_options['custom_image_row_count'] == $gllr_options['custom_image_row_count']-1 ) { ?>
								</div>
								<?php } 
								$count_image_block++; 
							} 
							if($count_image_block > 0 && $count_image_block%$gllr_options['custom_image_row_count'] != 0) { ?>
								</div>
							<?php } ?>
							</div>
						<?php } ?>
					</div>
					<div class="clear"></div>
				<?php endwhile; else: ?>
				<div class="gallery_box_single">
					<p class="not_found"><?php _e('Sorry - nothing to found.', 'gallery'); ?></p>
				</div>
				<?php endif; ?>
			</div>
		</div>
	<?php get_sidebar(); ?>
	<script type="text/javascript">
		(function($){
			$(document).ready(function(){
				$("a[rel=gallery_fancybox]").fancybox({
					'transitionIn'		: 'elastic',
					'transitionOut'		: 'elastic',
					'titlePosition' 	: 'inside',
					'speedIn'					:	500, 
					'speedOut'				:	300;
					}
				});
			});
		})(jQuery);
	</script>
<?php get_footer(); ?>