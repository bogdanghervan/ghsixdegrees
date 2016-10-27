<?php

namespace App\Http;

/**
 * Trait that controllers, controller middleware or error handlers
 * can employ to respond with JSON.
 */
trait RespondsWithJson
{
    /**
     * @var int
     */
    protected $statusCode = 200;
    
    /**
     * Sets status code.
     * 
     * @param  int $statusCode
     * @return Controller
     */
    protected function setStatusCode($statusCode)
    {
        $this->statusCode = (int) $statusCode;
        
        return $this;
    }
    
    /**
     * Retrieves status code.
     * 
     * @return int
     */
    protected function getStatusCode()
    {
        return $this->statusCode;
    }
    
    /**
     * Prepares a response for an array and returns it.
     * 
     * @param  array $array
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithArray(array $array)
    {
        return response()->json($array, $this->statusCode);
    }
    
    /**
     * Prepares an error response and returns it.
     * 
     * @param  string $message
     * @param  int $errorCode
     * @param  string $param (optional, default is null)
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithError($message, $errorCode, $param = null)
    {
        $error = [
            'error' => [
                'code' => $errorCode,
                'message' => $message
            ]
        ];
        if ($param) {
            $error['error']['param'] = $param;
        }
        
        return $this->respondWithArray($error);
    }
    
    /**
     * Prepares a resource not found response (HTTP status code 404) and returns it.
     *
     * @param  string $message (optional, default is "Resource not found.")
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorNotFound($message = 'Resource not found.')
    {
        return $this->setStatusCode(404)->respondWithError($message, 'ResourceNotFound');
    }
    
    /**
     * Prepares an invalid parameter response (HTTP status code 400) and returns it.
     *
     * @param  string $param The name of the parameter that is invalid
     * @param  string $message The message (optional, default is "Invalid parameter.")
     * @return \Illuminate\Http\JsonResponse
     */
    protected function errorInvalidParameter($param, $message = 'Invalid parameter.')
    {
        return $this->setStatusCode(400)->respondWithError($message, 'InvalidParameter', $param);
    }
} 
