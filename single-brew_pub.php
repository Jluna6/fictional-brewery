<?php
  
  get_header();

  while(have_posts()) {
    the_post(); 
    pageBanner();
    ?>

    <div class="container container--narrow page-section">
          <div class="metabox metabox--position-up metabox--with-home-link">
        <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('brew_pub'); ?>"><i class="fa fa-home" aria-hidden="true"></i> Our brewPubs</a> <span class="metabox__main"><?php the_title(); ?></span></p>
      </div>

      <div class="generic-content"><?php the_content(); ?>
      <div class="acf-map">
        <?php
          $mapLocation = get_field('map_location');
        ?>
        <div class="marker" data-lat="<?php echo $mapLocation['lat'] ?>" data-lng="<?php 
        echo $mapLocation['lng']; ?>">
          <h3><?php the_title(); ?></h3>
          <?php echo $mapLocation['address']; ?>
        </div>
      </div>

      <?php 
        $relatedBeerTypes = new WP_Query(array(
          'posts_per_page' => -1,
          'post_type' => 'beer_type',
          'orderby' => 'title',
          'order' => 'ASC',
          'meta_query' => array(
            array(
              'key' => 'related_brewpub',
              'compare' => 'LIKE',
              'value' => '"' . get_the_ID() . '"'
            )
          )
        ));

        if ($relatedBeerTypes->have_posts()) {
          echo '<hr class="section-break">';
        echo '<h2 class="headline headline--medium">Beer Types Available at This BrewPub</h2>';

        echo '<ul class="min-list link-list">';
        while($relatedBeerTypes->have_posts()) {
          $relatedBeerTypes->the_post(); ?>
          <li>
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </li>
        <?php }
        echo '</ul>';
        }

        wp_reset_postdata();

        // $today = date('Ymd');
        // $homepageEvents = new WP_Query(array(
        //   'posts_per_page' => 2,
        //   'post_type' => 'event',
        //   'meta_key' => 'event_date',
        //   'orderby' => 'meta_value_num',
        //   'order' => 'ASC',
        //   'meta_query' => array(
        //     array(
        //       'key' => 'event_date',
        //       'compare' => '>=',
        //       'value' => $today,
        //       'type' => 'numeric'
        //     ),
        //     array(
        //       'key' => 'related_beer_types',
        //       'compare' => 'LIKE',
        //       'value' => '"' . get_the_ID() . '"'
        //     )
        //   )
        // ));

        // if ($homepageEvents->have_posts()) {
        //   echo '<hr class="section-break">';
        // echo '<h2 class="headline headline--medium">Upcoming ' . get_the_title() . ' Events</h2>';

        // while($homepageEvents->have_posts()) {
        //   $homepageEvents->the_post(); 
        //   get_template_part('template-parts/content', 'event');
        // }
        // }

        
      ?>

    </div>
    

    
  <?php }

  get_footer();

?>