<?php get_header();?>

<main class="news-archive">
<div class="news-archive__inner">

        <h1 class="news-archive__title">お知らせ一覧</h1>

        <div class="news-archive__grid">

            <?php
            $paged = get_query_var('paged') ? get_query_var('paged') : 1;

$args = array(
    'post_type' => 'post',
    'posts_per_page' => 9,
    'paged' => $paged,
);
$news_query = new WP_Query($args);
            ?>

            <?php if ($news_query->have_posts()) : ?>
                <?php while ($news_query->have_posts()) : $news_query->the_post(); ?>

                    <a href="<?php the_permalink(); ?>">
                        <article>

                            <?php if (has_post_thumbnail()) : ?>
                                <?php the_post_thumbnail('medium'); ?>
                            <?php else : ?>
                                <img src="<?php echo get_template_directory_uri(); ?>/img/image/news-template.jpg" alt="news">
                            <?php endif; ?>

                            <div class="news__textbox">
                                <time><?php echo get_the_date('Y.m.d'); ?></time>
                                <p><?php echo wp_trim_words(get_the_excerpt(), 30); ?></p>
                            </div>

                        </article>
                    </a>

                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            <?php endif; ?>


        </div>

        <div class="pagination">
    <?php
    echo paginate_links(array(
        'total' => $news_query->max_num_pages
    ));
    ?>
</div>

</div>

</main>

<?php get_footer(); ?>