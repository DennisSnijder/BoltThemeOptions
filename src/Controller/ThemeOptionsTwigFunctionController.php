<?php
namespace Bolt\Extension\Snijder\BoltThemeOptions\Controller;
use Bolt\Extension\Snijder\BoltThemeOptions\Config\Config;


/**
 * Class ThemeOptionsTwigFunctionController
 *
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class ThemeOptionsTwigFunctionController
{
    /**
     * @var Config
     */
    private $config;

    /**
     * ThemeOptionsTwigFunctionController constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @param $name
     * @return string
     */
    public function renderThemeOption($name)
    {
        $fields = $this->config->getFields();
        return  $fields[$name]->getValue();
    }
    
}