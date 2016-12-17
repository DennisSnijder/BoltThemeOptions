<?php

namespace Bolt\Extension\Snijder\BoltUIOptions\Config;

use Bolt\Extension\Snijder\BoltUIOptions\Model\Field;
use Bolt\Extension\Snijder\BoltUIOptions\Model\Tab;

/**
 * Class Config.
 *
 * @author Dennis Snijder <Dennis@Snijder.io>
 */
class Config
{
    /**
     * @var array
     */
    protected $rawTabs;

    /**
     * @var Tab[]
     */
    protected $tabs = [];

    /**
     * Field map.
     *
     * @var Field[]
     */
    protected $fields = [];

    /**
     * Config constructor.
     *
     * @param $config array
     */
    public function __construct(array $config)
    {
        $this->rawTabs = $config['options'];
        $this->initializeFields();
    }

    /**
     * Hydrates data into models and store the array in $this object.
     */
    protected function initializeFields()
    {
        $rawTabs = $this->rawTabs;

        foreach ($rawTabs as $key => $rawTab) {
            $tab = new Tab();

            $tab->setId($key);
            $tab->setName($rawTab['name']);
            $tab->setSlug($rawTab['slug']);

            foreach ($rawTab['fields'] as $rawField) {
                $field = new Field();
                $field->setName($rawField['name']);
                $field->setSlug($rawField['slug']);
                $field->setValue($rawField['value']);
                $field->setType($rawField['type']);

                $this->fields[$field->getSlug()] = $field;
                $tab->addField($field);
            }

            $this->tabs[$tab->getSlug()] = $tab;
        }
    }


    /**
     * @return array
     */
    public function getArrayOptions()
    {
        $resultArray = [];

        foreach ($this->tabs as $tab) {
            $tabFields = [];
            foreach ($tab->getFields() as $field) {
                $tabFields[] = [
                    'name' => $field->getName(),
                    'slug' => $field->getSlug(),
                    'value' => $field->getValue(),
                    'type' => $field->getType()
                ];
            }

            $resultArray[] = [
                'name' => $tab->getName(),
                'slug' => $tab->getSlug(),
                'fields' => $tabFields,
            ];
        }
        
        return $resultArray;
    }

    /**
     * @return Tab[]
     */
    public function getTabs()
    {
        return $this->tabs;
    }

    /**
     * @return Field[]
     */
    public function getFields()
    {
        return $this->fields;
    }
}
