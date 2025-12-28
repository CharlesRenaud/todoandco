<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Debug\Exception\FlattenException;

class ErrorController extends Controller
{
    public function notFoundAction(FlattenException $exception)
    {
        return $this->render('Exception/error404.html.twig', [
            'status_code' => $exception->getStatusCode(),
        ]);
    }
}
