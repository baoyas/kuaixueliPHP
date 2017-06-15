<?php
namespace App\Http\Middleware;
use DB;
use Closure;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class LogAfterRequest {

    public function handle(Request $request, Closure $next)
    {
        DB::enableQueryLog();
        return $next($request);
    }

    public function terminate(Request $request, $response)
    {
        $formatter = new LineFormatter(null, null, true, true);
        $log = new Logger('access');
        $log->pushHandler((new StreamHandler(storage_path().'/logs/access_'.date('Y-m-d').'.log', Logger::INFO))->setFormatter($formatter) );
        $jsonHeader = json_encode($request->headers->all(), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        $jsonRequest = print_r($request->all(), true);
        $logFun = 'info';
        if(stripos($response->headers->get('Content-Type'), 'image')!==false) {
            $jsonResponse = 'content-type: '.$response->headers->get('Content-Type');
        } else if($response instanceof JsonResponse) {
            $jsonResponse = json_encode($response->getData(), JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
            if($response->getData()->status!=='success') {
                $logFun = 'error';
            }
        } else if($response instanceof \Dingo\Api\Http\Response) {
            if($response->headers->get('Content-Type')=='application/json') {
                $jsonResponse = json_decode($response->getContent(), true);
                if($jsonResponse['status']!=='success') {
                    $logFun = 'error';
                }
                $jsonResponse = json_encode($jsonResponse, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
            } else {
                $jsonResponse = $response->getContent();
            }
        } else if($response instanceof Response) {
            $jsonResponse = $response->getContent();
        }
        $sqlArr = [];
        $queries = DB::getQueryLog();
        foreach ($queries as $k=>$query) {
            $sql = $query['query'];       //查询语句sql
            $params = $query['bindings']; //查询参数
            $sql = str_replace('?', "'%s'", $sql);
            array_unshift($params, $sql);
            $sqlArr[] = call_user_func_array('sprintf', $params)."; {$query['time']}";
            unset($queries[$k]['bindings'], $queries[$k]['time']);
        }
        $jsonSql = print_r($sqlArr, true);
        app('log')->$logFun('fullurl==>'.$request->fullUrl()."\nhttpmethod==>".$request->getMethod()."\nheader==>{$jsonHeader}\nrequest==>{$jsonRequest}\nresponse==>{$jsonResponse}\nsql==>{$jsonSql}");
    }

}