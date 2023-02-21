<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use kartik\widgets\AlertBlock;
use SysProd\tableCreate\CreateTable;


/* @var $this yii\web\View */

$this->title = 'Прием и демонтаж металлолома в Москве и обл. Пункт приема: 8(985)837-71-47';
?>

<?= AlertBlock::widget([
    'useSessionFlash' => true,
    'type' => AlertBlock::TYPE_GROWL,
]);
?>

<?php
Modal::begin([
    'header' => '<p class="appointment"><i class="fa fa-sign-in icon"></i><span>Заявка</span></p>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><i class="icon-close-cross"></i></button>',
    'closeButton' => false,
    'toggleButton' => false,
    'size' => Modal::SIZE_LARGE,
    'options' => [
        'id' => 'applicationForm',
        'tabindex' => false // important for Select2 to work properly
    ],
]); ?>

<div class="container-fluid">

</div>

<?php Modal::end(); ?>

<!-- Наши Услуги -->
<div id="services" class="page-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-heading">
                    <h4>Наши услуги</h4>
                    <div class="line-dec"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="services-block col-md-4 col-sm-6 col-xs-12">
                <div class="services-data">
                    <div class="img">
                        <?= Html::a(Html::img('css/img/services-img-01.jpg', ['alt' => 'Прием лома цветных металлов']), '#', [
                            'class' => 'image-scale-color',
                            'data-toggle' => 'modal',
                            'data-target' => '#applicationForm',
                            'value' => Url::toRoute(["site/send-data"]),
                            'onclick' => '$(".container-fluid").load($(this).attr("value"));',
                        ]) ?>
                        <i class="icon icon-mark"></i>
                    </div>
                    <h4>Прием лома цветных металлов</h4>
                    <p> все: от алюминия до цинка(12A цинк, 12АЕ и т.д.) </p>
                </div>
            </div>
            <div class="services-block col-md-4 col-sm-6 col-xs-12">
                <div class="services-data">
                    <div class="img">
                        <?= Html::a(Html::img('css/img/services-img-02.jpg', ['alt' => 'Прием лома черных металлов']), '#', [
                            'class' => 'image-scale-color',
                            'data-toggle' => 'modal',
                            'data-target' => '#applicationForm',
                            'value' => Url::toRoute(["site/send-data"]),
                            'onclick' => '$(".container-fluid").load($(this).attr("value"));',
                        ]) ?>
                        <i class="icon icon-mark"></i>
                    </div>
                    <h4>Прием лома черных металлов</h4>
                    <p> 3А, 3А2, 5А, 5A2, 12A, 12АE, 17A, 22A</p>
                </div>
            </div>
            <div class="services-block col-md-4 col-sm-6 col-xs-12">
                <div class="services-data">
                    <div class="img">
                        <?= Html::a(Html::img('css/img/recycling_of_cars.jpg', ['alt' => 'Утилизация автомобилей']), '#', [
                            'class' => 'image-scale-color',
                            'data-toggle' => 'modal',
                            'data-target' => '#applicationForm',
                            'value' => Url::toRoute(["site/send-data"]),
                            'onclick' => '$(".container-fluid").load($(this).attr("value"));',
                        ]) ?>
                        <i class="icon icon-mark"></i>
                    </div>
                    <h4>Утилизация автомобилей</h4>
                    <p> оргтехники, судов, ж/д транспорта и другое с соответвующими документами </p>
                </div>
            </div>
            <div class="services-block col-md-4 col-sm-6 col-xs-12">
                <div class="services-data">
                    <div class="img">
                        <?= Html::a(Html::img('css/img/equipment_rental.jpg', ['alt' => 'Аренда спецтехники']), '#', [
                            'class' => 'image-scale-color',
                            'data-toggle' => 'modal',
                            'data-target' => '#applicationForm',
                            'value' => Url::toRoute(["site/send-data"]),
                            'onclick' => '$(".container-fluid").load($(this).attr("value"));',
                        ]) ?>
                        <i class="icon icon-mark"></i>
                    </div>
                    <h4>Аренда спецтехники</h4>
                    <p> закажите эту услугу прямо сейчас и получи дополнительную скидку </p>
                </div>
            </div>
            <div class="services-block col-md-4 col-sm-6 col-xs-12">
                <div class="services-data">
                    <div class="img">
                        <?= Html::a(Html::img('css/img/disassembly.jpg', ['alt' => 'Демонтаж и порезка']), '#', [
                            'class' => 'image-scale-color',
                            'data-toggle' => 'modal',
                            'data-target' => '#applicationForm',
                            'value' => Url::toRoute(["site/send-data"]),
                            'onclick' => '$(".container-fluid").load($(this).attr("value"));',
                        ]) ?>
                        <i class="icon icon-mark"></i>
                    </div>
                    <h4>Демонтаж и порезка</h4>
                    <p> металлоконструкций любой сложности </p>
                </div>
            </div>
            <div class="services-block col-md-4 col-sm-6 col-xs-12">
                <div class="services-data">
                    <div class="img">
                        <?= Html::a(Html::img('css/img/services-img-06.jpg', ['alt' => 'Вывоз лома спецтехникой']), '#', [
                            'class' => 'image-scale-color',
                            'data-toggle' => 'modal',
                            'data-target' => '#applicationForm',
                            'value' => Url::toRoute(["site/send-data"]),
                            'onclick' => '$(".container-fluid").load($(this).attr("value"));',
                        ]) ?>
                        <i class="icon icon-mark"></i>
                    </div>
                    <h4>Вывоз металла</h4>
                    <p> Вывозим лом спецтехникой быстро, аккуратно </p>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="divider-lg"></div>
        <div class="row">
            <div class="btn-inline text-center">
                <?= Html::a('<span>Перезвонить Вам?</span>', '#', [
                    'class' => 'btn btn-invert',
                    'data-toggle' => 'modal',
                    'data-target' => '#applicationForm',
                    'value' => Url::toRoute(["site/send-data"]),
                    'onclick' => '$(".container-fluid").load($(this).attr("value"));',
                ]) ?>
            </div>
        </div>
    </div>
