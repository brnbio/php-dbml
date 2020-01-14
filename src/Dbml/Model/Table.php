<?php

declare(strict_types=1);

namespace Dbml\Dbml\Model;

use Dbml\Dbml\Model\Table\Column;

/**
 * Class Table
 * @package Dbml\Dbml
 */
class Table
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string|null
     */
    public $alias = null;

    /**
     * @var Column[]
     */
    public $columns = [];

    /**
     * Table constructor.
     * @param string $name
     * @param string|null $alias
     * @param array $columns
     */
    public function __construct(string $name, string $alias = null, array $columns = [])
    {
        $this->name = $name;
        $this->alias = $alias;
        $this->columns = $columns;
    }

    /**
     * @param Column $column
     * @return Table
     */
    public function addColumn(Column $column): Table
    {
        $this->columns[] = $column;

        return $this;
    }
}