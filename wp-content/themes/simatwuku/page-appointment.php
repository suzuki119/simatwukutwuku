<?php get_header();?>

    <main>

        <section class="appointment" id="appointment">

                <h2 class="appointment__title">予約の方法</h2>


                <div class="appointment__inner">
                <ul class="appointment__list">
                    <li>
                        <div>
                            <p>予約は電話のみの受付です。
                                天候が著しく悪い日や都合による休業の時には電話が繋がりません。
                                ご了承ください。</p>
                        </div>
                    </li>
                    <li>
                        <div>
                            <p>本サービスはタクシーなどのような旅客運送業ではありません。「島トゥク」は、観光フォトサービスです。</p>
                        </div>
                    </li>
                </ul>
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

                <a href="#appointment-ex" class="appointment-Btn linkBtn">予約</a>

                <div class="appointment__others">

                    <p class="appointment__toggleBtn">その他注意事項</p>
                    <div class="appointment__others__textbox">
                        <ul>
                            <li>
                                <p>同乗いただけるのは６人までです。
                                </p>
                            </li>
                            <li>
                                <p>「島トゥク」はレンタカーではありません。後部座席に乗っていただき、島の景色を楽しんだり、写真を撮ることができるというサービスです。どうしても借りたい場合はお電話の際やお問い合わせにてご相談ください。
                                </p>
                            </li>
                            <li>
                                <p>料金はサービスの終了後に払っていただきます。キャンセル料はいただきませんが無断でのキャンセルはおやめいただき、お電話にてお知らせください。</p>
                            </li>
                            <li>
                                <p>その他ご不明点があればお電話の際やお問い合わせにてご相談ください。</p>
                            </li>
                        </ul>
                    </div>
                </div>






            </div>

        </section>

    </main>

<?php get_footer();?>