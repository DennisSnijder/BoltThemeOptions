<?php

namespace Bolt\Extension\Snijder\BoltUIOptions\Controller;

use Bolt\Extension\Snijder\BoltUIOptions\Config\Config;

/**
 * Class UIOptionsTwigFunctionController.
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
    public function renderUIOption($name)
    {
        $fields = $this->config->getFields();

        if (!array_key_exists($name, $fields) || $fields[$name] == null) {
            return '';
        }

        return  $fields[$name]->getValue();
    }
}
