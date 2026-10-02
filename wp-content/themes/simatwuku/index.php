<?php get_header(); ?>


<section class="global-bottom">
    <div class="global-bottom__inner">



        <div class="global-bottom__content">
            <div class="global-bottom__toggle">
                <img src="<?php echo get_template_directory_uri(); ?>/img/icon/appointment.webp" alt="" class="open" width="340" height="336">
                <img src="<?php echo get_template_directory_uri(); ?>/img/icon/back.webp" alt="" class="back" width="340" height="340">
            </div>
            <a href="tel:080-4223-3450">
                <div class="global-bottom__tel">
                    <img src="<?php echo get_template_directory_uri(); ?>/img/icon/tel_1.webp" alt="" width="420" height="147">
                    <p>#080-4223-3450</p>



                </div>
            </a>

            <div class="global-bottom__menu">
                <div>
                    <h3 class="global-bottom__title">料金</h3>
                    <div class="global-bottom__menu__box">
                        <ul>
                            <li>〜4名:<?php echo get_theme_mod('price_min', '2000'); ?>円</li>
                            <li>5名:<?php echo get_theme_mod('price_middle', '2500'); ?>円</li>
                            <li>6名:<?php echo get_theme_mod('price_max', '3000'); ?>円</li>
                        </ul>
                    </div>
                </div>
                <div>
                    <h3 class="global-bottom__title">所要時間</h3>
                    <div class="global-bottom__menu__box">
                        <p>20分</p>
                    </div>
                </div>
                <div>
                    <h3 class="global-bottom__title">人数</h3>
                    <div class="global-bottom__menu__box">
                        <p>1〜6人</p>
                    </div>
                </div>
                <div>
                    <h3 class="global-bottom__title">のりば</h3>
                    <div class="global-bottom__menu__box">
                        <p>西港[ひまぽ前]</p>
                        <p>東港[信号機前]</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="cloud-wipe-container" id="cloudWipe">
    <div class="cloud-layer cloud-left">
        <div class="cloud-item"></div>
        <div class="cloud-item"></div>
        <div class="cloud-item"></div>
    </div>
    <div class="cloud-layer cloud-right">
        <div class="cloud-item"></div>
        <div class="cloud-item"></div>
        <div class="cloud-item"></div>
    </div>
