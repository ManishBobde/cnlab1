<?php
/**
 * Created by PhpStorm.
 * User: Hash
 * Date: 19-07-2015
 * Time: 21:45
 */

namespace App\Exceptions;


class ErrorCodes {

    /*HTTP Status Code	Reason	Response Model
    400	Bad Request - Request does not have a valid format, all required parameters, etc.
    401	Unauthorized Access - No currently valid session available.
    404	Not Found - Resource not found
    500	System Error - Specific reason is included in the error message*/


    protected $errorCode,$errorTitle;

    /**
     * @return errorCode
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @param mixed errorCode
     * @return $this
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    /**
     * @param mixed errorTitle
     * @return $this
     */
    public function setErrorTitle($errorTitle)
    {
        $this->errorTitle = $errorTitle;

        return $this;
    }

    /**
     * @return errorTitle
     */
    public function getErrorTitle()
    {
        return $this->errorTitle;
    }



    /**Method to respond if resource not found
     * @param $message
     * @return mixed
     */
    public function respondNotFound($message = 'Not Found!',$errorTitle){

        return $this->setErrorCode(IlluminateResponse::HTTP_NOT_FOUND)->setErrorTitle($errorTitle)
            ->respondWithError($message);
    }

    /**
     * @param $message
     * @return response
     */
    public function respondInternalError($message = 'Internal Error'){

        return $this->setErrorCode(IlluminateResponse::HTTP_INTERNAL_SERVER_ERROR)->respondWithError($message);
    }

    /**
     * Generic method for response
     * @param $data
     * @param array $headers
     * @return
     * @internal param $message
     */
    public function respond($data ,$headers=[])
    {

        return Response::json($data, $this->getStatusCode(), $headers);
    }

    /**
     * @param $message
     * @return response
     */
    public function respondWithError($message){

        return $this->respond([
            'error'=>[
                'errorMessage'=>$message,
                'errorCode'=>$this->getStatusCode(),
                'errorTitle'=>$this->getErrorTitle()
            ]
        ]);
    }




}