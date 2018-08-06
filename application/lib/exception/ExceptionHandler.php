<?php
/**
 * Created by PhpStorm.
 * User: 10927
 * Date: 2018/4/15
 * Time: 9:53
 */
namespace app\lib\exception;
use Exception;
use think\Log;
use \think\Request;
use think\exception\Handle;

class ExceptionHandler extends Handle
{
    private $code;
    private $msg;
    private $error_code;
    //  返回客户端请求的当前URL
    public function render(Exception $e)
    {
        /**
         *  对于用户行为而导致的异常（没通过验证器）
         *      不记录日志
         *      向用户返回具体信息
         *  服务器自身一场（代码自身错误，外部接口错误）
         *      记录日志
         *      不向客户端返回错误原因
         */
        if ($e instanceof BaceException)
        {
            //如果是自定义的异常
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->error_code = $e->error_code;
        }else{
            /**
             *  当switch 处于打开状态(与调试模式相对应  可以使用app_debug作为开关变量)
             *      返回默认的错误页面用于调试（开发）
             *  否则
             *      返回异常信息并记录（完成开发）
             */
            if (config('app_debug')){
                return parent::render($e);
            }else{
                $this->code = 500;
                $this->msg = '服务器内部错误';
                $this->error_code = '999';
                $this->recordErrorLog($e);
            }
        }
        $request = Request::instance();
        $result = [
            'msg' => $this->msg,
            'error_code' => $this->error_code,
            'request_url' => $request->url()
        ];
        return json($result,$this->code);
    }
    private function recordErrorLog(Exception $e){
        #先将日志初始化
        Log::init([
            'type' => 'File',
            'path' => LOG_PATH,
            'level' => ['error']
        ]);
        Log::record($e->getMessage(),'error');
    }
}