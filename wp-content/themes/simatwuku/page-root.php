<?php get_header();?>

    <main>

        <section class="root">
            <div class="root__inner">
                <h2 class="root__title">
                    コースについて
                </h2>

                <div class="root__image">
    <a href="<?php echo home_url('/spot'); ?>">
        <picture>
            <source media="(min-width: 1100px)" srcset="<?php echo get_template_directory_uri(); ?>/img/map/d_map.png">
            <img src="<?php echo get_template_directory_uri(); ?>/img/map/s_map.png" alt="コースの画像">
        </picture>
    </a>
</div>

                <div class="root__textbox">
                    <p>西か東の港からスタートして、半周コースなら反対の港まで、1周コースなら同じ場所まで向かいながら各地のフォトスポットで写真を撮ります。
                        半周コースでは一箇所だけでの写真撮影ですが、1週コースなら100円につきさらに1枚撮らせていただきます。
                    </p>
                </div>

                <a href="<?php echo home_url('/spot'); ?>" class="linkBtn">スポット一覧</a>




            </div>
        </section>

    </main>

       <?php get_footer();?>
