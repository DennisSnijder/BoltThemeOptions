<?php

namespace Bolt\Extension\Snijder\BoltUIOptions;

use Bolt\Asset\File\Stylesheet;
use Bolt\Controller\Zone;
use Bolt\Extension\SimpleExtension;
use Bolt\Extension\Snijder\BoltUIOptions\Controller\UIOptionsController;
use Bolt\Extension\Snijder\BoltUIOptions\Provider\UIOptionsProvider;
use Bolt\Menu\MenuEntry;

/**
 * Class BoltUIOptionsExtension.
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
           'uioption' => [[$this->container['ui.options.twig.function'], 'renderUIOption']],
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
              $this->container['ui.options.config'],
              $this->container['filesystem'],
              $this->container['url_generator'],
              sprintf('config://extensions/%s.%s.yml', strtolower($this->getName()), strtolower($this->getVendor()))
          ),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function registerAssets()
    {
        $asset = new Stylesheet();

        $asset->setFileName('ui-options.css')
            ->setZone(Zone::BACKEND)
            ->setLate(true)
        ;

        return [
            $asset,
        ];
    }

}
