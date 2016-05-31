<?php

namespace Framework\Domain\Configuration;

class Config
{
    /**
     * @var ConfigItem[]
     */
    private $items = [];

    /**
     * @param ConfigItem $configItem
     */
    public function addItem(ConfigItem $configItem)
    {
        $this->items[$configItem->getName()] = $configItem;
    }

    /**
     * @param string $name
     * @param bool|false $returnValue
     * @return ConfigItem|string|null - null if the ConfigItem entry does not exist
     */
    public function get($name, $returnValue = true)
    {
        if (isset($this->items[$name])) {
            return $returnValue ? $this->items[$name]->getValue() : $this->items[$name];
        }
    }
}
