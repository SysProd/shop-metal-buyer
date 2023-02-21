<?php

/* @var $this \yii\web\View */

/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\bootstrap\Carousel;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" itemscope itemtype="http://schema.org/WebPage">
<head>
    <meta http-equiv="Content-Type" content="text/html;" charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="google-site-verification" content="IeLkm6P-3-tltviBZTv5UKKmTrLwWZ1nu3sn7AtCMbw"/> <!-- custom meta -->
    <meta name="yandex-verification" content="8930e7f69d9f0b72"/> <!-- custom meta -->
    <meta name='wmail-verification' content='0c804806827d605a914d62d2425d7d64'/> <!-- custom meta -->

    <meta name="description"
          content="Осуществляем перечень услуг: Приём вторсырья, демонтаж металлолома, вывоз металлалома, утилизация бытовой техники, вывоз бытовой техники. Принимаем металлолом в Москве и области по выгодным ценам от трех тонн. Производим демонтаж металлоконструкций, резка металла, резка металлолома, газорезка. Контакты: 8(985)837-71-47">
    <meta name="keywords"
          content="цена 1 т металлолома, цена металлолома за 1, пункт металлолома в москве, чугун 1, толщина металла в мм, адреса пунктов приёма цены на металлолом в москве, кг чугун, металлолом москва, демонтаж металлолома в москве, прием металлолома в москве и области, металлолом круглосуточно в москве, металлолом 1, металл прием москва, металлолом в москве цена за кг, прием металлолома в москве, прием металлолома в москве цены, пункты приема металлолома в москве, цена металлолома за 1 кг в москве, пункты приема металлолома и цены в москве, металлолом пункт, металлолом прием цена, металлолом цена, металл лом, лом цена, металлолом прием, металлолом прием пункт, прием металлолома цена за кг в москве, металл прием, прием лом">
    <meta name="robots" content="index, follow">
    <meta name="revisit-after" content="1 day">
    <meta name="language" content="Russian">
    <meta name="generator" content="N/A">

    <!-- Мета теги определения месторасположения -->
    <meta property="place:location:latitude" content="55.881020"/>
    <meta property="place:location:longitude" content="37.570977"/>
    <meta property="business:contact_data:street_address" content="Путевой проезд"/>
    <meta property="business:contact_data:locality" content="Москва"/>
    <meta property="business:contact_data:postal_code" content="127410"/>
    <meta property="business:contact_data:country_name" content="Россия"/>
    <meta property="business:contact_data:phone_number" content="+79858377147"/>
    <meta property="business:contact_data:website" content="http://гор-мет.рф/"/>
    <!-- Мета теги для социальных сетей (Facebook и ВКонтакте)-->
    <meta property="og:type" content="website"/>
    <meta property="og:title" content="Гор-Мет.РФ"/>
    <meta property="og:description" content="Прием и демонтаж металлолома в Москве и обл. Контакты: 8(985)837-71-47"/>
    <meta property="og:image" content="http://гор-мет.рф/img/favicon.ico"/>
    <meta property="og:url" content="http://гор-мет.рф/"/>
    <meta property="og:site_name" content="гор-мет.рф"/>
    <!-- Мета теги для социальных сетей (Twitter)-->
    <meta name="twitter:site" content="http://гор-мет.рф/"/>
    <meta name="twitter:title" content="Гор-Мет.РФ"/>
    <meta name="twitter:description" content="Прием и демонтаж металлолома в Москве и обл. Контакты: 8(985)837-71-47"/>
    <!-- Мета теги для социальных сетей (Google+)-->
    <meta itemprop="name" content="гор-мет.рф"/>
    <meta itemprop="description" content="Прием и демонтаж металлолома в Москве и обл. Контакты: 8(985)837-71-47"/>
    <meta itemprop="image" content="http://гор-мет.рф/img/favicon.ico"/>
    <!-- Разметка локализованных страниц-->
    <link rel="alternate" hreflang="ru" href="http://гор-мет.рф/"/>

    <link rel="icon" href="http://гор-мет.рф/img/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="http://гор-мет.рф/img/favicon.ico" type="image/x-icon">

    <?= Html::csrfMetaTags() ?>

    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="header">
    <div class="container">
        <div class="row">

            <div class="col-md-8 text-left">
                <div class="navbar-header1">
                    <a href="#" class="navbar-brand scroll-top">
                        <div class="header-tittle">Scrap metal</div>
                        <h1>Скупка, прием и демонтаж металлолома в Москве</h1>
                    </a>
                </div>
            </div>

            <div class="col-md-4 text-left">
                    <div class="navbar-number1">
                        <a href="whatsapp://+79858377147" title="Принимаем заявки на WhatsApp">
                            +7 (985) 837 71 47
                            <span> <b>*</b> Принимаем металлолом круглосуточно</span>
                        </a>
                </div>
            </div>

        </div>

        <nav class="navbar navbar-inverse" role="navigation">

            <button type="button" id="nav-toggle" class="navbar-toggle" data-toggle="collapse" data-target="#main-nav">
                <span class="sr-only">Scrap metal</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <div id="main-nav" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li><a href="#" class="scroll-top">Главная</a></li>
                    <li><a href="#" class="scroll-link" data-id="services">Услуги</a></li>
                    <li><a href="#" class="scroll-link" data-id="price">Цены</a></li>
                    <li><a href="#" class="scroll-link" data-id="about">О нас</a></li>
                    <li><a href="#" class="scroll-link" data-id="contact">Контакты</a></li>
                </ul>
            </div>

        </nav>
    </div>
