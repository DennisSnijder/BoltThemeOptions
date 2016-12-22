<?php

namespace Bolt\Extension\Snijder\BoltUIOptions\Provider;

use Bolt\Extension\Snijder\BoltUIOptions\Config\Config;
use Bolt\Extension\Snijder\BoltUIOptions\Controller\UIOptionsTwigFunctionController;
use Bolt\Extension\Snijder\BoltUIOptions\UIOptions;
use Bolt\Filesystem\Exception\IOException;
use Bolt\Filesystem\Handler\YamlFile;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Class UIOptionsProvider.
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


        $app['ui.options'] = $app->share(
            function($app) {
                return new UIOptions($app['ui.options.config']);
            }
        );

        $app['ui.options.twig.function'] = $app->share(
            function ($app) {
                return new UIOptionsTwigFunctionController($app['ui.options.config']);
            }
        );

        $app->extend('ui.options.config', function (Config $config) use ($app) {
            try {
                /** @var YamlFile $file */
                $file = $app['filesystem']->get('theme://theme.yml');
            } catch (IOException $e) {
                return $config;
            }

            $options = $file->parse();

            if (!empty($options['ui-options'])) {
                $config->addThemeTabs($options['ui-options']);
            }

            return $config;
        });
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
