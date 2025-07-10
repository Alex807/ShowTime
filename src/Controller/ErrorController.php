<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ErrorController extends AbstractController
{
    public function show(FlattenException $exception, Request $request): Response
    {
        $statusCode = $exception->getStatusCode();

        switch ($statusCode) {
            case 404:
                return $this->render('errors/error404.html.twig', [
                    'status_code' => $statusCode,
                    'status_text' => Response::$statusTexts[$statusCode] ?? 'Error',
                ]);

            case 403:
                return $this->render('errors/error403.html.twig', [
                    'status_code' => $statusCode,
                    'status_text' => Response::$statusTexts[$statusCode] ?? 'Error',
                ]);

            default:
                return $this->render('errors/error500.html.twig', [
                    'status_code' => $statusCode,
                    'status_text' => Response::$statusTexts[$statusCode] ?? 'Error',
                ]);
        }
    }
}
