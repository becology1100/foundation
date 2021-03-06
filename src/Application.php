<?php namespace Orchestra\Foundation;

use Illuminate\Events\EventServiceProvider;
use Orchestra\Routing\RoutingServiceProvider;
use Illuminate\Foundation\Application as BaseApplication;
use Orchestra\Contracts\Foundation\DeferrableServiceContainer;

class Application extends BaseApplication implements DeferrableServiceContainer
{
    /**
     * Register all of the base service providers.
     *
     * @return void
     */
    protected function registerBaseServiceProviders()
    {
        $this->register(new EventServiceProvider($this));

        $this->register(new RoutingServiceProvider($this));
    }

    /**
     * Get the path to the application configuration files.
     *
     * @return string
     */
    public function configPath()
    {
        return $this->basePath.'/resources/config';
    }

    /**
     * Get the path to the database directory.
     *
     * @return string
     */
    public function databasePath()
    {
        return $this->basePath.'/resources/database';
    }

    /**
     * Get the path to the language files.
     *
     * @return string
     */
    public function langPath()
    {
        return $this->basePath.'/resources/lang';
    }

    /**
     * Get the path to the public / web directory.
     *
     * @return string
     */
    public function publicPath()
    {
        return $this->basePath.'/public';
    }

    /**
     * Get the path to the storage directory.
     *
     * @return string
     */
    public function storagePath()
    {
        return $this->basePath.'/storage';
    }

    /**
     * Get the application's deferred services.
     *
     * @return array
     */
    public function getDeferredServices()
    {
        return $this->deferredServices;
    }

    /**
     * Flush the container of all bindings and resolved instances.
     *
     * @return void
     */
    public function flush()
    {
        parent::flush();

        $this->hasBeenBootstrapped = false;
    }
}
