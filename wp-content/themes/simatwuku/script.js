// script.js - wrapped with DOMContentLoaded and guarded selectors to avoid runtime errors

document.addEventListener('DOMContentLoaded', function () {

    /* -----------------
       appointment page
       ----------------- */




    /* -----------------
       index: 画像切替
       ----------------- */
    if (document.body.classList.contains("home")) {
        const visionimages = document.querySelectorAll(".vision-example__image-area__image");
        if (visionimages.length > 0) {
            let currentIndex = visionimages.length - 1;

            visionimages.forEach((img, i) => {
                img.style.opacity = i === currentIndex ? "1" : "0";
                img.style.transition = "opacity 1s ease";
            });

            setInterval(() => {
                const nextIndex = (currentIndex - 1 + visionimages.length) % visionimages.length;
                visionimages[currentIndex].style.opacity = "0";
                visionimages[nextIndex].style.opacity = "1";
                currentIndex = nextIndex;
            }, 3000);
        }
    }

    /* -----------------
       ページ内リンクの自前スクロール
       ----------------- */
    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener("click", function (e) {
            // if href is just "#" or not pointing to an id, ignore
            const href = this.getAttribute("href");
            if (!href || href === "#") return;

            e.preventDefault(); // ブラウザのデフォルト動作を止める

            const targetID = href.substring(1);
            const targetElement = document.getElementById(targetID);

            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop,
                    behavior: "smooth"
                });
            }

            // URLの #◯◯ を履歴に残さない
            history.replaceState(null, "", window.location.pathname);
        });
    });

    /* -----------------
       ハンバーガー
       ----------------- */
    const hamburgerBtn = document.querySelector(".hamburger");
    const menu = document.querySelector(".menu");
    const body = document.body;
    if (hamburgerBtn && menu) {
        hamburgerBtn.addEventListener("click", function () {
            menu.classList.toggle("active");
            hamburgerBtn.classList.toggle("active");
            if (window.innerWidth < 768) {
                body.classList.toggle('is-fixed');
            }
        });
    }

    /* -----------------
       メインビジュアル：アニメーション
       ----------------- */


    const isIOS = /iP(hone|od|ad)/.test(navigator.userAgent);


    if (document.body.classList.contains("home")) {
        const images = document.querySelectorAll(".mainvisual__circle__photo");

        if (images.length > 0) {
            let index = 0;

            function runSequence() {
                const img = images[index];
                img.style.animation = "none";
                void img.offsetWidth;

                const randomX = Math.random() * 100 - 20;
                let randomRotate = Math.floor(Math.random() * 2);

                if (randomRotate === 1) {
                    randomRotate = Math.floor(Math.random() * 40) + 40;
                }
                else {
                    randomRotate = Math.floor(Math.random() * 40) - 80;
                }
                let randomSize = Math.random() * (150 - 100) + 100;



                let randomY = (Math.floor((Math.random() * 2)));

                if (randomY === 1) {
                    randomY = 100;

                } else {
                    randomY = 400;
                }

                img.style.top = `${randomY}px`;

                img.style.setProperty("--rotate", `${randomRotate}deg`);

                img.style.setProperty("--up", `${randomSize / 5}px`);

                img.style.setProperty("--size", `${randomSize}%`);

                // ★ここを修正：up(横移動) と puka-puka(浮遊) を合体させる
                // up は 30s で横切り、puka-puka は 4s ごとに揺れる
                // runSequence内のアニメーション指定箇所
                img.style.animation = `up 30s linear forwards, puka-puka 4s ease-in-out infinite`;
                index = (index + 1) % images.length;

                const delaytime = 3000; //window.innerWidth * 8;

                setTimeout(runSequence, delaytime);
            }
            runSequence();
        }

        const toggle = document.querySelector('.global-bottom__toggle');
        const menu = document.querySelector('.global-bottom__content');

        toggle.addEventListener('click', () => {
            menu.classList.toggle('is-open');
            toggle.classList.toggle('is-open');
        });

        window.addEventListener('DOMContentLoaded', () => {
            const mv = document.querySelector('.mainvisual');
            if (mv) {
                // 現在の画面の高さを取得して、pxで直接指定する
                const vh = window.innerHeight;
            }

        });


        //雲のアニメーション
        function updateCloudAnimation() {
            const cloudContainer = document.getElementById('cloudWipe');
            const mv = document.querySelector('.mainvisual');

            if (!cloudContainer || !mv) return;

            const cloudLeft = cloudContainer.querySelector('.cloud-left');
            const cloudRight = cloudContainer.querySelector('.cloud-right');

            if (!cloudLeft || !cloudRight) return;

            // アニメーションを動かす範囲（メインビジュアルの高さ分など）
            const scrollMax = window.innerHeight;
            const scrollY = window.scrollY;

            // 進捗率を 0 ～ 1 の間で計算
            let progress = Math.min(scrollY / scrollMax, 1);

            // 雲の移動（-100% から 0% へ、100% から 0% へ）
            const leftMove = -100 + (progress * 200);
            const rightMove = 100 - (progress * 200);

            cloudLeft.style.transform = `translateX(${leftMove}%)`;
            cloudRight.style.transform = `translateX(${rightMove}%)`;

            // 雲が閉じきったらメインビジュアルを非表示にする
            if (progress >= 0.4) {
                mv.style.opacity = "0";
                mv.style.pointerEvents = "none";
            } else {
                mv.style.opacity = "1";
                mv.style.pointerEvents = "auto";
            }
        }

        // scroll は全デバイス共通
        window.addEventListener('scroll', updateCloudAnimation, { passive: true });

        // iOS はスクロール中に scroll イベントが遅延するため touchmove で補完
        if (isIOS) {
            document.body.classList.add('ios');
            window.addEventListener('touchmove', updateCloudAnimation, { passive: true });
        }

        // window.addEventListener('scroll', () => {
        //     const mainvisual = document.querySelector('.mainvisual');
        //     const scrollThreshold = window.innerHeight; // 100vhスクロールしたら解除


        //     if (window.scrollY >= scrollThreshold) {

        //     } else {
        //         mainvisual.style.position = 'fixed';
        //         mainvisual.style.top = '0';
        //     }
        // });





    }

});
