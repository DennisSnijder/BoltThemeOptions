<?php

namespace Bolt\Extension\Snijder\BoltUIOptions\Controller;
use Bolt\Extension\Snijder\BoltUIOptions\Config\Config;


/**
 * Class ThemeOptionsTwigFunctionController.
 *
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class UIOptionsTwigFunctionController
{
    /**
     * @var Config
     */
    private $config;

    /**
     * ThemeOptionsTwigFunctionController constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param $name
     *
     * @return string
     */
    public function renderThemeOption($name)
    {
        $fields = $this->config->getFields();

        return  $fields[$name]->getValue();
    }
}
