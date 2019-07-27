<?php


namespace Db;

use \Db\Exceptions\InvalidTypeException;

class Column
{
    const TYPE_STRING     = 1;
    const TYPE_INTEGER    = 2;
    const TYPE_BOOLEAN    = 3;
    const TYPE_DATE       = 4;
    const TYPE_TIME       = 5;
    const TYPE_DATETIME   = 6;
    const TYPE_BIG_TEXT   = 7;
    const TYPE_SMALL_TEXT = 8;

    /** @var string */
    private $name;

    /** @var int */
    private $type;

    /** @var int */
    private $size;

    /**
     * @param $typeNumber int The type number from the constants.
     * @return string
     * @throws InvalidTypeException
     */
    private static function stringFromTypeName($typeNumber) {
        switch ($typeNumber) {
            case self::TYPE_SMALL_TEXT: return "varchar";
            case self::TYPE_INTEGER: return "integer";
            case self::TYPE_BOOLEAN: return "boolean";
            case self::TYPE_STRING: return "text";
            case self::TYPE_DATE: return "date";
            case self::TYPE_DATETIME: return "datetime";
            case self::TYPE_BIG_TEXT: return "longtext";
            case self::TYPE_TIME: return "time";

            default: throw new InvalidTypeException($typeNumber);
        }
    }

    public function __construct($name, $type, $size = -1) {
        $this->name = $name;
        $this->size = $size;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     * @throws InvalidTypeException
     */
    public function getTypeName() {
        return self::stringFromTypeName($this->type);
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return string
     * @throws InvalidTypeException
     */
    public function toMysql() {
        return $this->name . ' ' . self::stringFromTypeName($this->type) . ($this->size > 0 ? "($this->size)" : "");
    }
}