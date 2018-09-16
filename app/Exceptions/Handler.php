<?php

namespace App\Exceptions;


use Exception;

use Acme\Exceptions\SessionInvalidException;
use Acme\Exceptions\ValidationErrorException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyDisplayer;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\TraitCollections\RestTrait;
use App\TraitCollections\RestExceptionHandlerTrait;

class Handler extends ExceptionHandler
{

    use RestTrait, RestExceptionHandlerTrait;
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        HttpException::class,
        ModelNotFoundException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        
        return parent::report($e);
    }
    
    public function render($request, Exception $e)
    {

//           if(!$this->isApiCall($request)) {
//
//                $feature = parent::render($request, $e);
//
//            } else {
//                $feature = $this->getJsonResponseForException($request, $e);
//
//            }
//
//            return $feature;

    	return  parent::render($request, $e);

    }

    protected function renderException($e)
    {
        switch($e){

            case ($e instanceof ModelNotFoundException):
                return $this->respondModelNotFoundHttpException();
                break;

            case ($e instanceof NotFoundHttpException):
                return $this->respondNotFoundHttpException();
                break;

            case ($e instanceof HttpException && 503 == $e->getStatusCode()):
                return $this->respondServerMaintainsException();
                break;

            case ($e instanceof SessionInvalidException):
                return $this->respondInvalidSessionException();
                break;

            case ($e instanceof ValidationErrorException):
                return $this->respondValidationErrorException();
                break;

            default:
                return (new SymfonyDisplayer(config('app.debug')))
                    ->createResponse($e);
        }
    }


    private function respondServerMaintainsException()
    {
        return response()->json([
            'code' => '9997',
            'msg' => '服务器正在维护中，请稍后再试!'
        ]);
    }

    private function respondNotFoundHttpException()
    {
        return response()->json([
            'code' => '9999',
            'msg' => '404路径访问错误'
        ]);
    }
    //todo: delete
    private function respondModelNotFoundHttpException()
    {
        return response()->json([
            'code' => '9999',
            'msg' => '未检索到数据'
        ]);
    }
    //todo: delete
    private function respondValidationErrorException()
    {
        return response()->json([
            'code' => '9999',
            'msg' => '参数错误'
        ]);
    }
    //todo: delete
    private function respondInvalidSessionException()
    {
        return response()->json([
            'code' => '1011',
            'msg' => '无效的session'
        ]);
    }
}
