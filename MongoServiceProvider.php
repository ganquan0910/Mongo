<?php

namespace Mongo;

use Mongo;
use Silex\Application;
use Silex\ServiceProviderInterface;

class MongoServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['mongo.connection'] = $app->share(function () use ($app) {
            $server = isset($app['mongo.server']) ? $app['mongo.server'] : 'mongodb://localhost:27017';
            $options = isset($app['mongo.options']) ? $app['mongo.options'] : array('connect' => true);

            return new Mongo($server, $options);
        });

        $app['mongo'] = $app->share(function () use ($app) {
            return $app['mongo.connection']->selectDb($app['mongo.default_database']);
        });
    }
}
