<?php

namespace Bolt\Extension\Snijder\BoltUIOptions\Controller;

use Bolt\Extension\Snijder\BoltUIOptions\Config\Config;
use Bolt\Filesystem\Filesystem;
use Bolt\Filesystem\Handler\YamlFile;
use Bolt\Filesystem\Manager;
use Bolt\Routing\UrlGeneratorFragmentWrapper;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGenerator;

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
     * @var
     */
    private $optionFilePath;

    /**
     * @var UrlGeneratorFragmentWrapper
     */
    private $urlGenerator;

    /**
     * ThemeOptionsController constructor.
     *
     * @param \Twig_Environment $twig
     * @param Config $config
     * @param Manager $filesystem
     * @param UrlGeneratorFragmentWrapper $urlGenerator
     * @param $optionFilePath
     */
    public function __construct(
        \Twig_Environment $twig,
        Config $config,
        Manager $filesystem,
        UrlGeneratorFragmentWrapper $urlGenerator,
        $optionFilePath
    )
    {
        $this->twig = $twig;
        $this->config = $config;
        $this->filesystem = $filesystem;
        $this->urlGenerator = $urlGenerator;
        $this->optionFilePath = $optionFilePath;
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

        $controllers->get('/', [$this, 'renderThemeOptionsBackendPage'])->bind('ui.options');
        $controllers->post('/post', [$this, 'handleThemeOptionsSaveFromRequest'])->bind('ui.options.save');

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
     * @return Response
     */
    public function handleThemeOptionsSaveFromRequest(Request $request)
    {
        $file = new YamlFile();
        $this->filesystem->getFile($this->optionFilePath, $file);
        $rawOptions = $file->parse();


        //todo check keys for existence
        $tabs = $this->config->getTabs();
        foreach ($request->request->all() as $tabKey => $rawFields) {
            $tab = $tabs[$tabKey];

            $fields = $tab->getFields();
            foreach ($rawFields as $fieldKey => $value) {
                $field = $fields[$fieldKey];
                $field->setValue($value);
            }
        }

        //todo check for options key existence
        $rawOptions['options'] = $this->config->getArrayOptions();

        $newFile = new YamlFile();
        $newFile->setFilesystem($this->filesystem);
        $newFile->setPath($this->optionFilePath);

        $newFile->dump($rawOptions, [
            'objectSupport' => true,
            'inline' => 7
        ]);

        return new RedirectResponse($this->urlGenerator->generate('ui.options'));
    }
}
