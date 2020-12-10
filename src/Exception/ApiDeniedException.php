<?php


namespace App\Exception;


use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ApiDeniedException extends \Symfony\Component\HttpKernel\Exception\HttpException
{
    /**
     * @param string|null $message
     * @param Throwable|null $previous
     * @param int $code
     * @param array $headers
     */
    public function __construct(string $message = null, Throwable $previous = null, int $code = 0, array $headers = [])
    {
        parent::__construct(Response::HTTP_FORBIDDEN, $message, $previous, $headers, $code);
    }
}