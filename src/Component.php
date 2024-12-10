<?php

namespace Wind\Eloquent;

use AmphpEloquentMysql\Connection;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\DB;
use Wind\Base\Config;

/**
 * Wind Framework Eloquent Component
 */
class Component implements \Wind\Base\Component
{

    public static function provide($app) { }

    public static function start($worker)
    {
        $app = Container::getInstance();

        if (class_exists(Dispatcher::class)) {
            if (!$app->bound('events')) {
                $app->bind('events', fn() => new Dispatcher($app));
            }
        }

        $capsule = new Manager($app);

        $capsule->getDatabaseManager()->extend('ampmysql', function($config, $name) {
            $config['name'] = $name;
            return new Connection($config);
        });

        $configs = di()->get(Config::class)->get('database');

        foreach ($configs as $name => $config) {
            //deal with different parts
            unset($config['type']);
            $config['driver'] = 'ampmysql';
            $capsule->addConnection($config, $name);
        }

        // Set can be visit as global static
        $capsule->setAsGlobal();

        // Boot Eloquent (can ignore this if you use QueryBuilder only)
        $capsule->bootEloquent();

        DB::swap($capsule->getDatabaseManager());
    }

}
