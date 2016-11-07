<?php
namespace Api\Controller;

use Symfony\Component\HttpFoundation\Response;

class ApiController
{
    public function listAction()
    {
        return new Response("AppController::listAction");
    }
}