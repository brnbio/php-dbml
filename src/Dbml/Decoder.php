<?php

declare(strict_types=1);

namespace Dbml\Dbml;

use Dbml\Dbml\Model\Table;
use Exception;

/**
 * Class Decoder
 * @package Dbml\Dbml
 */
class Decoder
{
    /**
     * @param string $content
     * @return Table[]
     * @throws Exception
     */
    public static function run(string $content): array
    {
        return self::decodeTables($content);
    }

    /**
     * @param string $content
     * @return array
     * @throws Exception
     */
    private static function decodeTables(string $content): array
    {
        $result = [];

        $re = '/Table (?|([\w_]+())|([\w]+) as ([\w]+)) \{\s*([^}]*)}/m';
        preg_match_all($re, $content, $tables, PREG_SET_ORDER);

        foreach ($tables as $item) {
            $name = trim($item[1]);
            $alias = !empty($item[2]) ? trim($item[2]) : null;
            $columns = self::decodeColumns(trim($item[3]));
            $result[] = new Model\Table($name, $alias, $columns);
        }

        // -- relationships
        self::decodeRelationships($content, $result);

        return $result;
    }

    /**
     * @param string $content
     * @return Table\Column[]
     */
    private static function decodeColumns(string $content): array
    {
        $result = [];

        $re = '/([\w_]+) ([a-z]+)( \[(.*)])?/m';
        preg_match_all($re, $content, $columns, PREG_SET_ORDER);

        foreach ($columns as $item) {
            $name = trim($item[1]);
            $type = trim($item[2]);
            $attributes = [];
            if (!empty($item[4])) {
                $attributes = self::decodeAttributes(trim($item[4]));
            }

            $result[] = new Table\Column($name, $type, $attributes);
        }

        return $result;
    }

    /**
     * @param string $content
     * @return array
     */
    public static function decodeAttributes(string $content): array
    {
        $result = [];

        $attributes = explode(',', $content);
        foreach ($attributes as $attribute) {
            $attribute = trim($attribute);
            if ($attribute === 'pk' || $attribute === 'primary key') {
                $result['null'] = false;
            }
            if ($attribute === 'not null') {
                $result['null'] = false;
            }
            if ($attribute === 'unique') {
                $result['unique'] = true;
            }
        }

        return $result;
    }

    /**
     * @param string $content
     * @param Table[] $tables
     * @return Table[]
     * @throws Exception
     */
    public static function decodeRelationships(string $content, array $tables): array
    {
        $result = [];

        $re = '/Ref: \"?([^".\s]+)\"?\.\"?([^".\s]+)\"? ([<>-]{1}) \"?([^".\s]+)\"?\.\"?([^".\s]+)\"?/m';
        preg_match_all($re, $content, $relationships, PREG_SET_ORDER);

        foreach ($relationships as $item) {

            $type = self::decodeRelationshipType($item[3]);

            // -- get relationship table
            $table = null;
            foreach ($tables as $table) {
                if ($item[1] === $table->name) {
                    break;
                }
            }
            // -- get column
            $column = null;
            foreach ($table->columns as $column) {
                if ($item[2] === $column->name) {
                    break;
                }
            }
            // -- get foreign table
            $foreignTable = null;
            foreach ($tables as $foreignTable) {
                if ($item[4] === $foreignTable->name) {
                    break;
                }
            }
            // -- get foreign column
            $foreignColumn = null;
            foreach ($foreignTable->columns as $foreignColumn) {
                if ($item[5] === $foreignColumn->name) {
                    break;
                }
            }

            $relationship = new Table\Relationship($type, $column, $foreignTable, $foreignColumn);
            $table->addRelationship($relationship);
        }

        return $tables;
    }

    /**
     * @param string $type
     * @return string
     * @throws Exception
     */
    private static function decodeRelationshipType(string $type): string
    {
        switch ($type) {
            case '<':
                return Table\Relationship::RELATIONSHIP_HAS_MANY;
                break;
            case '>':
                return Table\Relationship::RELATIONSHIP_BELONGS_TO;
                break;
            case '-':
                return Table\Relationship::RELATIONSHIP_HAS_ONE;
                break;
        }

        throw new Exception('Unsupported type.');
    }
}