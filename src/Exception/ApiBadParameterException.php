<?php


namespace App\Exception;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class ApiBadParameterException extends HttpException
{

    /**
     * ApiObjectNotFoundException constructor.
     * @param string|null $message
     * @param Throwable|null $previous
     * @param int $code
     * @param array $headers
     */
    public function __construct(string $message = null, Throwable $previous = null, int $code = 0, array $headers = [])
    {
        parent::__construct(Response::HTTP_BAD_REQUEST, $message, $previous, $headers, $code);
    }
}