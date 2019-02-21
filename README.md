say hello for Yii2.0
====================
say hello for Yii2.0

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

需要加载依赖扩展

```
php composer.phar require --prefer-dist ipaya/yii2-swoole
```

or add

```
"ipaya/yii2-swoole": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php

main.php 一个简单的配置

return [
    'id' => 'console',
    'basePath' => dirname(__DIR__),
    'components' => [
        'cache' => [
             // 'class' => 'yii\caching\FileCache',
             'class' => 'yii\redis\Cache',
        ],
		'request' => [
            'class'=>yii\console\Request::class,
            'isConsoleRequest' => true,
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
        ],
        'wechat'=>[ // 多平台 客服
            'class'=>'backend\modules\kfservice\models\WxConfig',
            'wechat_id'=>21,
        ],
        'timer' => [ //毫秒定时器任务
            'class' => 'iPaya\Swoole\Timer',
            'listen' => [
                ['127.0.0.1', 9051]
            ]
        ],
        'asyncTask' => [ //AsyncTask 服务
            'class' => 'iPaya\Swoole\AsyncTask\Server',
            'queue' => 'queue',
            'listen' => [
                ['127.0.0.1', 9052]
            ],
        ],
        'websocket' => [ //客服 websocket 服务配置
            'class' => 'iPaya\Swoole\WebSocket\Server',
            'swooleOptions'=>[
                'worker_num'=>8,
                'reactor_num'=>2,
            ],
            'listen' => [
                ['0.0.0.0', 9053]
            ]
        ]
    ]
];
