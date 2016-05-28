<?php

namespace Framework\Domain\Configuration;


class ConfigItem
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $value;

    /**
     * ConfigItem constructor.
     *
     * @param string $name
     * @param string $value
     */
    public function __construct($name, $value)
    {
        $this->assertProperties($name, $value);

        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return (string) $this->name;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return (string) $this->value;
    }

    /**
     * Validate the class property values
     *
     * @param string $name
     * @param string $value
     */
    private function assertProperties($name, $value)
    {
        \Assert\that($name)->notEmpty()->string();
        \Assert\that($value)->notEmpty()->string();
    }
}
