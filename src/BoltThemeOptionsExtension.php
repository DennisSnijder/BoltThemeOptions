<?php

namespace Bolt\Extension\Snijder\BoltThemeOptions;

use Bolt\Extension\SimpleExtension;
use Bolt\Extension\Snijder\BoltThemeOptions\Controller\ThemeOptionsController;
use Bolt\Extension\Snijder\BoltThemeOptions\Provider\ThemeOptionsProvider;
use Bolt\Menu\MenuEntry;

/**
 * Class BoltThemeOptionsExtension.
 *
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class BoltThemeOptionsExtension extends SimpleExtension
{
    /**
     * {@inheritdoc}
     */
    public function getServiceProviders()
    {
        return [
           $this,
           new ThemeOptionsProvider($this->getConfig()),
       ];
    }

    /**
     * {@inheritdoc}
     */
    protected function registerMenuEntries()
    {
        $menu = new MenuEntry('bolt-theme-options', 'bolt-theme-options');
        $menu->setLabel('Theme options')
            ->setIcon('fa:columns')
            ->setPermission('settings')
        ;

        return [
            $menu,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function registerTwigPaths()
    {
        return [
            'templates' => ['namespace' => 'ThemeOptions'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function registerTwigFunctions()
    {
        return [
           'themeoption' => [ [$this->container['theme.options.twig.function'], 'renderThemeOption'] ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function registerBackendControllers()
    {
        return [
          '/extend/bolt-theme-options' => new ThemeOptionsController(
              $this->container['twig'],
              $this->container['theme.options.config']
          ),
        ];
    }
}
