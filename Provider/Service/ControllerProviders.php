<?php

namespace Provider\Service;

use \Silex\Application;
use \Silex\ServiceProviderInterface;
use \Provider\Controller;

class ControllerProviders implements ServiceProviderInterface
{
    public function register(Application $app) {

        $app["provider.controller.home"] = $app->share(function() {
            return new \Controllers\IndexController();
        });

    }

    public function boot(Application $app) {

    }

}