</div>

<!-- Цены на металл -->
<div id="price" class="page-section block bg-3">
    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <div class="section-heading">
                    <h4 style="color: #ffffff;text-shadow: rgb(48, 34, 34) 1px 1px 1px;">Цены на металл в Москве и области</h4>
                    <div class="line-dec" style="background-color: #fede00;"></div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="table-block">

                <?php

                $thead = [
                    'items' => [
                        ['label' => 'Вид Металлолома. Основные Характеристики'],
                        ['label' => '<div class="o">от 1 т</div><div class="c">(цена за 1 тонну)</div>', 'encode' => false],
                        ['label' => '<div class="o">от 5 т</div><div class="c">(цена за 1 тонну)</div>', 'encode' => false],
                        ['label' => '<div class="o">от 10 т</div><div class="c">(цена за 1 тонну)</div>', 'encode' => false],
                    ],

                ];

                $tbody = [
                    'items' => [
                                [
                                    [
                                        'label' => 'Габаритные части стального лома',
                                        'small' => '3А',
                                        'icon' => Html::img('http://гор-мет.рф/img/3А.jpg', ['alt' => 'Прием лома цветных металлов']),
                                        'ul' =>
                                            [
                                                'Габаритные размеры не более 1500х500х500 мм',
                                                'Толщина металла не менее 6 мм',
                                                'Пруток и арматура более 20 мм',
                                                'Трубы наружным диаметром от 150 мм и более должны быть распущены вдоль',
                                            ],
                                    ],
                                    [
                                        'label' => '10 500 руб',
                                    ],
                                    [
                                        'label' => '11 600 руб',
                                    ],
                                    [
                                        'label' => '11 800 руб',
                                    ],
                                ],
                                [
                                    [
                                        'label' => 'Габаритные части стального лома',
                                        'small' => '3А2',
                                        'icon' => Html::img('http://гор-мет.рф/img/3А2.jpg', ['alt' => 'Прием лома цветных металлов']),
                                        'ul' =>
                                            [
                                                'Габаритные размеры не более 1500х500х500 мм',
                                                'Толщина металла от 4 до 6 мм',
                                                'Пруток и арматура диаметром от 10 до 20 мм',
                                                'Трубы наружным диаметром от 150 мм и более должны быть распущены вдоль',
                                            ],
                                    ],
                                    [
                                        'label' => '10 000 руб',
                                    ],
                                    [
                                        'label' => '10 500 руб',
                                    ],
                                    [
                                        'label' => '11 100 руб',
                                    ],
                                ],
                                [
                                    [
                                        'label' => 'Негабаритные части стального лома',
                                        'small' => '5А',
                                        'icon' => Html::img('http://гор-мет.рф/img/5А.jpg', ['alt' => 'Прием лома цветных металлов']),
                                        'ul' =>
                                            [
                                                'Габаритные размеры более 1500х500х500 мм',
                                                'Толщина металла более 6 мм',
                                                'Пруток и арматура длинной более 1500 мм и диаметром более 20 мм',
                                                'Стальные трубы диаметром более 150 мм - любой длинны',
                                            ],
                                    ],
                                    [
                                        'label' => '10 300 руб',
                                    ],
                                    [
                                        'label' => '10 950 руб',
                                    ],
                                    [
                                        'label' => '11 500 руб',
                                    ],
                                ],
                                [
                                    [
                                        'label' => 'Габаритные части стального, тонколистового лома',
                                        'small' => '12А',
                                        'icon' => Html::img('http://гор-мет.рф/img/12А.jpg', ['alt' => 'Прием лома цветных металлов']),
                                        'ul' =>
                                            [
                                                'Габаритные размеры не более 1500х500х500 мм',
                                                'Толщина металла от 0,5 до 4 мм',
                                            ],
                                    ],
                                    [
                                        'label' => '6 500 руб',
                                    ],
                                    [
                                        'label' => '7 000 руб',
                                    ],
                                    [
                                        'label' => '7 500 руб',
                                    ],
                                ],
                                [
                                    [
                                        'label' => 'Габаритные части чугунного лома',
                                        'small' => '17А',
                                        'icon' => Html::img('http://гор-мет.рф/img/17А.jpg', ['alt' => 'Прием лома цветных металлов']),
                                        'ul' =>
                                            [
                                                'Габаритные размеры не более 1500х500х500 мм',
                                                'Весом куска от 0,5 до 200 кг',
                                            ],
                                    ],
                                    [
                                        'label' => '10 300 руб',
                                    ],
                                    [
                                        'label' => '10 800 руб',
                                    ],
                                    [
                                        'label' => '11 000 руб',
                                    ],
                                ],

                    ],
                ];

                echo CreateTable::widget([
                    'thead' => $thead,
                    'tbody' => $tbody,
                ]);
                ?>

            </div>
        </div>
    </div>


