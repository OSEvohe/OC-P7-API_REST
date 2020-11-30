<?php


namespace App\Service;


use App\Response\ApiErrorResponse;
use Symfony\Component\Form\FormInterface;

class FormHelper
{

    function validate(FormInterface $form, $data, bool $clearMissing = true){
        $form->submit($data, $clearMissing);
        return false !== $form->isValid();
    }

    function errorsResponse($form, string $message = 'Erreur')
    {
        $list = [];
        foreach ($form->getErrors(true) as $error) {
            $list[] = $error->getOrigin()->getName() . ' : ' . $error->getMessage();
        }
        return new ApiErrorResponse($message, $list);
    }
}