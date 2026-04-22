<?php
/* Template Name: お問い合わせフォーム */
get_header(); ?>

<main>
    <section class="access">
        <div class="access__inner">
            <h2 class="access__title">お問い合わせ</h2>

            <?php echo do_shortcode('[mwform_formkey key="23"]'); ?>


<div class="access__policy">
                    <h3 class="access__policy__title">プライバシーポリシー</h3>
                    <div class="access__policy__textbox">
                        <p>島トゥクユーザーの個人情報の取扱いについて，以下のとおりプライバシーポリシーを定めます。</p>
                    </div>
                <div class="access__policy__listbox">
                        <ul>
                            <li>
                                <h4 class="access__policy__subtitle">個人情報の利用目的</h4>
                                <p>当サイトでは、お問い合わせやご予約時に「お名前」「メールアドレス」等の個人情報を取得いたします。これらの情報は、お問い合わせへの回答や必要な情報の連絡にのみ利用し、目的以外では利用いたしません。</p>
                            </li>
                            <li>
                                <h4 class="access__policy__subtitle">個人情報の第三者提供</h4>
                                <p>当サイトは、取得した個人情報を法令に基づく場合を除き、許可なく第三者に提供することはありません。</p>
                            </li>
                            <li>
                                <h4 class="access__policy__subtitle">個人情報の管理</h4>
                                <p>当サイトは、取得した個人情報を適切に管理し、不正アクセス、紛失、破壊、改ざん及び漏洩等の防止に努めます。</p>
                            </li>
                            <li>
                                <h4 class="access__policy__subtitle">個人情報の開示・訂正・削除</h4>
                                <p>当サイトは、ユーザーから自己の個人情報の開示、訂正、削除等の請求があった場合、適切に対応いたします。</p>
                            </li>
                            <li>
                                <h4 class="access__policy__subtitle">プライバシーポリシーの変更</h4>
                                <p>当サイトは、法令等の変更に伴い、本プライバシーポリシーを変更することがあります。変更後のプライバシーポリシーは、当サイトに掲載した時点から効力を生じるものとします。</p>
                            </li>
                            <li>
                                <h4 class="access__policy__subtitle">お問い合わせ</h4>
                                <p>当サイトのプライバシーポリシーに関するお問い合わせは、このお問い合わせページからお願いいたします。</p>
                            </li>
                        </ul>
                </div>
                </div>

        </div>
    </section>
</main>

<?php get_footer(); ?>