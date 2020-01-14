<?php

declare(strict_types=1);

namespace Dbml\Dbml\Model\Table;

/**
 * Class Column
 * @package Dbml\Dbml\Table
 */
class Column
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $type;

    /**
     * @var bool
     */
    public $null = true;

    /**
     * @var bool
     */
    public $unsigned = false;

    /**
     * @var int|null
     */
    public $length = null;

    /**
     * @var string|null
     */
    public $default = null;

    /**
     * @var bool
     */
    public $unique = false;

    /**
     * @var bool
     */
    public $autoIncrement = false;

    /**
     * @param string $name
     * @param string $type
     * @param array $attributes
     */
    public function __construct(string $name, string $type, array $attributes = [])
    {
        $this->name = $name;
        $this->type = $type;
        $this->initAttributes($attributes);
    }

    /**
     * @param array $attributes
     * @return Column
     */
    private function initAttributes(array $attributes = []): Column
    {
        foreach ($attributes as $attribute => $value) {
            $this->{$attribute} = $value;
        }

        return $this;
    }
}