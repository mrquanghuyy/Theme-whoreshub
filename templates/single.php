<?php
$actors_list = get_the_terms(get_the_ID(), 'ophim_actors');
$genres = get_the_terms(get_the_ID(), 'ophim_genres');
$tags = get_the_terms(get_the_ID(), 'ophim_tags');
$primary_actor = (!empty($actors_list) && !is_wp_error($actors_list)) ? $actors_list[0] : null;
$getslug = get_option('ophim_slug_actors');
$actor_slug = $getslug ? $getslug : 'actors';
?>
<div class="content">
    <div class="section">
        <div class="container">
            <div class="top-cols">
                <div class="col">
                    <div class="player-holder">
                        <div class="player">
                            <?php $first_episode = get_first_episode_info(); if ($first_episode): ?>
                                <?php if (!empty($first_episode['link_m3u8'])): ?>
                                    <button style="display:none;" data-id="<?= $first_episode['server_key'] ?>" data-link="<?= $first_episode['link_m3u8'] ?>" data-type="m3u8" onclick="chooseStreamingServer(this)" class="pu-link player__cdn set-player-source">Server 1</button>
                                <?php elseif (!empty($first_episode['link_embed'])): ?>
                                    <button style="display:none;" data-id="<?= $first_episode['server_key'] ?>" data-link="<?= $first_episode['link_embed'] ?>" data-type="embed" onclick="chooseStreamingServer(this)" class="pu-link player__cdn set-player-source">Server 2</button>
                                <?php endif; ?>
                            <?php endif; ?>
                            <div class="player-wrap" style="width: 100%; height: 0; padding-bottom: 56.338028169014%">
                                <div id="kt_player" class="loaded kt_player_dev embet-player-test kt-player is-no-touch is-splash is-paused is-mouseout" style="width: 100%; height: 100%; position: relative; background-image: none; visibility: visible;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bottom-spot"></div>
                    <div class="video-info">
                        <div class="video-title">
                            <h1 class="title"><?= get_the_title() ?></h1>
                        </div>
                        <div class="info-buttons">
                            <div class="wrap-buttons">
                                <ul class="tab-buttons">
                                    <li class="item">
                                        <div class="rating-container">
                                            <div class="likes">
                                                
                                                <a href="#like" class="rate-like like" title="I like this video" data-video-id="114545" data-vote="5">
                                                    <svg class="svg-icon"><use xlink:href="#icon-like"></use></svg>
                                                    <span class="value">0</span>
                                                </a>
                                                <a href="#dislike" class="rate-dislike dislike" title="I don't like this video" data-video-id="114545" data-vote="0">
                                                    <svg class="svg-icon"><use xlink:href="#icon-dislike"></use></svg>
                                                    <span class="value">0</span>
                                                </a>
                                                <span class="voters" data-success="Thank you!" data-error="IP already voted"></span>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="item active">
                                        <a href="#tab1" class="btn js-tab">
                                            <svg class="svg-icon"><use xlink:href="#icon-info"></use></svg>
                                            <span class="text">Info</span>
                                        </a>
                                    </li>

                                    <li class="item">
                                        <a href="#tab5" class="btn js-tab">
                                            <svg class="svg-icon"><use xlink:href="#icon-img"></use></svg>
                                            <span class="text">Screenshots</span>
                                        </a>
                                    </li>

                                    <li class="item">
                                        <a href="#tab3" class="btn js-tab">
                                            <svg class="svg-icon"><use xlink:href="#icon-share"></use></svg>
                                            <span class="text">Share</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="tab-content">
                            <div class="tab-box" id="tab1" style="display: block;">
                                <button aria-label="button" type="button" class="toggle-info" href="#">
                                    <svg class="svg-icon arrow">
                                        <use xlink:href="#icon-arrow-down"></use>
                                    </svg>
                                </button>
                                <ul class="list-info">
                                    <li class="wrap">
                                        <svg class="svg-icon">
                                            <use xlink:href="#icon-duration"></use>
                                        </svg>
                                        <div class="value"><?= op_get_runtime() ?></div>
                                    </li>
                                    <li class="wrap">
                                        <svg class="svg-icon">
                                            <use xlink:href="#icon-view"></use>
                                        </svg>
                                        <div class="value"><?= op_get_post_view() ?></div>
                                    </li>
                                    <li class="wrap">
                                        <svg class="svg-icon second">
                                            <use xlink:href="#icon-calendar"></use>
                                        </svg>
                                        <div class="value"><?= human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ' . __('ago'); ?></div>
                                    </li>
                                </ul>
                                <?php if ($primary_actor): ?>
                                <div class="info-top">
                                    <a href="<?= esc_url(get_term_link($primary_actor)) ?>" class="img-holder avatar user-photo">
                                        <img src="<?= esc_url(op_get_actor_image($primary_actor->term_id)) ?>" alt="<?= esc_attr($primary_actor->name) ?>">
                                    </a>
                                    <div class="user-info">
                                        <a href="<?= esc_url(get_term_link($primary_actor)) ?>" class="username name-link user-link">
                                            <?= esc_html($primary_actor->name) ?>
                                        </a>
                                        <div class="subscribers">
                                            <span class="value"><?= (int)$primary_actor->count ?></span>
                                            <span class="text">Videos</span>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <div class="info-wrap">
                                    <div class="text text-description"><?= the_content() ?></div>
                                    <div class="tags-outer">
                                        <ul class="tags-list">
                                            <li class="item">
                                                <h4 class="name">Categories:</h4>
                                            </li>
                                            <?php
                                            foreach ($genres as $genre):
                                            ?>
                                            <li class="item">
                                                <a href="<?= get_term_link($genre) ?>" class="btn"><?= $genre->name ?></a>
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <div class="tags-outer">
                                        <ul class="tags-list">
                                            <li class="item">
                                                <h4 class="name">Tags:</h4>
                                            </li>
                                            <?php
                                            foreach ($tags as $tag):
                                            ?>
                                            <li class="item">
                                                <a href="<?= get_term_link($tag) ?>" class="btn"><?= $tag->name ?></a>
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-box" id="tab3" style="display: none;">
                                <div class="box">
                                    <form>
                                    <input class="input" type="text" readonly="readonly" value="<?= the_permalink() ?>">
                                    </form>
                                </div>
                            </div>

                            <div id="tab5" class="tab-box" style="">
                                <div class="thumbs block-screenshots">
                                    <?php 
                                    $posters = function_exists('op_get_preview_images') ? op_get_preview_images() : array();
                                    if (!empty($posters) && is_array($posters)): 
                                        foreach ($posters as $poster):
                                    ?>
                                    <div class="thumb">
                                        <div class="box">
                                            <a href="<?= esc_url($poster) ?>" class="item" rel="screenshots" data-fancybox-type="image">
                                                <span class="thumb-img">
                                                    <img class="img lazyload" src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7" data-src="<?= esc_url($poster) ?>" width="320" height="180" alt="<?= esc_attr(get_the_title()) ?>">
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                    <?php endforeach;
                                    endif; ?>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="container">
            <div class="headline">
			    <h2 class="title"><span>Related</span> Videos</h2>
		    </div>

            <div class="block-thumbs">
                <div class="thumbs">
                    <?php
                        $postType = 'ophim';
                        $taxonomyName = 'ophim_genres';
                        $taxonomy = get_the_terms(get_the_id(), $taxonomyName);
                        if ($taxonomy) {
                            $category_ids = array();
                            foreach ($taxonomy as $individual_category) $category_ids[] = $individual_category->term_id;
                            $args = array('post_type' => $postType, 'post__not_in' => array(get_the_id()), 'posts_per_page' => 10, 'tax_query' => array(array('taxonomy' => $taxonomyName, 'field' => 'term_id', 'terms' => $category_ids,),));
                            $my_query = new wp_query($args);

                            if ($my_query->have_posts()):
                                while ($my_query->have_posts()):$my_query->the_post(); ?>
                                    <?php get_template_part('templates/section/section_thumb_item'); ?>
                                <?php
                                endwhile;
                            endif;
                            wp_reset_query();
                        }
                        ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
