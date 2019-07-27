<?php


namespace Db;


use Db\Exceptions\InvalidActionException;
use Db\Exceptions\InvalidTypeException;

class Key
{
    const TYPE_PRIMARY = 1;
    const TYPE_FOREIGN = 2;

    const ACTION_CASCADE = 1;
    const ACTION_SETNULL = 2;
    const ACTION_NONE    = 0;

    /** @var int */
    private $type;

    /** @var string */
    private $name;

    /** @var Column */
    private $col;

    /** @var int | bool */
    private $action;

    /** @var Column | bool */
    private $refcol;

    /** @var string | bool */
    private $reftbl;

    /** @var string */
    private $tableName;

    /**
     * Key constructor.
     * @param Table $tbl
     * @param Column | string $col
     * @param \string $name
     * @param int $type
     */
    public function __construct(Table $tbl, $col, string $name, int $type) {
        $this->col = $col instanceof \string ? $tbl->getColumn($col) : $col;
        $this->name = $name;
        $this->type = $type;
        $this->tableName = $tbl->getName();

        $this->action = ($type == self::TYPE_FOREIGN);
        $this->refcol = ($type == self::TYPE_FOREIGN);
        $this->reftbl = ($type == self::TYPE_FOREIGN);
    }

    /**
     * If appropriate, sets the action for the key. Otherwise, throws an InvalidTypeException.
     * @param int $action
     * @throws InvalidActionException
     * @throws InvalidTypeException
     */
    public function setAction(int $action) {
        if ($this->action == true) {
            if ($action != self::ACTION_CASCADE && $action != self::ACTION_SETNULL && $action != self::ACTION_NONE)
                throw new InvalidActionException($action);
            $this->action = $action;
        } elseif ($this->action == false) {
            throw new InvalidTypeException("primary");
        }
    }

    /**
     * If appropriate, sets the reference column of this foreign key.
     * @param Column $refcol
     * @throws InvalidTypeException
     */
    public function setReferenceColumn(Column $refcol) {
        if ($this->refcol == true) {
            $this->refcol = $refcol;
        } elseif ($this->refcol == false) {
            throw new InvalidTypeException("primary");
        }
    }

    public function setReferenceTable(string $tbl) {
        if ($this->reftbl == true) {
            $this->reftbl = $tbl;
        } elseif ($this->reftbl == false) {
            throw new InvalidTypeException("primary");
        }
    }

    public function getReferenceTable()
    {
        if ($this->reftbl == true)
            return null;
        elseif ($this->reftbl == false)
            throw new InvalidTypeException("primary");
        else
            return $this->reftbl;
    }

    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * Gives the ON DELETE action if appropriate.
     * @return int|null
     * @throws InvalidTypeException
     */
    public function getAction() {
        if ($this->action == true)
            return null;
        elseif ($this->action == false)
            throw new InvalidTypeException("primary");
        else
            return $this->action;
    }

    /**
     * @return string
     * @throws InvalidTypeException
     */
    public function getActionName() {
        return self::getActionNames()[$this->getAction()];
    }

    /**
     * Gives the referenced column if appropriate.
     * @return Column|null
     * @throws InvalidTypeException
     */
    public function getReferenceColumn() {
        if ($this->refcol == true)
            return null;
        elseif ($this->refcol == false)
            throw new InvalidTypeException("primary");
        else
            return $this->refcol;
    }

    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getTypeName()
    {
        return self::getTypeNames()[$this->getType()];
    }

    /**
     * Get all possible type names.
     * @return array
     */
    public static function getTypeNames() {
        return [self::TYPE_PRIMARY => "PRIMARY", self::TYPE_FOREIGN => "FOREIGN"];
    }

    /**
     * Get all possible "ON DELETE" actions' names.
     * @return array
     */

    public static function getActionNames() {
        return [self::ACTION_NONE => "NONE", self::ACTION_SETNULL => "SET NULL", self::ACTION_CASCADE => "CASCADE"];
    }

    public function getColumn()
    {
        return $this->col;
    }
}