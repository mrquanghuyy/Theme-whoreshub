<header class="heading">
    <h3 class="heading__title"><?= $title; ?></h3>
</header>
<ul class="list-disc pl-10">
    <?php $loop =0; while ($query->have_posts()) : $query->the_post(); $loop++; ?>
        <li>
            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"> 
                <?php the_title(); ?>
            </a>
        </li>
    <?php endwhile; ?>
</ul>