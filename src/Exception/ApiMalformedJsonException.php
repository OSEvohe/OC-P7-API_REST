<?php


namespace App\Exception;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class ApiMalformedJsonException extends HttpException
{

    /**
     * @param string|null $message
     * @param Throwable|null $previous
     * @param int $code
     * @param array $headers
     */
    public function __construct(string $message = "Error in Json syntax", Throwable $previous = null, int $code = 0, array $headers = [])
    {
        parent::__construct(Response::HTTP_BAD_REQUEST, $message, $previous, $headers, $code);
    }
}