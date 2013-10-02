<?php namespace Skovachev\Notifier;

class ServiceProvider extends \Illuminate\Support\ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('skovachev/notifier');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['notifier'] = $this->app->share(function($app){
            return new NotificationService(array(
                $app->make('Skovachev\Notifier\Notifiers\EmailNotifier'),
                $app->make('Skovachev\Notifier\Notifiers\SMSNotifier'),
            ));
        });
    }

    public function provides()
    {
        return array('notifier');
    }

}