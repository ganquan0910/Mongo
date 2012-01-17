<?php

namespace Pegas\Mongo;

use Mongo;
use Silex\Application;
use Silex\ServiceProviderInterface;

class MongoServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['mongo.connection'] = $app->share(function () use ($app) {
            return new Mongo($app['mongo.server'], $app['mongo.options']);
        });

        $app['mongo'] = $app->share(function () use ($app) {
            return $app['mongo.connection']->selectDb($app['mongo.default_database']);
        });
    }
}
