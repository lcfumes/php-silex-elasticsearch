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
    }

    private function mountControllers()
    {
        $this->mount('/', $this["provider.controller.home"]);
    }

    private function registerTwig()
    {
        $this->register(new Silex\Provider\TwigServiceProvider(), [
            "twig.path" => __DIR__.'/../templates/'
        ]);

        $this['twig'] = $this->extend('twig', function ($twig, $app) {
            $twig->addFunction(new \Twig_SimpleFunction('asset', function ($asset) use ($app) {
                return $app['request_stack']->getMasterRequest()->getBasepath().'/'.ltrim($asset, '/');
            }));

            return $twig;
        });
    }

}
