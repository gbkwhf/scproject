<?php

namespace App\TraitCollections;

use Exception;
use Illuminate\Http\Request;
use Acme\Exceptions\ValidationErrorException;
use Acme\Exceptions\SessionInvalidException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait RestExceptionHandlerTrait
{

    protected function getJsonResponseForException(Request $request, Exception $e)
    {
        switch(true) {

            case $this->isModelNotFoundException($e):
                $feature = $this->modelNotFound();
                break;

            case $this->isValidationErrorException($e):
                $feature = $this->validationError();
                break;

            case $this->isInvalidSessionException($e):
                $feature = $this->invalidSession();
                break;

            default:
                $feature = $this->badRequest();
        }

        return $feature;
    }

    protected function badRequest($message='请求失败', $statusCode=400)
    {
        return $this->jsonResponse(['code' => $statusCode,'msg' => $message], 200);
    }

    protected function modelNotFound($message='记录未找到', $statusCode=404)
    {
        return $this->jsonResponse(['code' => $statusCode,'msg' => $message], 200);
    }

    protected function validationError($message='参数错误', $statusCode=400)
    {
        return $this->jsonResponse(['code' => 9999, 'msg' => $message], 200);
    }

    protected function invalidSession($message='Session无效', $statusCode=400)
    {
        return $this->jsonResponse(['code' => 1011, 'msg' => $message], 200);
    }

    protected function jsonResponse(array $payload=null, $statusCode=404)
    {
        $payload = $payload ?: [];

        return response()->json($payload, 200);
    }

    protected function isModelNotFoundException(Exception $e)
    {
        return $e instanceof ModelNotFoundException;
    }

    protected function isValidationErrorException(Exception $e)
    {
        return $e instanceof ValidationErrorException;
    }

    protected function isInvalidSessionException(Exception $e)
    {
        return $e instanceof SessionInvalidException;
    }

}