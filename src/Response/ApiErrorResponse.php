<?php


namespace App\Response;


use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiErrorResponse extends JsonResponse
{

    /**
     * ApiResponse constructor.
     *
     * @param string $message
     * @param array $errors
     * @param int $status
     * @param array $headers
     */
    public function __construct(string $message, array $errors = [], int $status = Response::HTTP_BAD_REQUEST, array $headers = [])
    {
        parent::__construct($this->format($message, $errors), $status, $headers);
    }

    /**
     * Format the API response.
     *
     * @param string $message
     * @param array $errors
     *
     * @return array
     */
    private function format(string $message, array $errors = [])
    {
        $response = [
            'message' => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return $response;
    }
}