<?php

add_action('rest_api_init', 'breweryRegisterSearch');

function breweryRegisterSearch() {
  register_rest_route('brewery/v1', 'search', array(
    'methods' => WP_REST_SERVER::READABLE,
    'callback' => 'brewerySearchResults'
  ));
}

function brewerySearchResults($data) {
  $mainQuery = new WP_Query(array(
    'post_type' => array('post', 'page', 'beer', 'beer_type', 'brew_pub', 'event'),
    's' => sanitize_text_field($data['term'])
  ));

  $results = array(
    'generalInfo' => array(),
    'beers' => array(),
    'beer_types' => array(),
    'events' => array(),
    'brew_pubs' => array()
  );

  while($mainQuery->have_posts()) {
    $mainQuery->the_post();

    if (get_post_type() == 'post' OR get_post_type() == 'page') {
      array_push($results['generalInfo'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
        'postType' => get_post_type(),
        'authorName' => get_the_author()
      ));
    }

    if (get_post_type() == 'beer') {
      array_push($results['beers'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
        'image' => get_the_post_thumbnail_url(0, 'beerCan')
      ));
    }

    if (get_post_type() == 'beer_type') {
      $relatedBrewpubs  = get_field('related_brewpub');

      if ($relatedBrewpubs ) {
        foreach($relatedBrewpubs as $brewpub) {
          array_push($results['brew_pubs'], array(
            'title' => get_the_title($brewpub),
            'permalink' => get_the_permalink($brewpub)
          ));
        }
      }
    
      array_push($results['beer_types'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
        'id' => get_the_id()
      ));
    }

    if (get_post_type() == 'brew_pub') {
      array_push($results['brew_pubs'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink()
      ));
    }

    if (get_post_type() == 'event') {
      $eventDate = new DateTime(get_field('event_date'));
      $description = null;
      if (has_excerpt()) {
        $description = get_the_excerpt();
      } else {
        $description = wp_trim_words(get_the_content(), 18);
      }

      array_push($results['events'], array(
        'title' => get_the_title(),
        'permalink' => get_the_permalink(),
        'month' => $eventDate->format('M'),
        'day' => $eventDate->format('d'),
        'description' => $description
      ));
    }
    
  }

  if ($results['beer_types']) {
    $beertypesMetaQuery = array('relation' => 'OR');

    foreach($results['beer_types'] as $item) {
      array_push($beertypesMetaQuery, array(
          'key' => 'related_beer_types',
          'compare' => 'LIKE',
          'value' => '"' . $item['id'] . '"'
        ));
    }

    $beertypeRelationshipQuery = new WP_Query(array(
      'post_type' => array('beer', 'event'),
      'meta_query' => $beertypesMetaQuery
    ));

    while($beertypeRelationshipQuery->have_posts()) {
      $beertypeRelationshipQuery->the_post();

      if (get_post_type() == 'event') {
        $eventDate = new DateTime(get_field('event_date'));
        $description = null;
        if (has_excerpt()) {
          $description = get_the_excerpt();
        } else {
          $description = wp_trim_words(get_the_content(), 18);
        }

        array_push($results['events'], array(
          'title' => get_the_title(),
          'permalink' => get_the_permalink(),
          'month' => $eventDate->format('M'),
          'day' => $eventDate->format('d'),
          'description' => $description
        ));
      }

      if (get_post_type() == 'beer') {
        array_push($results['beers'], array(
          'title' => get_the_title(),
          'permalink' => get_the_permalink(),
          'image' => get_the_post_thumbnail_url(0, 'beerCan')
        ));
      }

    }

    $results['beers'] = array_values(array_unique($results['beers'], SORT_REGULAR));
    $results['events'] = array_values(array_unique($results['events'], SORT_REGULAR));
  }


  return $results;

}