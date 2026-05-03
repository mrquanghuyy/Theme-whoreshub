<?php
get_header();
?>

<?php
if (!isset($_GET['filter'])){
    $_GET['filter']['categories'] ='';
    $_GET['filter']['genres'] ='';
    $_GET['filter']['regions'] ='';
    $_GET['filter']['years'] ='';
}
?>
<div class="content">
    <div class="section">
        <div class="container">
            <div class="block-thumbs">
                <div class="headline">
                    <h1 class="title"><span>Videos </span> For: <?= get_search_query() ?></h1>
                    
                </div>

                <div class="thumbs">
                    <?php 
                    $key = 0; 
                    if (have_posts()) : 
                        while (have_posts()) : the_post(); 
                            $key++;
                            get_template_part('templates/section/section_thumb_item');
                        endwhile; 
                    else: 
                        echo '<p style="text-align:center;width:100%;color:#888;">No videos found in this sequence.</p>';
                    endif; 
                    ?>
                </div>

                <?php 
                if (function_exists('ophim_pagination1')) {
                    ophim_pagination1();
                } elseif (function_exists('ophim_pagination')) {
                    ophim_pagination();
                } else {
                    the_posts_pagination();
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php
get_footer();
?>
