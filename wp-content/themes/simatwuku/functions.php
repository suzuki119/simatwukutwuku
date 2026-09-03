<?php
// Enqueue main script and pass theme URL to JavaScript
function simatwuku_scripts() {
    wp_enqueue_script(
        'main-js',
        get_template_directory_uri() . '/script.js',
        array(),
        false,
        true
    );

    wp_localize_script('main-js', 'themeVars', array(
        'themeUrl' => get_template_directory_uri(),
    ));
}
add_action('wp_enqueue_scripts', 'simatwuku_scripts');

// Optional: enqueue main stylesheet if needed
// スタイルシートの一括管理
function simatwuku_styles() {
    // メインのstyle.css（リセットCSSは simatwuku_inline_reset_css() で先にインライン出力される）
    $dir  = get_template_directory();
    $file = '/css/style.css';

    // 圧縮版が最新であればそちらを配信する（古ければ通常版にフォールバック）
    if ( is_readable( $dir . '/css/style.min.css' )
        && filemtime( $dir . '/css/style.min.css' ) >= filemtime( $dir . '/css/style.css' ) ) {
        $file = '/css/style.min.css';
    }

    wp_enqueue_style(
        'main-style',
        get_template_directory_uri() . $file,
        array(),
        filemtime( $dir . $file ) // 更新時にキャッシュを自動クリア
    );
}
add_action('wp_enqueue_scripts', 'simatwuku_styles');

// WordPress の絵文字変換スクリプト（このサイトでは未使用）を止める
function simatwuku_disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'emoji_svg_url', '__return_false' );
}
add_action( 'init', 'simatwuku_disable_emojis' );

// リセットCSS（約1.5KB）は外部リクエストにせず <head> に直接埋め込む。
// CDN 参照だと DNS + TLS のぶんレンダリングがブロックされるため。
// wp_print_styles は wp_head の優先度8で実行されるので、それより前に出す。
function simatwuku_inline_reset_css() {
    $reset = get_template_directory() . '/css/reset.css';
    if ( ! is_readable( $reset ) ) {
        return;
    }
    echo '<style id="reset-css-inline">' . file_get_contents( $reset ) . '</style>' . "\n";
}
add_action( 'wp_head', 'simatwuku_inline_reset_css', 2 );

// LCP になるメインコピー画像を先読みする
function simatwuku_preload_lcp_image() {
    if ( ! is_front_page() ) {
        return;
    }
    // index.php 側の srcset / sizes と必ず同じ内容にすること（食い違うと二重に取得される）
    $dir = get_template_directory_uri();
    printf(
        '<link rel="preload" as="image" href="%s" imagesrcset="%s 800w, %s 1400w" imagesizes="(min-width: 1100px) 700px, 90vw" fetchpriority="high">' . "\n",
        esc_url( $dir . '/img/icon/main-title.webp' ),
        esc_url( $dir . '/img/icon/main-title-800.webp' ),
        esc_url( $dir . '/img/icon/main-title.webp' )
    );
}
add_action( 'wp_head', 'simatwuku_preload_lcp_image', 1 );

//サムネイル機能
function simatwuku_theme_setup() {
    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('html5', array('search-form'));
}
add_action('after_setup_theme', 'simatwuku_theme_setup');

// コメント機能を完全に無効化
function disable_comments_post_types_support() {
    remove_post_type_support('post', 'comments');
    remove_post_type_support('post', 'trackbacks');
}
add_action('init', 'disable_comments_post_types_support');

add_filter('body_class', function ($classes) {
    if (is_page()) {
        global $post;
        $classes[] = 'page-' . $post->post_name;
    }
    return $classes;
});

// 投稿からカテゴリー・タグ機能を削除
function remove_post_taxonomies() {
    unregister_taxonomy_for_object_type('category', 'post');
    unregister_taxonomy_for_object_type('post_tag', 'post');
}
add_action('init', 'remove_post_taxonomies');

// OGP / meta description 用の共通取得関数
function simatwuku_get_ogp_data() {
    $site_name = get_bloginfo('name');
    $site_description = get_bloginfo('description');
    $theme_url = get_template_directory_uri();

    $data = array(
        'title' => $site_name,
        'description' => $site_description,
        'url' => home_url('/'),
        'image' => $theme_url . '/img/ogp.jpg',
        'type' => 'website',
        'site_name' => $site_name,
    );

    if (is_front_page()) {
        $data['title'] = '日間賀島を巡る観光フォトサービス｜島トゥク';
        $data['description'] = '日間賀島をトゥクトゥクで巡る観光フォトサービス「島トゥク」。潮風を感じながら、島の人気スポットで思い出に残る写真を撮影できます。';
    }

    if (is_single() || is_page()) {
        $data['title'] = get_the_title();
        $data['description'] = get_the_excerpt() ?: $site_description;
        $data['url'] = get_permalink();
        $data['type'] = 'article';

        if (has_post_thumbnail()) {
            $data['image'] = get_the_post_thumbnail_url(null, 'large');
        }
    }

    return $data;
}

