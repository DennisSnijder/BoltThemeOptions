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
     * @var Tab[]
     */
    protected $themeTabs = [];

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
        $this->addExtensionTabs($config['ui-options']);
    }

    /**
     * Hydrates data into models and store the array in $this object.
     * @param $rawTabs
     */
    protected function addExtensionTabs($rawTabs)
    {
        foreach ($rawTabs as $key => $rawTab) {
            $tab = $this->convertTabAndFields($rawTab, $key);
            $this->tabs[$tab->getSlug()] = $tab;
        }
    }


    public function addThemeTabs($rawTabs)
    {

        $keyOffset = count($this->tabs);

        foreach ($rawTabs as $key => $rawTab) {
            $tab = $this->convertTabAndFields($rawTab, $key + $keyOffset);
            $this->themeTabs[$tab->getSlug()] = $tab;
        }
    }


    protected function convertTabAndFields($rawTab, $key)
    {
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

            if(isset($rawField['options'])) {
                $field->setOptions($rawField['options']);
            }

            $this->fields[$field->getSlug()] = $field;
            $tab->addField($field);
        }

        return $tab;
    }


    /**
     * @param bool $themeTabs
     * @return array
     */
    public function getArrayOptions($themeTabs = false)
    {
        $resultArray = [];

        if($themeTabs) {
            $tabs = $this->getThemeTabs();
        }else {
            $tabs = $this->tabs;
        }

        foreach ($tabs as $tab) {
            $tabFields = [];
            foreach ($tab->getFields() as $field) {

                $newField = [
                    'name' => $field->getName(),
                    'slug' => $field->getSlug(),
                    'value' => $field->getValue(),
                    'type' => $field->getType()
                ];


                if($field->getOptions() != []) {
                    $newField['options'] = $field->getOptions();
                }

                $tabFields[] = $newField;
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
     * @return Tab[]
     */
    public function getThemeTabs()
    {
        return $this->themeTabs;
    }

    /**
     * @return Field[]
     */
    public function getFields()
    {
        return $this->fields;
    }

}
