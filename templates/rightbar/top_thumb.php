<header class="heading">
    <h3 class="heading__title"><?= $title; ?></h3>
</header>
<div class="top-items">
    <div class="top-items__wrapper">
        <?php $loop =0; while ($query->have_posts()) : $query->the_post(); $loop++; ?>
            <div class="item">
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="item__thumbnail"> 
                    <img data-src="<?= op_get_poster_url() ?>" alt="<?php the_title_attribute(); ?>" class="lazyload"> 
                    <div class="item__labels">
                        <span><?= op_get_lang() ?></span>
                    </div>
                </a>
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="item__title">
                    <h4 class="item__title">
                        <?php the_title(); ?>
                    </h4>
                </a>
            </div>
        <?php endwhile; ?>
    </div>
</div>