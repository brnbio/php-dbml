<?php

declare(strict_types=1);

namespace Dbml\Dbml\Model\Table;

use Dbml\Dbml\Model\Table;

class Relationship
{
    public const RELATIONSHIP_BELONGS_TO = 'belongsTo';
    public const RELATIONSHIP_HAS_MANY = 'hasMany';
    public const RELATIONSHIP_HAS_ONE = 'hasOne';

    /**
     * @var string
     */
    public $type;

    /**
     * Column, that has a relationship
     *
     * @var Column
     */
    public $column;

    /**
     * @var Table
     */
    public $foreignTable;

    /**
     * @var Column
     */
    public $foreignColumn;

    /**
     * @param string $type
     * @param Column $column
     * @param Table $foreignTable
     * @param Column $foreignColumn
     */
    public function __construct(string $type, Column $column, Table $foreignTable, Column $foreignColumn)
    {
        $this->type = $type;
        $this->column = $column;
        $this->foreignTable = $foreignTable;
        $this->foreignColumn = $foreignColumn;
    }
}