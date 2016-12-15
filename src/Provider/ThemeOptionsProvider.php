<?php

namespace Bolt\Extension\Snijder\BoltThemeOptions\Provider;

use Bolt\Extension\Snijder\BoltThemeOptions\Config\Config;
use Bolt\Extension\Snijder\BoltThemeOptions\Controller\ThemeOptionsTwigFunctionController;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Class ThemeOptionsProvider.
 *
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class ThemeOptionsProvider implements ServiceProviderInterface
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
        $app['theme.options.config'] = $app->share(
            function () {
                return new Config($this->config);
            }
        );

        $app['theme.options.twig.function'] = $app->share(
            function($app) {
                return new ThemeOptionsTwigFunctionController($app['theme.options.config']);
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