add_action('wp_footer', function (){?>
    <script src="<?= get_template_directory_uri() ?>/assets/player/js/p2p-media-loader-core.min.js"></script>
    <script src="<?= get_template_directory_uri() ?>/assets/player/js/p2p-media-loader-hlsjs.min.js"></script>
    <?php op_jwpayer_js(); ?>
    <script>
        
        var episode_id = '<?= get_first_episode_info()['server_key'] ?>';
        const wrapper = document.getElementById('kt_player');
        const vastAds = "<?= get_option('ophim_jwplayer_advertising_file') ?>";

        function chooseStreamingServer(el) {
            const type = el.dataset.type;
            const link = el.dataset.link.replace(/^http:\/\//i, 'https://');
            const id = el.dataset.id;

            episode_id = id;

            Array.from(document.getElementsByClassName('pu-link')).forEach(server => {
                server.classList.remove('player__cdn--active');
            })
            el.classList.add('player__cdn--active');
            wrapper.style.display = 'block';

            renderPlayer(type, link, id);
        }

        function renderPlayer(type, link, id) {
            if (type == 'embed') {
                if (vastAds) {
                    wrapper.innerHTML = `<div id="fake_jwplayer"></div>`;
                    const fake_player = jwplayer("fake_jwplayer");
                    const objSetupFake = {
                        key: "<?= get_option('ophim_jwplayer_license', 'ITWMv7t88JGzI0xPwW8I0+LveiXX9SWbfdmt0ArUSyc=') ?>",
                        aspectratio: "16:9",
                        width: "100%",
                        file: "<?= get_template_directory_uri() ?>/assets/player/1s_blank.mp4",
                        volume: 100,
                        mute: false,
                        autostart: true,
                        advertising: {
                            tag: "<?= get_option('ophim_jwplayer_advertising_file') ?>",
                            client: "vast",
                            vpaidmode: "insecure",
                            skipoffset: <?= get_option('ophim_jwplayer_advertising_skipoffset') ?:  5 ?>,
                            skipmessage: "Bỏ qua sau xx giây",
                            skiptext: "Bỏ qua"
                        }
                    };
                    fake_player.setup(objSetupFake);
                    fake_player.on('complete', function(event) {
                        $("#fake_jwplayer").remove();
                        wrapper.innerHTML = `<iframe width="100%" height="100%" src="${link}" frameborder="0" scrolling="no"
                    allowfullscreen="" allow='autoplay'></iframe>`
                        fake_player.remove();
                    });

                    fake_player.on('adSkipped', function(event) {
                        $("#fake_jwplayer").remove();
                        wrapper.innerHTML = `<iframe width="100%" height="100%" src="${link}" frameborder="0" scrolling="no"
                    allowfullscreen="" allow='autoplay'></iframe>`
                        fake_player.remove();
                    });

                    fake_player.on('adComplete', function(event) {
                        $("#fake_jwplayer").remove();
                        wrapper.innerHTML = `<iframe width="100%" height="100%" src="${link}" frameborder="0" scrolling="no"
                    allowfullscreen="" allow='autoplay'></iframe>`
                        fake_player.remove();
                    });
                } else {
                    if (wrapper) {
                        wrapper.innerHTML = `<iframe width="100%" height="100%" src="${link}" frameborder="0" scrolling="no"
                    allowfullscreen="" allow='autoplay'></iframe>`
                    }
                }
                return;
            }

            if (type == 'm3u8' || type == 'mp4') {
                wrapper.innerHTML = `<div id="jwplayer"></div>`;
                const player = jwplayer("jwplayer");
                const objSetup = {
                    key: "<?= get_option('ophim_jwplayer_license', 'ITWMv7t88JGzI0xPwW8I0+LveiXX9SWbfdmt0ArUSyc=') ?>",
                    aspectratio: "16:9",
                    width: "100%",
                    image: "<?= op_get_poster_url() ?>",
                    file: link,
                    playbackRateControls: true,
                    playbackRates: [0.25, 0.75, 1, 1.25],
                    sharing: {
                        sites: [
                            "reddit",
                            "facebook",
                            "twitter",
                            "googleplus",
                            "email",
                            "linkedin",
                        ],
                    },
                    volume: 100,
                    mute: false,
                    autostart: true,
                    logo: {
                        file: "<?= get_option('ophim_jwplayer_logo_file') ?>",
                        link: "<?= get_option('ophim_jwplayer_logo_link') ?>",
                        position: "<?= get_option('ophim_jwplayer_logo_position') ?>",
                    },
                    advertising: {
                        tag: "<?= get_option('ophim_jwplayer_advertising_file') ?>",
                        client: "vast",
                        vpaidmode: "insecure",
                        skipoffset: <?= get_option('ophim_jwplayer_advertising_skipoffset') ?:  5 ?>,
                        skipmessage: "Bỏ qua sau xx giây",
                        skiptext: "Bỏ qua"
                    }
                };

                if (type == 'm3u8') {
                    const segments_in_queue = 50;

                    var engine_config = {
                        debug: !1,
                        segments: {
                            forwardSegmentCount: 50,
                        },
                        loader: {
                            cachedSegmentExpiration: 864e5,
                            cachedSegmentsCount: 1e3,
                            requiredSegmentsPriority: segments_in_queue,
                            httpDownloadMaxPriority: 9,
                            httpDownloadProbability: 0.06,
                            httpDownloadProbabilityInterval: 1e3,
                            httpDownloadProbabilitySkipIfNoPeers: !0,
                            p2pDownloadMaxPriority: 50,
                            httpFailedSegmentTimeout: 500,
                            simultaneousP2PDownloads: 20,
                            simultaneousHttpDownloads: 2,
                            httpDownloadInitialTimeout: 0,
                            httpDownloadInitialTimeoutPerSegment: 17e3,
                            httpUseRanges: !0,
                            maxBufferLength: 300,
                        },
                    };
                    if (Hls.isSupported() && p2pml.hlsjs.Engine.isSupported()) {
                        var engine = new p2pml.hlsjs.Engine(engine_config);
                        player.setup(objSetup);
                        jwplayer_hls_provider.attach();
                        p2pml.hlsjs.initJwPlayer(player, {
                            liveSyncDurationCount: segments_in_queue,
                            maxBufferLength: 300,
                            loader: engine.createLoaderClass(),
                        });
                    } else {
                        player.setup(objSetup);
                    }
                } else {
                    player.setup(objSetup);
                }


                const resumeData = 'OPCMS-PlayerPosition-' + id;
                player.on('ready', function() {
                    if (typeof(Storage) !== 'undefined') {
                        if (localStorage[resumeData] == '' || localStorage[resumeData] == 'undefined') {
                            console.log("No cookie for position found");
                            var currentPosition = 0;
                        } else {
                            if (localStorage[resumeData] == "null") {
                                localStorage[resumeData] = 0;
                            } else {
                                var currentPosition = localStorage[resumeData];
                            }
                            console.log("Position cookie found: " + localStorage[resumeData]);
                        }
                        player.once('play', function() {
                            console.log('Checking position cookie!');
                            console.log(Math.abs(player.getDuration() - currentPosition));
                            if (currentPosition > 180 && Math.abs(player.getDuration() - currentPosition) >
                                5) {
                                player.seek(currentPosition);
                            }
                        });
                        window.onunload = function() {
                            localStorage[resumeData] = player.getPosition();
                        }
                    } else {
                        console.log('Your browser is too old!');
                    }
                });

                player.on('complete', function() {
                    if (typeof(Storage) !== 'undefined') {
                        localStorage.removeItem(resumeData);
                    } else {
                        console.log('Your browser is too old!');
                    }
                })

                function formatSeconds(seconds) {
                    var date = new Date(1970, 0, 1);
                    date.setSeconds(seconds);
                    return date.toTimeString().replace(/.*(\d{2}:\d{2}:\d{2}).*/, "$1");
                }
            }
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const episode = '<?= get_first_episode_info()['server_key'] ?>';
            let playing = document.querySelector(`[data-id="${episode}"]`);
            if (playing) {
                playing.click();
                return;
            }

            const servers = document.getElementsByClassName('pu-link');
            if (servers[0]) {
                servers[0].click();
            }
        });
    </script>

<?php }) ?>
