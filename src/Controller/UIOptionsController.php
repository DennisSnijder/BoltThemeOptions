<?php

namespace Bolt\Extension\Snijder\BoltUIOptions\Controller;

use Bolt\Extension\Snijder\BoltUIOptions\Config\Config;
use Bolt\Extension\Snijder\BoltUIOptions\Model\Tab;
use Bolt\Filesystem\Exception\DumpException;
use Bolt\Filesystem\Handler\YamlFile;
use Bolt\Filesystem\Manager;
use Bolt\Routing\UrlGeneratorFragmentWrapper;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

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
     * @var
     */
    private $themeFilePath;
    /**
     * @var Session
     */
    private $session;

    /**
     * ThemeOptionsController constructor.
     *
     * @param \Twig_Environment           $twig
     * @param Config                      $config
     * @param Manager                     $filesystem
     * @param UrlGeneratorFragmentWrapper $urlGenerator
     * @param Session                     $session
     * @param $optionFilePath
     * @param $themeFilePath
     */
    public function __construct(
        \Twig_Environment $twig,
        Config $config,
        Manager $filesystem,
        UrlGeneratorFragmentWrapper $urlGenerator,
        Session $session,
        $optionFilePath,
        $themeFilePath
    ) {
        $this->twig = $twig;
        $this->config = $config;
        $this->filesystem = $filesystem;
        $this->urlGenerator = $urlGenerator;
        $this->optionFilePath = $optionFilePath;
        $this->themeFilePath = $themeFilePath;
        $this->session = $session;
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
                    'themeTabs' => $this->config->getThemeTabs(),
                ]
            )
        );
    }

    /**
     * Handles the request for ui options saving, on both extension and theme options.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function handleThemeOptionsSaveFromRequest(Request $request)
    {
        if ($request->get('extension')) {
            if ($this->saveExtensionOptions($request->get('extension'))) {
                $this->session->getFlashBag()->add('ui-options', [
                    'type' => 'success',
                    'message' => 'Extension options successfully updated!',
                ]);
            }
        }

        if ($request->get('theme')) {
            if ($this->saveThemeOptions($request->get('theme'))) {
                $this->session->getFlashBag()->add('ui-options', [
                    'type' => 'success',
                    'message' => 'Theme options successfully updated!',
                ]);
            }
        }

        return new RedirectResponse($this->urlGenerator->generate('ui.options'));
    }

    /**
     * Saves options into the extension its config file.
     *
     * @param $requestOptions
     *
     * @return bool
     */
    protected function saveExtensionOptions($requestOptions)
    {
        $rawOptions = $this->getParsedYamlFile($this->themeFilePath);
        $this->updateTabs($this->config->getTabs(), $requestOptions);

        if (isset($rawOptions['ui-options'])) {
            $rawOptions['ui-options'] = $this->config->getArrayOptions();

            return $this->saveYamlFile($rawOptions, $this->optionFilePath);
        }

        return false;
    }

    /**
     * Saves options into the theme.yml file.
     *
     * @param $requestOptions
     *
     * @return bool
     */
    protected function saveThemeOptions($requestOptions)
    {
        $rawOptions = $this->getParsedYamlFile($this->themeFilePath);
        $this->updateTabs($this->config->getThemeTabs(), $requestOptions);

        if (isset($rawOptions['ui-options'])) {
            $rawOptions['ui-options'] = $this->config->getArrayOptions(true);

            return $this->saveYamlFile($rawOptions, $this->themeFilePath);
        }

        return false;
    }

    /**
     * Gets a parsed Yaml file by its path.
     *
     * @param $path
     *
     * @return mixed
     */
    protected function getParsedYamlFile($path)
    {
        $file = new YamlFile();
        $this->filesystem->getFile($path, $file);

        return $file->parse();
    }

    /**
     * Updates the tabs in the Config class.
     *
     * @param Tab[] $tabs
     * @param array $data
     */
    protected function updateTabs($tabs, $data)
    {
        foreach ($data as $tabKey => $rawFields) {
            if (!isset($tabs[$tabKey])) {
                continue;
            }

            /** @var Tab $tab */
            $tab = $tabs[$tabKey];

            $fields = $tab->getFields();
            foreach ($rawFields as $fieldKey => $value) {
                if (!isset($fields[$fieldKey])) {
                    continue;
                }

                $field = $fields[$fieldKey];
                $field->setValue($value);
            }
        }
    }

    /**
     * Saves the Yaml file.
     *
     * @param array $data
     * @param $path
     *
     * @return bool
     */
    protected function saveYamlFile(array $data, $path)
    {
        $newFile = new YamlFile();
        $newFile->setFilesystem($this->filesystem);
        $newFile->setPath($path);

        try {
            $newFile->dump($data, [
                'objectSupport' => true,
                'inline' => 7,
            ]);

            return true;
        } catch (DumpException $e) {
            return false;
        }
    }
}
