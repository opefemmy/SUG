<?php

namespace App\Exceptions\Payments;

use Exception;

class PaymentGatewayException extends Exception
{
    protected $responseBody;
    protected $statusCode;

    public function __construct($message = "", $statusCode = null, $responseBody = null, $code = 0, Exception $previous = null)
    {
        $this->statusCode = $statusCode;
        $this->responseBody = $responseBody;
        parent::__construct($message, $code, $previous);
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getResponseBody()
    {
        return $this->responseBody;
    }
}
