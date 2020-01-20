<?php

declare(strict_types=1);

namespace Dbml;

use Dbml\Dbml\Decoder as DbmlDecoder;
use Dbml\Dbml\Model\Table;

/**
 * Class Dbml
 * @package Dbml
 */
class Dbml
{
    /**
     * @var string
     */
    protected $filename;

    /**
     * @var Table[]
     */
    public $tables = [];

    /**
     * Dbml constructor.
     * @param string $filename
     */
    public function __construct(string $filename)
    {
        $this->filename = $filename;
        $this->decode();
    }

    /**
     * @return Dbml
     */
    private function decode(): Dbml
    {
        $dbml = file_get_contents($this->filename);
        $this->tables = DbmlDecoder::run($dbml);

        return $this;
    }

    /**
     * @return string
     */
    public function encode(): string
    {
        // TODO
    }

    /**
     * @return string
     */
    public function toSql(): string
    {
        // TODO
    }

    /**
     * @param string|null $key
     * @return Table|null
     */
    public function tables(string $key = null): ?Table
    {
        foreach ($this->tables as $table) {
            if ($table->name === $key) {
                return $table;
            }
        }

        return null;
    }
}