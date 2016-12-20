<?php

namespace Bolt\Extension\Snijder\BoltUIOptions\Model;

/**
 * Class Field.
 *
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class Field
{
    /**
     * Field name.
     *
     * @var string
     */
    protected $name;

    /**
     * Field slug.
     *
     * @var string
     */
    protected $slug;

    /**
     * Field type.
     *
     * @var string
     */
    protected $type;

    /**
     * Field value.
     *
     * @var string
     */
    protected $value;

    /**
     * Field options, mainly used in select type fields.
     *
     * @var string[]
     */
    protected $options;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return \string[]
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param \string[] $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }
}
