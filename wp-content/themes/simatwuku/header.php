<!DOCTYPE html>
<!--nobanner-->
<html lang="ja">

<head>


    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c:wght@700&family=Zen+Kaku+Gothic+Antique:wght@400;700&display=swap"
        rel="stylesheet"
        media="print"
        onload="this.media='all'">

    <link rel="preload" as="image" href="<?php echo get_template_directory_uri(); ?>/img/icon/logo_2.webp">


    <?php
    // OGP/meta description
    $ogp = simatwuku_get_ogp_data();
    ?>
    <meta name="description" content="<?php echo esc_attr($ogp['description']); ?>">

    <meta property="og:title" content="<?php echo esc_attr($ogp['title']); ?>">
    <meta property="og:description" content="<?php echo esc_attr($ogp['description']); ?>">
    <meta property="og:url" content="<?php echo esc_url($ogp['url']); ?>">
    <meta property="og:image" content="<?php echo esc_url($ogp['image']); ?>">
    <meta property="og:type" content="<?php echo esc_attr($ogp['type']); ?>">
    <meta property="og:site_name" content="<?php echo esc_attr($ogp['site_name']); ?>">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo esc_attr($ogp['title']); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr($ogp['description']); ?>">
    <meta name="twitter:image" content="<?php echo esc_url($ogp['image']); ?>">

    <?php wp_head(); ?>

    <!--nobanner-->
</head>


<body <?php body_class(); ?>>
    <header class="header">
        <div class="header__inner">
            <a href="<?php echo home_url('/'); ?>" class="header__logo">
                <?php if (is_front_page()) : ?>
                    <h1>
                        <img src="<?php echo get_template_directory_uri(); ?>/img/icon/logo_2.webp" alt="島トゥク ロゴ" width="700" height="253" fetchpriority="high">
                    </h1>
                <?php else : ?>
                    <p>
                        <img src="<?php echo get_template_directory_uri(); ?>/img/icon/logo_2.webp" alt="島トゥク ロゴ" width="700" height="253" fetchpriority="high">
                    </p>
                <?php endif; ?>
            </a>
            <div class="global">
                <div class="hamburger">
                    <span class="hamburger__span"></span>
                    <span class="hamburger__span"></span>
                    <span class="hamburger__span"></span>
                </div>
                <nav class="menu">
                    <div class="menu__inner">
                        <ul>
                            <li><a href="<?php echo home_url('/'); ?>">HOME　↓</a></li>
                            <li><a href="<?php echo home_url('/#about'); ?>">島トゥクとは</a></li>
                            <li><a href="<?php echo home_url('/#vision-example'); ?>">景色の例</a></li>
                            <li><a href="<?php echo home_url('/#appointment-ex'); ?>">予約の方法</a></li>
                            <li><a href="<?php echo home_url('/#news'); ?>">お知らせ</a></li>
                        </ul>
                        <ul>
                            <li><a href="<?php echo home_url('/appointment'); ?>">予約</a></li>
                            <li><a href="<?php echo home_url('/q&a'); ?>">Q&A</a></li>
                            <li><a href="<?php echo home_url('/access'); ?>">お問い合わせ</a></li>
                            <li><a href="<?php echo home_url('/spot'); ?>">スポット</a></li>
                            <li><a href="<?php echo home_url('/root'); ?>">コース</a></li>
                            <li><a href="<?php echo home_url('/info'); ?>">アクセス</a></li>
                        </ul>

                        <div class="footer__media">
                            <div class="footer__media__SNS">
                                <a href="https://www.instagram.com/ajihama.himakajima" class="insta">
                                    <img src="<?php echo get_template_directory_uri(); ?>/img/icon/Instagram_Glyph_Gradient.webp" alt="インスタグラム" width="240" height="240" loading="lazy">
                                </a>
                                <a href="https://ameblo.jp/katu-sayo/" class="blog">
                                    <img src="<?php echo get_template_directory_uri(); ?>/img/bana-/blog_bana-.webp" alt="ブログバナー" width="580" height="400" loading="lazy">
                                </a>
                            </div>
                            <a href="#" class="ajihama">
                                <img src="<?php echo get_template_directory_uri(); ?>/img/bana-/azi_bana-.webp" alt="アジハマバナー" width="720" height="360" loading="lazy">
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </header>