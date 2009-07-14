<?php get_header() ?>

<div class="content-header">
	<?php bp_last_activity() ?>
</div>

<div id="content" class="vcard">
	<?php do_action( 'template_notices' ) // (error/success feedback) ?>
	
	<div class="left-menu">
		<!-- Profile Menu (Avatar, Add Friend, Send Message buttons etc) -->
		<?php load_template( TEMPLATEPATH . '/profile/profile-menu.php' ) ?>
	</div>

	<div class="main-column">
		<div class="inner-tube">
			
			<!-- Profile Header (Name & Status) -->
			<?php load_template( TEMPLATEPATH . '/profile/profile-header.php' ) ?>
		
			<!-- Profile Data Loop -->
			<?php load_template( TEMPLATEPATH . '/profile/profile-loop.php' ) ?>
			
			<!-- Latest Activity Loop -->
			<?php if ( function_exists( 'bp_activity_install')) : ?>
			<div class="info-group">
				<h4><?php echo bp_word_or_name( __( "My Latest Activity", 'buddypress' ), __( "%s's Latest Activity", 'buddypress' ), true, false ) ?> <a href="<?php echo bp_displayed_user_domain() . BP_ACTIVITY_SLUG ?>"><?php _e( 'See All', 'buddypress' ) ?> &rarr;</a></h4>

				<?php if ( bp_has_activities( 'type=personal&max=5' ) ) : ?>

					<div id="activity-rss">
						<p><a href="<?php bp_activities_member_rss_link() ?>" title="<?php _e( 'RSS Feed', 'buddypress' ) ?>"><?php _e( 'RSS Feed', 'buddypress' ) ?></a></p>
					</div>

					<ul id="activity-list">
					<?php while ( bp_activities() ) : bp_the_activity(); ?>
						<li class="<?php bp_activity_css_class() ?>">
							<?php bp_activity_content() ?>
						</li>
					<?php endwhile; ?>
					</ul>

				<?php else: ?>

					<div id="message" class="info">
						<p><?php echo bp_word_or_name( __( "You haven't done anything yet.", 'buddypress' ), __( "%s hasn't done anything yet.", 'buddypress' ), true, false ) ?></p>
					</div>

				<?php endif;?>
			</div>
			<?php endif; ?>
		
			<!-- Random Groups Loop -->
			<?php if ( function_exists( 'bp_has_groups' ) ) : ?>

				<?php if ( bp_has_groups( 'type=random&max=5' ) ) : ?>
					<div class="info-group">
						<h4><?php bp_word_or_name( __( "My Groups", 'buddypress' ), __( "%s's Groups", 'buddypress' ) ) ?> (<?php bp_group_total_for_member() ?>) <a href="<?php echo bp_displayed_user_domain() . BP_GROUPS_SLUG ?>"><?php _e( 'See All', 'buddypress' ) ?> &rarr;</a></h4>
						
						<ul class="horiz-gallery">
						<?php while ( bp_groups() ) : bp_the_group(); ?>
							<li>
								<a href="<?php bp_group_permalink() ?>"><?php bp_group_avatar_thumb() ?></a>
								<h5><a href="<?php bp_group_permalink() ?>"><?php bp_group_name() ?></a></h5>
							</li>
						<?php endwhile; ?>
						</ul>
					
					<div class="clear"></div>	
					</div>
					
				<?php endif; ?>
					
			<?php endif; ?>

			<!-- Random Friends Loop -->
			<?php if ( function_exists( 'bp_has_friendships' ) ) : ?>

				<?php if ( bp_has_friendships( 'type=random&max=5' ) ) : ?>
					<div class="info-group">
						<h4><?php bp_word_or_name( __( "My Friends", 'buddypress' ), __( "%s's Friends", 'buddypress' ) ) ?> (<?php bp_friend_total_for_member() ?>) <a href="<?php echo bp_displayed_user_domain() . BP_FRIENDS_SLUG ?>"><?php _e( 'See All', 'buddypress' ) ?> &rarr;</a></h4>
						
						<ul class="horiz-gallery">
						<?php while ( bp_user_friendships() ) : bp_the_friendship(); ?>
							<li>
								<a href="<?php bp_friend_url() ?>"><?php bp_friend_avatar_thumb() ?></a>
								<h5><a href="<?php bp_friend_url() ?>"><?php bp_friend_name() ?></a></h5>
							</li>
						<?php endwhile; ?>
						</ul>
					
					<div class="clear"></div>	
					</div>
					
				<?php endif; ?>
					
			<?php endif; ?>
			
			<!-- Hook for including new profile boxes -->
			<?php do_action( 'bp_custom_profile_boxes' ) ?>

			<!-- Profile Wire Loop - uses [TEMPLATEPATH]/wire/post-list.php -->
			<?php if ( function_exists('bp_wire_get_post_list') ) : ?>
				<?php bp_wire_get_post_list( bp_current_user_id(), bp_word_or_name( __( "My Wire", 'buddypress' ), __( "%s's Wire", 'buddypress' ), true, false ), bp_word_or_name( __( "No one has posted to your wire yet.", 'buddypress' ), __( "No one has posted to %s's wire yet.", 'buddypress' ), true, false), bp_profile_wire_can_post() ) ?>
			<?php endif; ?>
			
		</div>
	</div>

</div>

<?php get_footer() ?>