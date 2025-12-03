<div class="post-item">
  <li class="beer-card__list-item">
    <a class="beer-card" href="<?php the_permalink(); ?>">
      <img class="beer-card__image" src="<?php the_post_thumbnail_url('beerCan') ?>">
      <span class="beer-card__name"><?php the_title(); ?></span>
    </a>
  </li>
</div>