/**
 * カスタマイザーに料金設定を追加
 */
function shimatuku_customize_register( $wp_customize ) {
    // 1. セクション（まとまり）を作る
    $wp_customize->add_section( 'shimatuku_price_section', array(
        'title'    => '料金・数値設定', // 管理画面に表示される名前
        'priority' => 100,
    ));
    // 料金の設定
    $wp_customize->add_setting( 'price_min', array(
        'default'   => '2000', // 初期値
        'transport' => 'refresh',
    ));
    $wp_customize->add_control( 'price_min', array(
        'label'    => '４名までの料金（単位なしで入力してください）',
        'section'  => 'shimatuku_price_section',
        'settings' => 'price_min',
        'type'     => 'text',
    ));

    $wp_customize->add_setting( 'price_middle', array(
    'default'   => '2500', // 初期値
    'transport' => 'refresh',
    ));
    $wp_customize->add_control( 'price_middle', array(
        'label'    => '5名の料金（単位なしで入力してください）',
        'section'  => 'shimatuku_price_section',
        'settings' => 'price_middle',
        'type'     => 'text',
    ));

    $wp_customize->add_setting( 'price_max', array(
    'default'   => '3000', // 初期値
    'transport' => 'refresh',
    ));
    $wp_customize->add_control( 'price_max', array(
        'label'    => '6名の料金（単位なしで入力してください）',
        'section'  => 'shimatuku_price_section',
        'settings' => 'price_max',
        'type'     => 'text',
    ));
}
add_action( 'customize_register', 'shimatuku_customize_register' );

// canonical タグ
function simatwuku_add_canonical() {
    if ( is_front_page() ) {
        $canonical = home_url( '/' );
    } elseif ( is_singular() ) {
        $canonical = get_permalink();
    } else {
        return;
    }
    echo '<link rel="canonical" href="' . esc_url( $canonical ) . '">' . "\n";
}
add_action( 'wp_head', 'simatwuku_add_canonical' );

// JSON-LD 構造化データ（トップページのみ）
function simatwuku_add_json_ld() {
    if ( ! is_front_page() ) {
        return;
    }

    $price_min    = get_theme_mod( 'price_min', '2000' );
    $price_max    = get_theme_mod( 'price_max', '3000' );
    $theme_url    = get_template_directory_uri();

    $json_ld = array(
        '@context'    => 'https://schema.org',
        '@type'       => 'TouristAttraction',
        'name'        => '島トゥク',
        'alternateName' => 'しまトゥク',
        'description' => '日間賀島をトゥクトゥクで巡る観光フォトサービス。潮風を感じながら、島の人気スポットで思い出に残る写真を撮影できます。',
        'url'         => home_url( '/' ),
        'telephone'   => '080-4223-3450',
        'image'       => $theme_url . '/img/ogp.jpg',
        'address'     => array(
            '@type'           => 'PostalAddress',
            'addressLocality' => '日間賀島',
            'addressRegion'   => '愛知県',
            'addressCountry'  => 'JP',
        ),
        'hasOfferCatalog' => array(
            '@type' => 'OfferCatalog',
            'name'  => 'トゥクトゥク観光プラン',
            'itemListElement' => array(
                array(
                    '@type'       => 'Offer',
                    'name'        => 'トゥクトゥク観光フォトサービス',
                    'description' => '所要時間20分、1〜6名で乗車可能',
                    'price'       => $price_min,
                    'priceCurrency' => 'JPY',
                    'priceSpecification' => array(
                        '@type'       => 'PriceSpecification',
                        'minPrice'    => $price_min,
                        'maxPrice'    => $price_max,
                        'priceCurrency' => 'JPY',
                    ),
                ),
            ),
        ),
        'availableLanguage' => array(
            '@type' => 'Language',
            'name'  => 'Japanese',
        ),
        'sameAs' => array(
            'https://www.instagram.com/ajihama.himakajima',
            'https://ameblo.jp/katu-sayo/',
        ),
    );

    echo '<script type="application/ld+json">' . "\n";
    echo wp_json_encode( $json_ld, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
    echo "\n" . '</script>' . "\n";
}
add_action( 'wp_head', 'simatwuku_add_json_ld' );