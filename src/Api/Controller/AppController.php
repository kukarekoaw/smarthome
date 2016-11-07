<?php

namespace Api\Controller;


use Symfony\Component\HttpFoundation\Response;
use Silex\Application;


class AppController
{
    public function homeAction()
    {
        return new Response("AppController::homeAction");
    }

    public function helloAction(Application $app, $name)
    {
        //return new Response("Hello -> " . $app->escape($name));
        $result = ["name"=>$app->escape($name), "slug"=>'salt'];
        return $app->json($result);
    }
}