<?php

namespace Bolt\Extension\Snijder\BoltUIOptions\Provider;

use Bolt\Extension\Snijder\BoltUIOptions\Config\Config;
use Bolt\Extension\Snijder\BoltUIOptions\Controller\UIOptionsTwigFunctionController;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Class ThemeOptionsProvider.
 *
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class UIOptionsProvider implements ServiceProviderInterface
{
    /**
     * @var array
     */
    private $config;

    /**
     * ThemeOptionsProvider constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Application $app
     */
    public function register(Application $app)
    {
        $app['ui.options.config'] = $app->share(
            function () {
                return new Config($this->config);
            }
        );

        $app['ui.options.twig.function'] = $app->share(
            function ($app) {
                return new UIOptionsTwigFunctionController($app['ui.options.config']);
            }
        );
    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     *
     * @param Application $app
     */
    public function boot(Application $app)
    {
    }
}
