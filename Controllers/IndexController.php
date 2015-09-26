<?php

namespace Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Silex\ControllerProviderInterface;


class IndexController implements ControllerProviderInterface
{

    public function connect(Application $app)
    {
        $controller = $app['controllers_factory'];

        $controller->get("/", ['\Controllers\IndexController', 'indexAction'])
            ->method('GET')
            ->bind('index');
        return $controller;
    }

    public function indexAction(Application $app, Request $request)
    {
        return new Response($app['twig']->render('index.html.twig', array()));
    }

}
