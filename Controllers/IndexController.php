<?php

namespace Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Silex\ControllerProviderInterface;

use Domain\Repositories\ElasticSearchClientRepository;
use Domain\Services\ElasticSearchClientService;

class IndexController implements ControllerProviderInterface
{

    public function connect(Application $app)
    {
        $controller = $app['controllers_factory'];

        $controller->get("/", ['\Controllers\IndexController', 'indexAction'])
            ->method('GET')
            ->bind('index');

        $controller->get("create", ['\Controllers\IndexController', 'createAction'])
            ->method('GET')
            ->bind('index.create');

        $controller->get("search", ['\Controllers\IndexController', 'searchAction'])
            ->method('GET')
            ->bind('index.search');

        return $controller;
    }

    public function indexAction(Application $app, Request $request)
    {

        $elasticSearchClient = new \Domain\Services\ElasticSearchClientService(new \Domain\Repositories\ElasticSearchClientRepository($app['config']));

        return new Response($app['twig']->render('index.html.twig', array()));
    }

    public function createAction(Request $request, Application $app)
    {
        $formCreate = new \Provider\Form\CreateClientFormProvider($app);

        $form = $formCreate->create();

        return new Response($app['twig']->render('create.html.twit', ['form' => $form->createView()]));

    }

    public function searchAction(Request $request, Application $app)
    {
        $formSearch = new \Provider\Form\SearchClientFormProvider($app);

        $form = $formSearch->create();

        return new Response($app['twig']->render('search.html.twit', ['form' => $form->createView()]));

    }

}
