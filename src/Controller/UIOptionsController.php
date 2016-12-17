<?php

namespace Bolt\Extension\Snijder\BoltUIOptions\Controller;

use Bolt\Extension\Snijder\BoltUIOptions\Config\Config;
use Bolt\Filesystem\Filesystem;
use Bolt\Filesystem\Handler\YamlFile;
use Bolt\Filesystem\Manager;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class UIOptionsController.
 *
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class UIOptionsController implements ControllerProviderInterface
{
    /**
     * @var \Twig_Environment
     */
    private $twig;
    /**
     * @var Config
     */
    private $config;
    /**
     * @var Manager
     */
    private $filesystem;

    /**
     * ThemeOptionsController constructor.
     *
     * @param \Twig_Environment $twig
     * @param Config $config
     * @param Manager $filesystem
     */
    public function __construct(\Twig_Environment $twig, Config $config, Manager $filesystem)
    {
        $this->twig = $twig;
        $this->config = $config;
        $this->filesystem = $filesystem;
    }

    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app)
    {
        /** @var ControllerCollection $controllers */
        $controllers = $app['controllers_factory'];

        $controllers->get('/', [$this, 'renderThemeOptionsBackendPage']);
        $controllers->post('/post', [$this, 'saveThemeOptionsFromRequest']);

        return $controllers;
    }

    /**
     * Renders the main Theme Options page.
     *
     * @return Response
     */
    public function renderThemeOptionsBackendPage()
    {
        return new Response(
            $this->twig->render(
                '@UIOptions/options.twig',
                [
                    'tabs' => $this->config->getTabs(),
                ]
            )
        );
    }

    /**
     * @param Request $request
     */
    public function handleThemeOptionsFromRequest(Request $request)
    {

    }
}
