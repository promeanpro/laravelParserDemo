<?php

namespace App\Providers;

use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind(SerializerInterface::class, static function() {
            $normalizers = [new ObjectNormalizer(), new ArrayDenormalizer()];
            return new Serializer($normalizers);
        });


        if ($this->app->isLocal()) {
            $this->registerDev();
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    private function registerDev() : void
    {
        $this->app->register(IdeHelperServiceProvider::class);
    }
}
