<?php get_header(); ?>

<main class="single-news">
<div class="single-news__inner">

        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>

                <article class="single-news__article">

                    <h1 class="single-news__title"><?php the_title(); ?></h1>

                    <time class="single-news__date">
                        <?php echo get_the_date('Y年m月d日'); ?>
                    </time>

<div class="single-news__contents">

                        <?php if (has_post_thumbnail()) : ?>
                            <div class="single-news__thumb">
                                <?php the_post_thumbnail('large'); ?>
                            </div>
                        <?php endif; ?>

                        <div class="content">
                            <?php the_content(); ?>
                        </div>
</div>

                </article>

            <?php endwhile; ?>
        <?php endif; ?>
</div>

</main>

<?php get_footer(); ?>