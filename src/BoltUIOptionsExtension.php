<?php

namespace Bolt\Extension\Snijder\BoltUIOptions;

use Bolt\Extension\SimpleExtension;
use Bolt\Extension\Snijder\BoltUIOptions\Controller\UIOptionsController;
use Bolt\Extension\Snijder\BoltUIOptions\Provider\UIOptionsProvider;
use Bolt\Menu\MenuEntry;

/**
 * Class BoltThemeOptionsExtension.
 *
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class BoltUIOptionsExtension extends SimpleExtension
{
    /**
     * {@inheritdoc}
     */
    public function getServiceProviders()
    {
        return [
           $this,
           new UIOptionsProvider($this->getConfig()),
       ];
    }

    /**
     * {@inheritdoc}
     */
    protected function registerMenuEntries()
    {
        $menu = new MenuEntry('bolt-ui-options', 'bolt-ui-options');
        $menu->setLabel('UI options')
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
            'templates' => ['namespace' => 'UIOptions'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function registerTwigFunctions()
    {
        return [
           'themeoption' => [[$this->container['ui.options.twig.function'], 'renderThemeOption']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function registerBackendControllers()
    {
        return [
          '/extend/bolt-ui-options' => new UIOptionsController(
              $this->container['twig'],
              $this->container['ui.options.config']
          ),
        ];
    }
}
