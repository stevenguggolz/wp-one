<?php


// SHORTCODE - STRETCHED LINK
function od_stretched_link() { ?>
    <a class="od-stretched-link" href="<?php the_permalink(); ?>"></a>
<?php } add_shortcode( 'stretched_link', 'od_stretched_link' );


// READING TIME
function od_reading_time() { 
    ?>
    <span id="readingtime"></span>
    <script>
        function getMeta(metaName) {
            const metas = document.getElementsByTagName('meta');
            for (let i = 0; i < metas.length; i++) {
                if (metas[i].getAttribute('name') === metaName) {
                return metas[i].getAttribute('content');
                }
            }
            return '';
        }
        document.getElementById('readingtime').innerHTML = getMeta('twitter:data2'); 
    </script>
    <?php
} add_shortcode('reading_time', 'od_reading_time');