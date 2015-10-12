<?php

namespace app;

use Silex;
use app\ControllerProvider\Provider;
use Silex\Provider\TwigServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\Yaml\Parser;
use Silex\Application as SilexApplication;

class Application extends SilexApplication {

    public function __construct()
    {
        parent::__construct();
        $this->registerConfig();
        $this->registerProviders();
        $this->mountControllers();
        $this->registerTwig();
    }

    public function registerConfig()
    {
        $yaml = new Parser();
        $config = $yaml->parse(file_get_contents(__DIR__ . '/../config/config.yml'));
        $this['config'] = $config;
    }

    public function registerProviders()
    {
        $this->register(new \Silex\Provider\ServiceControllerServiceProvider());
        $this->register(new \Provider\Service\ControllerProviders());
        $this->register(new \Silex\Provider\SwiftmailerServiceProvider());
        $this->register(new \Silex\Provider\ValidatorServiceProvider());
        $this->register(new \Silex\Provider\FormServiceProvider());
        $this->register(new \Silex\Provider\TranslationServiceProvider(), ['translator.messages' => [],]);
    }

    private function mountControllers()
    {
        $this->mount('/', $this["provider.controller.home"]);
        $this->mount('/clients', $this["provider.controller.clients"]);
    }

    private function registerTwig()
    {

        $this->register(new Silex\Provider\TwigServiceProvider(), [
            "twig.path" => __DIR__.'/../templates/'
        ]);

        $this['twig'] = $this->extend('twig', function ($twig, $app) {

            $twig->addGlobal('navigation', $app['request']->getRequestUri());

            $twig->addFunction(new \Twig_SimpleFunction('asset', function ($asset) use ($app) {
                return $app['request_stack']->getMasterRequest()->getBasepath().'/'.ltrim($asset, '/');
            }));

            return $twig;
        });

        $this['templating.loader'] = $this->share(function() {
            return new FilesystemLoader( __DIR__ . '/../templates/');
        });
    }

}
