<?php


namespace App\EventSubscriber;


use App\Exception\ApiObjectNotFoundException;
use App\Response\ApiErrorResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof NotFoundHttpException || $exception instanceof RouteNotFoundException) {
            $data = $this->formatException($exception->getStatusCode(), 'No resource available at this URI', [$exception->getMessage()]);
        } elseif ($exception instanceof ApiObjectNotFoundException) {
            $data = $this->formatException($exception->getStatusCode(), 'Resource not found', [$exception->getMessage()]);
        } elseif ($exception instanceof HttpException) {
            $data = $this->formatException($exception->getStatusCode(), $exception->getMessage());
        } else {
            $data = $this->formatException(500, $exception->getMessage(), $exception->getTrace());
        }

        $event->setResponse(new ApiErrorResponse(($data['message']), $data['errors'], $data['status']));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }

    private function formatException($status, $message, $errors = []){
        return [
            'status' => $status,
            'message' => $message,
            'errors' => $errors
        ];
    }
}