</div>
<main class="main">
    <section class="mainvisual">
        <div class="mainvisual__inner">




            <div class="mainvisual__copy">
                <?php // LCP要素。スマホでは 90vw（≒370px）までしか表示しないので小さい方を配信する
                ?>
                <img src="<?php echo get_template_directory_uri(); ?>/img/icon/main-title.webp"
                    srcset="<?php echo get_template_directory_uri(); ?>/img/icon/main-title-800.webp 800w,
             <?php echo get_template_directory_uri(); ?>/img/icon/main-title.webp 1400w"
                    sizes="(min-width: 1100px) 700px, 90vw"
                    alt="しまトゥクあじはま"
                    width="1400" height="567"
                    fetchpriority="high">
            </div>

            <div class="mainvisual__circle">
                <?php
                // メインビジュアル内を漂う写真。表示は最大200px四方（実測316px）なので実寸も400pxに抑えてある。
                // 1枚ずつ順番に現れる演出なので、LCP画像（メインコピー）の帯域を奪わないよう fetchpriority を下げる。
                $mv_photos = array(
                    array('icon/octopus.webp',              400, 390, true),
                    array('icon/puffer-fish.webp',          400, 296, true),
                    array('mainvisual__image/Vector_1.webp', 367, 400, false),
                    array('mainvisual__image/Vector_2.webp', 400, 400, false),
                    array('mainvisual__image/Vector_3.webp', 400, 400, false),
                    array('mainvisual__image/Vector_4.webp', 396, 400, false),
                    array('mainvisual__image/Vector_5.webp', 220, 220, false),
                    array('icon/octopus_1.webp',            376, 400, true),
                    array('mainvisual__image/Vector_6.webp', 320, 400, false),
                    array('mainvisual__image/Vector_7.webp', 400, 300, false),
                    array('mainvisual__image/Vector_8.webp', 400, 400, false),
                    array('mainvisual__image/Vector_9.webp', 400, 400, false),
                );
                foreach ($mv_photos as $p) :
                    $class = 'mainvisual__circle__photo' . ($p[3] ? ' mainvisual__circle__image' : '');
                ?>
                    <img src="<?php echo get_template_directory_uri() . '/img/' . $p[0]; ?>" alt="" class="<?php echo $class; ?>" width="<?php echo $p[1]; ?>" height="<?php echo $p[2]; ?>" fetchpriority="low">
                <?php endforeach; ?>

                <div class="mainvisual__waves">
                    <div>
                        <div class="wave top ">
                            <svg viewBox="0 0 2400 150" preserveAspectRatio="none">
                                <path d="M0,50 C100,130 100,-30 200,50 C300,130 300,-30 400,50 C500,130 500,-30 600,50 C700,130 700,-30 800,50 C900,130 900,-30 1000,50 C1100,130 1100,-30 1200,50 C1300,130 1300,-30 1400,50 C1500,130 1500,-30 1600,50 C1700,130 1700,-30 1800,50 C1900,130 1900,-30 2000,50 C2100,130 2100,-30 2200,50 C2300,130 2300,-30 2400,50 L2400,150 L0,150 Z" />
                            </svg>
                        </div>

                        <div class="wave bottom ">
                            <svg viewBox="0 0 2400 150" preserveAspectRatio="none">
                                <path d="M0,50 C100,-30 100,130 200,50 C300,-30 300,130 400,50 C500,-30 500,130 600,50 C700,-30 700,130 800,50 C900,-30 900,130 1000,50 C1100,-30 1100,130 1200,50 C1300,-30 1300,130 1400,50 C1500,-30 1500,130 1600,50 C1700,-30 1700,130 1800,50 C1900,-30 1900,130 2000,50 C2100,-30 2100,130 2200,50 C2300,-30 2300,130 2400,50 L2400,-50 L0,-50 Z" />
                            </svg>
                        </div>
                    </div>

                    <div>
                        <div class="wave top ">
                            <svg viewBox="0 0 2400 150" preserveAspectRatio="none">
                                <path d="M0,50 C100,130 100,-30 200,50 C300,130 300,-30 400,50 C500,130 500,-30 600,50 C700,130 700,-30 800,50 C900,130 900,-30 1000,50 C1100,130 1100,-30 1200,50 C1300,130 1300,-30 1400,50 C1500,130 1500,-30 1600,50 C1700,130 1700,-30 1800,50 C1900,130 1900,-30 2000,50 C2100,130 2100,-30 2200,50 C2300,130 2300,-30 2400,50 L2400,150 L0,150 Z" />
                            </svg>
                        </div>

                        <div class="wave bottom ">
                            <svg viewBox="0 0 2400 150" preserveAspectRatio="none">
                                <path d="M0,50 C100,-30 100,130 200,50 C300,-30 300,130 400,50 C500,-30 500,130 600,50 C700,-30 700,130 800,50 C900,-30 900,130 1000,50 C1100,-30 1100,130 1200,50 C1300,-30 1300,130 1400,50 C1500,-30 1500,130 1600,50 C1700,-30 1700,130 1800,50 C1900,-30 1900,130 2000,50 C2100,-30 2100,130 2200,50 C2300,-30 2300,130 2400,50 L2400,-50 L0,-50 Z" />
                            </svg>
                        </div>
                    </div>




                    <div>
                        <div class="wave top ">
                            <svg viewBox="0 0 2400 150" preserveAspectRatio="none">
                                <path d="M0,50 C100,130 100,-30 200,50 C300,130 300,-30 400,50 C500,130 500,-30 600,50 C700,130 700,-30 800,50 C900,130 900,-30 1000,50 C1100,130 1100,-30 1200,50 C1300,130 1300,-30 1400,50 C1500,130 1500,-30 1600,50 C1700,130 1700,-30 1800,50 C1900,130 1900,-30 2000,50 C2100,130 2100,-30 2200,50 C2300,130 2300,-30 2400,50 L2400,150 L0,150 Z" />
                            </svg>
                        </div>

                        <div class="wave bottom ">
                            <svg viewBox="0 0 2400 150" preserveAspectRatio="none">
                                <path d="M0,50 C100,-30 100,130 200,50 C300,-30 300,130 400,50 C500,-30 500,130 600,50 C700,-30 700,130 800,50 C900,-30 900,130 1000,50 C1100,-30 1100,130 1200,50 C1300,-30 1300,130 1400,50 C1500,-30 1500,130 1600,50 C1700,-30 1700,130 1800,50 C1900,-30 1900,130 2000,50 C2100,-30 2100,130 2200,50 C2300,-30 2300,130 2400,50 L2400,-50 L0,-50 Z" />
                            </svg>
                        </div>
                    </div>


                </div>
            </div>



        </div>
    </section>

    <section class="appointment-ex" id="appointment-ex">
        <div class="appointment-ex__inner">
            <h2 class="appointment-ex__title">ご利用方法</h2>
            <div class="appointment-ex__cards">
                <div class="appointment-ex__card appointment-ex__card--ok">
                    <img src="<?php echo get_template_directory_uri(); ?>/img/tel-image.webp" alt="電話で島トゥクを予約するタコのイラスト" class="appointment-ex__image" width="1200" height="625" loading="lazy">
                    <p class="appointment-ex__card-title">ご予約は<strong>お電話のみ</strong>！</p>
                    <a href="tel:080-4223-3450" class="appointment-ex__tel">080-4223-3450</a>
                </div>
                <div class="appointment-ex__card appointment-ex__card--ng">
                    <img src="<?php echo get_template_directory_uri(); ?>/img/tel-image2.webp" alt="インターネットで予約できずに困っているタコのイラスト" class="appointment-ex__image" width="800" height="735" loading="lazy">
                    <p class="appointment-ex__card-title">ネット予約はできません</p>
                </div>

            </div>
            <p class="appointment-ex__notice">天候が著しく悪い日や都合による休業の時には電話が繋がりません。ご了承ください。</p>
            <a href="tel:080-4223-3450" class="appointment-Btn linkBtn">予約</a>
            <a href="<?php echo home_url('/appointment'); ?>" class="appointment-ex__link">更に詳しく＞</a>



        </div>


        <!-- <div class="appointment-ex__fee">

                    <ul>
                        島1周コース（〜6名様）
                    <li>大人一人500円</li>
                        <li>子ども（小学生以下）一人300円</li>
                        <li>フォトスポット1ヶ所につき、チェキ1枚（追加で、一枚につき100円）</li>
                    </ul>
                    <ul>
                    島半周コース（〜6名様）
                    <li>大人一人300円</li>
                    <li>子ども（小学生以下）一人300円</li>
                    <li>フォトスポット1ヶ所で一回写真撮影</li>
                </ul>
