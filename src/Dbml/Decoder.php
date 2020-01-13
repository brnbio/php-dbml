<?php

declare(strict_types=1);

namespace Dbml\Dbml;

use Dbml\Dbml\Model\Table;

/**
 * Class Decoder
 * @package Dbml\Dbml
 */
class Decoder
{
    /**
     * @param string $content
     * @return Table[]
     */
    public static function run(string $content): array
    {
        return self::decodeTables($content);
    }

    /**
     * @param string $tables
     * @return array
     */
    private static function decodeTables(string $tables): array
    {
        $result = [];

        $re = '/Table (?|([\w_]+())|([\w]+) as ([\w]+)) \{\s*([^}]*)}/m';
        preg_match_all($re, $tables, $tables, PREG_SET_ORDER);

        foreach ($tables as $item) {
            $name = trim($item[1]);
            $alias = !empty($item[2]) ? trim($item[2]) : null;
            $columns = self::decodeColumns(trim($item[3]));
            $result[] = new Model\Table($name, $alias, $columns);
        }

        return $result;
    }

    /**
     * @param string $columns
     * @return Table\Column[]
     */
    private static function decodeColumns(string $columns): array
    {
        $result = [];

        $re = '/([\w_]+) ([a-z]+)( \[(.*)])?/m';
        preg_match_all($re, $columns, $columns, PREG_SET_ORDER);

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
     * @param string $attributes
     * @return array
     */
    public static function decodeAttributes(string $attributes): array
    {
        $result = [];

        $attributes = explode(',', $attributes);
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

}