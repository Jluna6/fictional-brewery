<?php
  
  get_header();

  while(have_posts()) {
    the_post(); 
    pageBanner();
    ?>
    
    <div class="container container--narrow page-section">
          

      <div class="generic-content">
        <div class="row group">
          <div class="one-third">
            <?php the_post_thumbnail(); ?>
          </div>
          <div class="two-thirds">
            <?php
              $likeCount = new WP_Query(array(
                'post_type' => 'like',
                'meta_query' => array(
                  array(
                    'key' => 'liked_beer_id',
                    'compare' => '=',
                    'value' => get_the_ID()
                  )
                )
              ));

              $existStatus = 'no';

              if (is_user_logged_in()) {
                $existQuery = new WP_Query(array(
                  'author' => get_current_user_id(),
                  'post_type' => 'like',
                  'meta_query' => array(
                    array(
                      'key' => 'liked_beer_id',
                      'compare' => '=',
                      'value' => get_the_ID()
                    )
                  )
                ));

                if ($existQuery->found_posts) {
                  $existStatus = 'yes';
                }
              } ?>
            <span class="like-box">
              <i class="fa fa-heart-o" aria-hidden="true"></i>
              <i class="fa fa-heart" aria-hidden="true"></i>
              <span class="like-count"><?php echo $likeCount->found_posts; ?></span>
            </span>
            
            <?php the_content(); ?>
          </div>
      </div>
        
      <?php

        $relatedBeerTypes = get_field('related_beer_types');

        if ($relatedBeerTypes) {
          echo '<hr class="section-break">';
          echo '<h2 class="headline headline--medium">Our Beer Types(s)</h2>';
          echo '<ul class="link-list min-list">';
          foreach($relatedBeerTypes as $beerType) { ?>
            <li><a href="<?php echo get_the_permalink($beerType); ?>"><?php echo get_the_title($beerType); ?></a></li>
          <?php }
          echo '</ul>';
        }

      ?>

    </div>
    

    
  <?php }

  get_footer();

?>