<!--    <div class="container">
        <div class="projects-holder-3">
            <div class="payment">
                <img class="wow zoomIn animated" data-wow-offset="150" src="css/img/payment-img.png" alt="прием лома в москве" style="visibility: visible; animation-name: zoomIn;">
            </div>
        </div>
    </div>-->


    <div class="container">
        <div class="divider-lg"></div>
        <div class="row">
            <div class="text-center grey">
                <?= Html::a('<span>Подробный Прайс</span>', '#', [
                    'class' => 'btn btn-border',
                    'data-toggle' => 'modal',
                    'data-target' => '#applicationForm',
                    'value' => Url::toRoute(["site/send-data"]),
                    'onclick' => '$(".container-fluid").load($(this).attr("value"));',
                ]) ?>
            </div>
        </div>
    </div>



</div>

<!-- О Компании -->
<div id="about" class="page-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-heading">
                    <h4>Почему <span style="color: rgba(255, 2, 15, 0.86);">"Гор-Мет"</span> выбирают</h4>
                    <div class="line-dec"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="service-item second-service">
                    <i class="fa fa-calendar style-icon"></i>
                    <h4>Работаем круглосуточно</h4>
                    <p>Ежедневно без перерыва и выходных принимаем металл</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="service-item first-service">
                    <i class="fa fa-credit-card style-icon"></i>
                    <h4>Быстрый расчет</h4>
                    <p>Оплата за металлолом наличными и безналичной формой оплаты на банковские счета или карты, по
                        Вашему желанию.</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="service-item third-service">
                    <i class="fa fa-money style-icon"></i>
                    <h4>Высокие цены</h4>
                    <p>Цены на металл формируются от биржевых котировок, особые условия для Вас при оптовых поставках!</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="service-item third-service">
                    <i class="fa fa-handshake-o style-icon"></i>
                    <h4>Честность</h4>
                    <p>Взвесим честно Ваш металлалом с точностью до 1 кг</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Почему выбирают... -->
<div id="fun-facts" class="page-section">
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="fact-item">
                    <i class="fa fa-users icon" aria-hidden="true"></i>
                    <span class="counter" data-count="1926">1926+</span>
                    <div><h5>Постояных довольных клиентов</h5></div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="fact-item">
                    <i class="fa fa-recycle icon" aria-hidden="true"></i>
                    <span class="counter" data-count="100000">100000+</span>
                    <div><h5>Переработанных тонн отходов металлолома</h5></div>
                </div>
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="fact-item">
                    <i class="fa fa-map-marker icon" aria-hidden="true"></i>
                    <span class="counter" data-count="3">3+</span>
                    <div><h5>Пункты приема металлолома</h5></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Котакты -->
<div id="contact" class="page-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-heading">
                    <h4>Адреса пунктов приёма</h4>
                    <div class="line-dec"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <!-- Yandex Maps counter -->
                <div class="map">
                    <script type="text/javascript" charset="utf-8" async
                            src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3Abf7e37f130535f08159655a3639304fd7e5cda5ae4f7b88b7185141b154c1d48&amp;width=555&amp;height=400&amp;lang=ru_RU&amp;scroll=true"></script>
                </div>
                <!-- Yandex Maps counter -->
            </div>
            <div class="col-md-6">
                <div class="row footer-col-left">
                    <div class="inside">
                        <h2 class="h-phone"><a href="tel:89858377147">
                                <i class="color fa fa-phone-square  fa-rotate"></i>
                                <span class="number white">&nbsp;&nbsp;&nbsp;8 (985) 837-71-47</span>
                                <br>
                                <i class="color fa fa-clock-o icon fa-flip-horizontal"></i><span class="number white">&nbsp;&nbsp;&nbsp;круглосуточно</span></a>
                        </h2>
                        <div class="contact-info"><i class="icon fa fa-map-marker" style="color: #fe0000e6;"></i>&nbsp;г.
                            Москва, Путевой проезд
                        </div>
                        <div class="contact-info">
                            <!--<i class="icon icon-locate"></i>-->
                            <i class="icon fa fa-volume-control-phone  fa-rotate"
                               title="Звоните для уточнения пунктов приема" style="color: #2da826;"></i>
                            &nbsp; Полный список пунктов приема узнайте по телефону
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>