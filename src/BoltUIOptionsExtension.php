<?php

namespace Bolt\Extension\Snijder\BoltUIOptions;

use Bolt\Asset\File\JavaScript;
use Bolt\Asset\File\Stylesheet;
use Bolt\Controller\Zone;
use Bolt\Extension\SimpleExtension;
use Bolt\Extension\Snijder\BoltUIOptions\Controller\UIOptionsController;
use Bolt\Extension\Snijder\BoltUIOptions\Provider\UIOptionsProvider;
use Bolt\Menu\MenuEntry;
use Bolt\Version;

/**
 * Class BoltUIOptionsExtension.
 *
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class BoltUIOptionsExtension extends SimpleExtension
{
    /**
     * @var string
     */
    private $backendURL = 'bolt-ui-options';

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
        $menu = new MenuEntry('bolt-ui-options', $this->backendURL);
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
        $extendBaseUrl = Version::compare('3.2.999', '<')
            ? '/extensions/'
            : '/extend/'
        ;

        return [
            $extendBaseUrl . $this->backendURL => new UIOptionsController(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function registerAssets()
    {

        $spectrumStyle = new Stylesheet();
        $spectrumStyle->setFileName('/lib/spectrum/spectrum.css')
            ->setZone(Zone::BACKEND)
        ;


        $spectrumJS = new JavaScript();
        $spectrumJS->setFileName('/lib/spectrum/spectrum.js')
            ->setZone(Zone::BACKEND)
        ;

        $UIOptionStyle = new Stylesheet();
        $UIOptionStyle->setFileName('ui-options.css')
            ->setZone(Zone::BACKEND)
            ->setLate(true)
        ;


        $UIOptionJS = new JavaScript();
        $UIOptionJS->setFileName('ui-options.js')
            ->setZone(Zone::BACKEND)
            ->setLate(true)
        ;

        return [
            $spectrumStyle,
            $spectrumJS,
            $UIOptionStyle,
            $UIOptionJS
        ];
    }
}
