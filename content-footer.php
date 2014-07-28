<?php
/**
 * The template for displaying article footers
 *
 * @since 1.0.6
 */
 ?>
	<footer class="clearfix">
	    <?php
	    if ( is_single() ) wp_link_pages( array( 'before' => '<p id="pages">' . __( 'Pages:', 'arcade' ) ) );
	    edit_post_link( __( '(edit)', 'arcade' ), '<p class="edit-link">', '</p>' );
		if ( is_single() ) the_tags( '<p class="tags"><i class="fa fa-tags"></i> <span>' . __( 'Tags:', 'arcade' ) . '</span>', ' ', '</p>' );
	    ?>
	</footer><!-- .entry -->