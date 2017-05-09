<?php

namespace Bolt\Extension\Snijder\BoltUIOptions\Controller;

use Bolt\Extension\Snijder\BoltUIOptions\Config\Config;
use Bolt\Extension\Snijder\BoltUIOptions\Model\Tab;
use Bolt\Filesystem\Exception\DumpException;
use Bolt\Filesystem\Handler\YamlFile;
use Bolt\Filesystem\Manager;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
    private $optionFile;

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

        $controllers->before([$this, 'before']);

        return $controllers;
    }

    /**
     * Middleware to run before the request cycle begins.
     *
     * @param Request     $request
     * @param Application $app
     */
    public function before(Request $request, Application $app)
    {
        if (!$app['users']->isAllowed('dashboard')) {
            /** @var UrlGeneratorInterface $generator */
            $generator = $app['url_generator'];
            return new RedirectResponse($generator->generate('dashboard'), Response::HTTP_SEE_OTHER);
        }
        $this->config = $app['ui.options.config'];
        $this->filesystem = $app['filesystem'];
        $this->optionFile = $app['ui.options.config.file'];
    }

    /**
     * Renders the main Theme Options page.
     *
     * @param Application $app
     *
     * @return Response
     */
    public function renderThemeOptionsBackendPage(Application $app)
    {
        $twig = $app['twig'];

        return new Response(
            $twig->render(
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
     * @param Application $app
     * @param Request     $request
     *
     * @return Response
     */
    public function handleThemeOptionsSaveFromRequest(Application $app, Request $request)
    {
        $session = $app['session'];
        $extensionOptions = $request->request->get('extension');
        if ($extensionOptions) {
            if ($this->saveExtensionOptions($extensionOptions)) {
                $session->getFlashBag()->add('ui-options', [
                    'type' => 'success',
                    'message' => 'Extension options successfully updated!',
                ]);
            } else {
                $session->getFlashBag()->add('ui-options', [
                    'type' => 'danger',
                    'message' => 'Something went wrong while saving extension options!',
                ]);
            }
        }

        $themeOptions = $request->request->get('theme');
        if ($themeOptions) {
            if ($this->saveThemeOptions($themeOptions)) {
                $session->getFlashBag()->add('ui-options', [
                    'type' => 'success',
                    'message' => 'Theme options successfully updated!',
                ]);
            } else {
                $session->getFlashBag()->add('ui-options', [
                    'type' => 'danger',
                    'message' => 'Something went wrong while saving theme options!',
                ]);
            }
        }
        $targetUrl = $app['url_generator']->generate('ui.options');

        return new RedirectResponse($targetUrl);
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
        $rawOptions = $this->getParsedYamlFile($this->optionFile);
        $this->updateTabs($this->config->getTabs(), $requestOptions);

        if (isset($rawOptions['ui-options'])) {
            $rawOptions['ui-options'] = $this->config->getArrayOptions();

            return $this->saveYamlFile($this->optionFile, $rawOptions);
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
        $themeFilePath = 'theme://theme.yml';
        if (!$this->filesystem->has($themeFilePath)) {
            return true;
        }

        $themeFile = $this->filesystem->get($themeFilePath);
        $rawOptions = $this->getParsedYamlFile($themeFile);
        $this->updateTabs($this->config->getThemeTabs(), $requestOptions);

        if (isset($rawOptions['ui-options'])) {
            $rawOptions['ui-options'] = $this->config->getArrayOptions(true);

            return $this->saveYamlFile($themeFile, $rawOptions);
        }

        return false;
    }

    /**
     * Gets a parsed Yaml file by its path.
     *
     * @param YamlFile $yamlFile
     *
     * @return mixed
     */
    protected function getParsedYamlFile(YamlFile $yamlFile)
    {
        if (!$yamlFile->exists()) {
            return [];
        }

        return $yamlFile->parse();
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
     * @param YamlFile $yamlFile
     * @param array    $data
     *
     * @return bool
     */
    protected function saveYamlFile(YamlFile $yamlFile, array $data)
    {
        try {
            $yamlFile->dump($data, [
                'objectSupport' => true,
                'inline' => 7,
            ]);

            return true;
        } catch (DumpException $e) {
            return false;
        }
    }
}
