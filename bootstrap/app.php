<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);

$app = new Illuminate\Foundation\Application(
    realpath(__DIR__.'/../')
);


//域名配置
$env_hosts = [
    'test' => ['127.0.0.1'],
    'prod' => ['api.ldlchat.com', 'www.ldlchat.com', 'ldlchat.com', '47.93.86.189']
];
//环境处理
if($app->runningInConsole()) {//命令行
    $app_env = 'test';
} else {//HTTP形式
    $http_host = str_replace(':' . $_SERVER['SERVER_PORT'], '', $_SERVER['HTTP_HOST']);
    if (empty($http_host)) {
        die('[error] no host');
    }
    foreach ($env_hosts as $env => $hosts) {
        if (in_array($http_host, $hosts)) {
            $app_env = $env;
            break;
        }
    }
}
if (empty($app_env) || empty($env_hosts[$app_env])) {
    die('[error] no environment');
}
$app->loadEnvironmentFrom('env.'.$app_env);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);







$app->configureMonologUsing(function($monolog) {
    $formatter = new LineFormatter(null, null, true, true);
    class LogFileHandler extends StreamHandler
    {
        public function __construct($stream, $level, $bubble, $filePermission, $useLocking)
        {
            parent::__construct($stream, $level, $bubble, $filePermission, $useLocking);
        }

        public function handleBatch(array $records)
        {
            $log = "";
            foreach ($records as $record) {
                if (!$this->isHandling($record)) {
                    continue;
                }
                $record = $this->processRecord($record);
                $log .= $this->getFormatter()->format($record);
            }
            // 调用日志写入方法
            $this->write(['formatted' => $log]);
        }
    }
    $LogFileHandler = new LogFileHandler(storage_path().'/logs/'.date('Y-m-d').'.log');
    $LogFileHandler->setFormatter($formatter);
    $monolog->pushHandler(new \Monolog\Handler\BufferHandler($LogFileHandler));
});







/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
