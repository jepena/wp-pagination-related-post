<?php

/* 
* 
* PAGINATION 
*
*/

function j_pagination($query = false) {
    
    if(!$query) {
		$query = $GLOBALS['wp_query'];
	}
	if($query->max_num_pages < 2) {
		return;
	}

	$paged		= (get_query_var('paged')) ? get_query_var('paged') : ((get_query_var('page')) ? get_query_var('page') : 1);
	$pagenum_link = html_entity_decode(get_pagenum_link());
	$query_args   = array();
	$url_parts	= explode('?', $pagenum_link);

	if(isset($url_parts[1])) {
		wp_parse_str($url_parts[1], $query_args);
	}

	$pagenum_link = remove_query_arg(array_keys($query_args), $pagenum_link);
	$pagenum_link = trailingslashit($pagenum_link) . '%_%';

	$format  = $GLOBALS['wp_rewrite']->using_index_permalinks() && ! strpos($pagenum_link, 'index.php') ? 'index.php/' : '';
	$format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit('page/%#%', 'paged') : '?paged=%#%';

	// Set up paginated links.
	$links = paginate_links(array(
		'base'	 => $pagenum_link,
		'format'   => $format,
		'total'	=> $query->max_num_pages,
		'current'  => $paged,
		'mid_size' => 1,
		'add_args' => array_map('urlencode', $query_args),
		'prev_text' => 'Prev',
		'next_text' => 'Next',
	));

	if($links) :
	?>
	<div class="e_pagination">
	    <div class="e_pagination-links">
		    <?php echo $links; ?>
	    </div>
	</div><!-- .pagination -->
	<?php
	endif;
}
/* 
* 
* PAGINATION INNER PAGE OF POST
*
*/

function j_inner_pagination(){
    $next_post = get_next_post();
    $prev_post = get_previous_post();
    
    if ( $next_post || $prev_post ) {
    
    	$pagination_classes = '';
    
    	if ( ! $next_post ) {
    		$pagination_classes = ' only-one only-prev';
    	} elseif ( ! $prev_post ) {
    		$pagination_classes = ' only-one only-next';
    	}
      ?>
      <div class="j_next-post">
    <?php
      if ( $next_post ) {
        ?>
        <a class="next-post" href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>">
          <span class="arrow" aria-hidden="true"><i class="fas fa-chevron-left"></i></span>
          <span class="title"><?php echo wp_kses_post( get_the_title( $next_post->ID ) ); ?></span>
        </a>
        <?php
      }
      ?>
      </div>
      
      <div class="j_prev-post">
      <?php
      if ( $prev_post ) {
        ?>
        <a class="previous-post" href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>">
          <span class="title"><?php echo wp_kses_post( get_the_title( $prev_post->ID ) ); ?></span>
          <span class="arrow" aria-hidden="true"><i class="fas fa-chevron-right"></i></span>
        </a>
        <?php
      }
    ?>
      </div>
    	<?php
    }
}
/* 
* 
* RELATED POST ON INNER PAGE 
*
*/
function j_related_post() {
    
    $args = array(
        'post_type'         => 'post',
        'post_status'       => 'publish',
        'posts_per_page'    => 3,
        'orderby'           => 'date',
        'order'             => 'DESC',
        'post__not_in'      => array(get_the_ID())
        
    );
    
    $the_query = new WP_Query($args);
    
    while($the_query->have_posts()) { $the_query->the_post();
        $post_content = strip_tags(do_shortcode(get_the_content()));
        $post_content = preg_replace( "/\[(\/*)?vc_(.*?)\]/", '', $post_content); // apply this if using vc page builder
    ?>
    <div class="col-md-4">
        <article>
            <figure class="article-featured-image">        
                <a href="<?php echo get_the_permalink(); ?>" class="article-postlink-on-img"><?php the_post_thumbnail(); ?></a>
            </figure>
            <div class="article-content-holder">
                <div class="article-content">
                   <h2><?php the_title(); ?></h2>
                   <p><?php echo wp_trim_words( $post_content, '15', '...' ); ?></p>
                </div>
                <hr>
                <div class="article-widgets-holder">
                    <div class="article-link"><a href="<?php echo esc_url(get_the_permalink()); ?>">Lire la suite <span><img src="<?php echo get_template_directory_uri() . '/dist/images/arrow.png' ?>"></span></a></div>
                    
                    <div>
                        <span></span>
                        <span><?php echo get_the_date('m/d/Y'); ?></span>
                    </div>
                    
                </div>
            </div>
        </article>
    </div>
    <?php
    }
    
    wp_reset_postdata();
    
    
}

/* 
* 
* PAGINATION INNER PAGE OF POST WITH POST THUMBNAIL
*
*/
function j_inner_pagination_thumbnail(){
    $next_post = get_next_post();
    $prev_post = get_previous_post();
    
    if ( $next_post || $prev_post ) {
    
    	$pagination_classes = '';
    
    	if ( ! $next_post ) {
    		$pagination_classes = ' only-one only-prev';
    	} elseif ( ! $prev_post ) {
    		$pagination_classes = ' only-one only-next';
    	}
      ?>

      
    <div class="j_prev-post">
      <?php
      if ( $prev_post ) {
        ?>
        <a class="previous-post" href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>">
            <?php if (has_post_thumbnail($prev_post->ID)): ?>
                <div class="pagination-post-thumbnail">
                  <span class="arrow pl-3 pr-1" aria-hidden="true"><i class="fas fa-chevron-left"></i></span>
                  <span class="title px-2"><?php echo wp_kses_post( get_the_title( $prev_post->ID ) ); ?></span>
                  <figure>  
                    <?php echo get_the_post_thumbnail( $prev_post->ID, 'full' ); ?>
                  </figure>
                </div>
              <?php else: ?>
                <span class="arrow pr-1" aria-hidden="true"><i class="fas fa-chevron-left"></i></span>
                <span class="title px-2"><?php echo wp_kses_post( get_the_title( $prev_post->ID ) ); ?></span>
            <?php endif ?>
        </a>
        <?php
      }
    ?>
    </div>
      
    <div class="j_next-post">
    <?php
      if ( $next_post ) {
        ?>
        <a class="next-post" href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>">
            <?php if (has_post_thumbnail($next_post->ID)): ?>
                <div class="pagination-post-thumbnail">
                  <figure>  
                    <?php echo get_the_post_thumbnail( $next_post->ID, 'full' ); ?>
                  </figure>
                  <span class="title px-2"><?php echo wp_kses_post( get_the_title( $next_post->ID ) ); ?></span>
                  <span class="arrow pl-1 pr-3" aria-hidden="true"><i class="fas fa-chevron-right"></i></span>
                </div>
              <?php else: ?>
                <span class="title px-2"><?php echo wp_kses_post( get_the_title( $next_post->ID ) ); ?></span>
                <span class="arrow pl-1" aria-hidden="true"><i class="fas fa-chevron-right"></i></span>
          <?php endif ?>
        </a>
        <?php
      }
      ?>
      </div>
    	<?php
    }
}
