<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Everyman\Neo4j\Client as Neo4jClient;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Provides low-level Neo4j client
        $this->app->singleton('neo4j', function ($app) {
            $config = config('database.connections.neo4j');

            $client = new Neo4jClient($config['host'], $config['port']);
            $client->getTransport()
                   ->setAuth($config['username'], $config['password']);

            return $client;
        });
    }
}
