<?php

namespace Bolt\Extension\Snijder\BoltUIOptions\Model;

/**
 * Class Tab.
 *
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class Tab
{
    /**
     * The Tab index / id.
     *
     * @var int
     */
    protected $id;

    /**
     * Tab name.
     *
     * @var string
     */
    protected $name;

    /**
     * Tab slug
     *
     * @var string
     */
    protected $slug;

    /**
     * Tab fields.
     *
     * @var Field[]
     */
    protected $fields = [];

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

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
     * @return Field[]
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param Field[] $fields
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    public function addField(Field $field)
    {
        $this->fields[$field->getSlug()] = $field;
    }
}
