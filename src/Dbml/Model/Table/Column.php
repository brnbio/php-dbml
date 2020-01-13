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
    protected $name;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var bool
     */
    protected $null = true;

    /**
     * @var bool
     */
    protected $unsigned = false;

    /**
     * @var int|null
     */
    protected $length = null;

    /**
     * @var string|null
     */
    protected $default = null;

    /**
     * @var bool
     */
    protected $unique = false;

    /**
     * @var bool
     */
    protected $autoIncrement = false;

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