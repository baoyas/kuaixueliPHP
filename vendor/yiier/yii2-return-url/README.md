Yii2 Login ReturnUrl filter
===========================
Keeps current URL in session for login actions so we can return to it if needed.

中文说明：登录之后自动跳转登录之前的页面

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist yiier/yii2-return-url "*"
```

or add

```
"yiier/yii2-return-url": "*"
```

to the require section of your `composer.json` file.


Usage
-----

In your controller add ReturnUrl filter to behaviors:

```php
public function behaviors()
{
    return [
        'returnUrl' => [
            'class' => 'yiier\returnUrl\ReturnUrl',
            'uniqueIds' => ['site/qrcode', 'site/login', 'user/security/auth'] // 过滤掉不需要的 controller/action
        ],
    ];
}
```

For access to previously visited url:

```php
Yii::$app->user->getReturnUrl();
```