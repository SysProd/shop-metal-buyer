<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/style/fontAwesome.css',
        'css/style/hero-slider.css',
        'css/style/tooplate-style.css',
        'css/style/carousel-style.css',
        'css/style/iconfont/style.css',
        'css/style/other.css',
        'https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800',
    ];

    public $js = [
        'js/main.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
