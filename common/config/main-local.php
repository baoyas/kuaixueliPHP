<?php

if (YII_DEBUG) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = array(
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '202.91.181.194', '192.168.56.*'],
    );

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = array(
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '202.91.181.194', '192.168.56.*'],
    );
}

return $config;