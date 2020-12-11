<?php


namespace App\Service;


use App\Response\ApiErrorResponse;
use Symfony\Component\Form\FormInterface;

class FormHelper
{
    /**
     * @param FormInterface $form
     * @param $data
     * @param bool $clearMissing
     * @return bool
     */
    function validate(FormInterface $form, $data, bool $clearMissing = true): bool
    {
        $form->submit($data, $clearMissing);
        return false !== $form->isValid();
    }

    /**
     * @param $form
     * @param string $message
     * @return ApiErrorResponse
     */
    function errorsResponse($form, string $message = 'Errors'): ApiErrorResponse
    {
        $list = [];
        foreach ($form->getErrors(true) as $error) {
            $list[] = $error->getOrigin()->getName() . ' : ' . $error->getMessage();
        }
        return new ApiErrorResponse($message, $list);
    }
}