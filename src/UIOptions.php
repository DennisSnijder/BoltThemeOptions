<?php
namespace Bolt\Extension\Snijder\BoltUIOptions;
use Bolt\Extension\Snijder\BoltUIOptions\Config\Config;

/**
 * Class UIOptions
 *
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class UIOptions
{
    /**
     * @var Config
     */
    private $config;

    /**
     * UIOptions constructor.
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }


    /**
     * gets an option value by its slug
     *
     * @param $slug
     *
     * @return string
     */
    public function getOptionValue($slug)
    {
        $fields = $this->config->getFields();

        if (!array_key_exists($slug, $fields) || $fields[$slug] == null) {
            return '';
        }

        return  $fields[$slug]->getValue();
    }

}