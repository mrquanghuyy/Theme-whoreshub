<div class="section">
    <div class="container">
        <div class="block-thumbs">
            <div class="headline"><h1 class="title"><span><?= $title ?></span></h1></div>
            <div class="thumbs">
                <?php $key =0; while ($query->have_posts()) : $query->the_post(); $key++;
                    get_template_part('templates/section/section_thumb_item');
                endwhile; ?>
            </div>
        </div>
    </div>
</div>