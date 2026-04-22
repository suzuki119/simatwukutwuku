<?php
get_header();
?>
<div class="page-404">
  <h1>404 NOT FOUND</h1>
  <p>お探しのページは見つかりませんでした</p>
  <?php // トップに戻るボタン ?>
  <a href="<?php echo esc_url( home_url( '/' ) ); ?>">トップに戻る</a>
  <?php get_search_form(); // 検索フォーム ?>
</div>
<?php
get_footer();