</div>
 -->
    </section>

    <section class="about" id="about">
        <div class="about__inner">
            <h2 class="about__title">島トゥクとは？</h2>
            <div class="about__textbox">
                <p>日間賀島を「トゥクトゥク」で巡る
                    観光フォトサービス！
                    潮風を体に感じて、
                    島内の人気スポットや抱負な
                    自然も知ることができます。
                    この機会にぜひ乗りに来てください！</p>
            </div>
        </div>
    </section>

    <section class="vision-example" id="vision-example">
        <div class="vision-example__inner">
            <h2 class="vision-example__title">景色の例</h2>
            <div class="vision-example__image-area">
                <a href="https://www.instagram.com/ajihama.himakajima" class="insta">
                    <?php
                    // スライド表示される作例写真。1枚目以外はファーストビュー外なので遅延読み込み
                    $examples = array(
                        array('15.webp', 800, 1000),
                        array('13.webp', 800, 1067),
                        array('9.webp',  800, 1069),
                        array('19.webp', 800, 1000),
                        array('24.webp', 800, 1000),
                        array('20.webp', 800, 1000),
                    );
                    foreach ($examples as $i => $ex) :
                    ?>
                        <img src="<?php echo get_template_directory_uri() . '/img/image/' . $ex[0]; ?>" alt="example_<?php echo $i + 1; ?>" class="vision-example__image-area__image" width="<?php echo $ex[1]; ?>" height="<?php echo $ex[2]; ?>" loading="lazy">
                    <?php endforeach; ?>
                </a>
            </div>
            <a href="https://www.instagram.com/ajihama.himakajima" class="more linkBtn">Instagram</a>
        </div>

    </section>



    <section class="news" id="news">

        <div class="news__inner">
            <h2 class="news__title">お知らせ</h2>


            <?php
            $args = array(
                'post_type'      => 'post',
                'posts_per_page' => 4,
                'orderby'        => 'date',
                'order'          => 'DESC',
            );
            $news_query = new WP_Query($args);
            ?>

            <!-- 記事 -->
            <div class="news__box">

                <?php if ($news_query->have_posts()) : ?>
                    <?php while ($news_query->have_posts()) : $news_query->the_post(); ?>

                        <a href="<?php the_permalink(); ?>" class="news__link">
                            <article class="news__item">


                                <!-- サムネイル -->

                                <div class="news__imgbox">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('medium', array('loading' => 'lazy')); ?>
                                    <?php else : ?>
                                        <img src="<?php echo get_template_directory_uri(); ?>/img/image/news-template.webp" alt="news" width="720" height="450" loading="lazy">
                                    <?php endif; ?>
                                </div>

                                <div class="news__textbox">
                                    <p>
                                        日付：<?php echo get_the_date('Y年m月d日'); ?><br> </p>
                                    <?php the_excerpt(); ?>

                                </div>

                            </article>
                        </a>

                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                <?php else : ?>
                    <p class="news__empty">まだ記事はありません。</p>
                <?php endif; ?>


            </div>
            <a href="<?php echo home_url('/news-archive'); ?>" class="more linkBtn">More</a>
        </div>

    </section>

</main>

<?php get_footer(); ?>