</div>


<!-- Карусель -->
<?php
// Слайды карусели
$carousel = [
    // Слайд №1
    [
        'content' => '',
        'caption' => '<h3>Прием металлолома от трех тонн по лучшей цене</h3><p>Осуществляем прием металаллома по высоким ценам по городу Москва и Московской области.</p>',
        'options' => []
    ],
    // Слайд №2
    [
        'content' => '',
        'caption' => '<h3>Демонтаж металлоконструкций и металлолома</h3><p>Демонтаж металлома - это сложный технологический процесс, для выполнения которого необходима соответствующая профессиональная подготовка.
                        <br>В таком случае понадобится участие специализированной техники и компетентное выполнение задачи. 
                        <br> Наша организация готова вам предложить качественный сервис по профессиональному демонтажу техники, коммуникаций и других изделий из металла. </p>',
        'options' => []
    ],
    // Слайд №3
    [
        'content' => '',
        'caption' => '<h3>Комната ожидания</h3><p>Наша организация может вам предложить комнату для ожидания, где вы можете восспользоваться Wi-fi с доступом в интернет и автоматы с продуктами.</p>',
        'options' => []
    ]
];

echo Carousel::widget([
    'items' => $carousel,
    'options' => ['class' => 'carousel slide', 'data-interval' => '4000', 'data-ride' => "carousel"],
    'controls' => [
        '<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>',
        '<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>'
    ]

]);
?>

<?= Breadcrumbs::widget([
    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
]) ?>
<?= Alert::widget() ?>
<?= $content ?>

<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="copyright-text">
                    <p>Copyright &copy; <?= date('Y') ?> <a href="mailto:sys.prod@yahoo.com?subject=Вопрос по HTML" target="_parent">Web-разработчик</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Иконки Мессенджеров-->
<div class="messenger hidden-lg">
    <ul>
        <li><a target="_blank" href="whatsapp://send?phone=+79858377147">&nbsp;<img src="css/img/ci/w.png" alt=""></a>
        </li>
        <li><a target="_blank" href="viber://add?number=+79858377147"><img src="css/img/ci/v.png" alt=""></a></li>
        <li><a target="_blank" href="tg://resolve?domain=+79858377147"><span class="rounded-x social_tumblr"></span><img src="css/img/ci/t.png" alt=""></a></li>
    </ul>
</div>

<?php
$script = <<< JS

    $(document).ready(function () {

        // navigation click actions
        $('.scroll-link').on('click', function (event) {
            event.preventDefault();
            var sectionID = $(this).attr("data-id");
            scrollToID('#' + sectionID, 750);
        });
        // scroll to top action
        $('.scroll-top').on('click', function (event) {
            event.preventDefault();
            $('html, body').animate({scrollTop: 0}, 'slow');
        });
        // mobile nav toggle
        $('#nav-toggle').on('click', function (event) {
            event.preventDefault();
            $('#main-nav').toggleClass("open");
        });

        $("#fca_phone_div").fancybox();

    });

    // scroll function
    function scrollToID(id, speed) {
        var offSet = 50;
        var targetOffset = $(id).offset().top - offSet;
        var mainNav = $('#main-nav');
        $('html,body').animate({scrollTop: targetOffset}, speed);
        if (mainNav.hasClass("open")) {
            mainNav.css("height", "1px").removeClass("in").addClass("collapse");
            mainNav.removeClass("open");
        }
    }

    if (typeof console === "undefined") {
        console = {
            log: function () {
            }
        };
    }

JS;
$this->registerJs($script);
?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
