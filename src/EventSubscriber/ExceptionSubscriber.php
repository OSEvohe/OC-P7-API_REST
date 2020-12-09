<?php


namespace App\EventSubscriber;


use App\Exception\ApiObjectNotFoundException;
use App\Response\ApiErrorResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if ($exception instanceof NotFoundHttpException) {
            $data = [
                'status' => $exception->getStatusCode(),
                'message' => 'No resource available at this URI',
                'errors' => [$exception->getMessage()]
            ];
        } elseif ($exception instanceof ApiObjectNotFoundException || $exception instanceof HttpException) {
            $data = [
                'status' => $exception->getStatusCode(),
                'message' => 'Resource not found',
                'errors' => [$exception->getMessage()]
            ];
        } else {
            $data = [
                'status' => 500,
                'message' => $exception->getMessage(),
                'errors' => $exception->getTrace()
            ];
        }

        $event->setResponse(new ApiErrorResponse(($data['message']), $data['errors'], $data['status']));
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }
}