<?php
  
  get_header();

  while(have_posts()) {
    the_post(); 
    pageBanner();
    ?>

    <div class="container container--narrow page-section">
          <div class="metabox metabox--position-up metabox--with-home-link">
        <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('beer_type'); ?>"><i class="fa fa-home" aria-hidden="true"></i> Our Beer Types</a> <span class="metabox__main"><?php the_title(); ?></span></p>
      </div>

      <div class="generic-content"><?php the_field('main_body_content'); ?></div>

      <?php 
        $relatedBeers = new WP_Query(array(
          'posts_per_page' => -1,
          'post_type' => 'beer',
          'orderby' => 'title',
          'order' => 'ASC',
          'meta_query' => array(
            array(
              'key' => 'related_beer_types',
              'compare' => 'LIKE',
              'value' => '"' . get_the_ID() . '"'
            )
          )
        ));

        if ($relatedBeers->have_posts()) {
          echo '<hr class="section-break">';
        echo '<h2 class="headline headline--medium">' . get_the_title() . ' Beers</h2>';

        echo '<ul class="beer-cards">';
        while($relatedBeers->have_posts()) {
          $relatedBeers->the_post(); ?>
          <li class="beer-card__list-item">
            <a class="beer-card" href="<?php the_permalink(); ?>">
              <img class="beer-card__image" src="<?php the_post_thumbnail_url(); ?>">
              <span class="beer-card__name"><?php the_title(); ?></span>
            </a>
          </li>
        <?php }
        echo '</ul>';
        }

        wp_reset_postdata();

        $today = date('Ymd');
        $homepageEvents = new WP_Query(array(
          'posts_per_page' => 2,
          'post_type' => 'event',
          'meta_key' => 'event_date',
          'orderby' => 'meta_value_num',
          'order' => 'ASC',
          'meta_query' => array(
            array(
              'key' => 'event_date',
              'compare' => '>=',
              'value' => $today,
              'type' => 'numeric'
            ),
            array(
              'key' => 'related_beer_types',
              'compare' => 'LIKE',
              'value' => '"' . get_the_ID() . '"'
            )
          )
        ));

        if ($homepageEvents->have_posts()) {
          echo '<hr class="section-break">';
        echo '<h2 class="headline headline--medium">Upcoming ' . get_the_title() . ' Events</h2>';

        while($homepageEvents->have_posts()) {
          $homepageEvents->the_post(); 
          get_template_part('template-parts/content', 'event');
        }
        }

        wp_reset_postdata();
        $relatedBrewPubs = get_field('related_brewpub');

        if ($relatedBrewPubs) {
          echo '<hr class="section-break">';
          echo '<h2 class="headline headline--medium">' . get_the_title() . ' is available at these brewPubs:</h2>';

          echo '<ul class="min-list link-list">';
          foreach($relatedBrewPubs as $brewPub) { 
            ?><li><a href="<?php echo get_the_permalink($brewPub); ?>"><?php echo get_the_title($brewPub) ?></a></li> <?php
          }
        echo '</ul>';
        }

      ?>

    </div>
    

    
  <?php }

  get_footer();

?>