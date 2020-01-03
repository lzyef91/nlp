<?php

namespace Nldou\Nlp;

use Nldou\Nlp\Nlp;

use Illuminate\Contracts\Support\DeferrableProvider;

class ServiceProvider extends \Illuminate\Support\ServiceProvider implements DeferrableProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/Config/nlp.php', 'nlp'
        );

        $this->app->singleton(Nlp::class, function($app){
            $defaultGate = config('nlp.default_gateway');

            $gatewayOptions = config('nlp.gateways');

            $debug = config('nlp.debug');

            return new Nlp($defaultGate, $gatewayOptions, $debug);
        });

        $this->app->alias(Nlp::class, 'nlp');
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/Config/nlp.php' => config_path('nlp.php')
        ]);
    }

    public function provides()
    {
        return [Nlp::class, 'nlp'];
    }
}