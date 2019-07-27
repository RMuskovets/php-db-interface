<?php


namespace Db;


class Table
{
    /** @var string */
    protected $name;

    /** @var array */
    protected $columns;

    /** @var Column | bool */
    protected $identity;

    /** @var array */
    protected $keys;

    /** @var array */
    protected $records;

    /**
     * Table constructor.
     * @param string $name the table's name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->columns = [];
        $this->keys = [];
        $this->identity = false;
        $this->records = [];
    }

    /**
     * Add a column to this table.
     * @param Column $column
     */
    public function addColumn(Column $column)
    {
        $this->columns[] = $column;
    }

    public function getColumn(string $columnName)
    {
        foreach ($this->columns as $column)
        {
            if ($column->getName() == $columnName)
                return $column;
        }
        return null;
    }

    /**
     * Add a record.
     * @param array $record
     */
    public function addRecord(array $record)
    {
        $this->records[] = $record;
    }

    /**
     * @param array $criterias The criterias (in form field => value)
     * @return array
     */
    public function getRecords(array $criterias)
    {
        $records = [];
        foreach ($this->records as $record)
        {
            $suits = true;
            foreach ($criterias as $key => $value)
            {
                if (!isset($record[$key]) || $record[$key] != $value)
                    $suits = false;
            }
            if ($suits) $records[] = $record;
        }
        return $records;
    }

    /**
     * Get all records from this table
     * @return array
     */
    public function getAllRecords() {
        return $this->getRecords([]);
    }

    /**
     * Set identity (PRIMARY KEY) column.
     * @param string $name
     */
    public function setIdentityColumn(string $name) {
        foreach ($this->columns as $column) {
            if ($column->getName() == $name){
                $this->identity = $column;
                $this->columns[] = new Key($this, $column, "PK__" . $name, Key::TYPE_PRIMARY);
            }
        }
    }

    /**
     * Get all keys of this table.
     * @return array
     */
    public function getKeys() {
        return $this->keys;
    }

    /**
     * Get all columns of this table.
     * @return array
     */
    public function getColumns() {
        return $this->columns;
    }

    /**
     * Remove a column from this table by given name.
     * @param string $name
     */
    public function removeColumn(string $name) {
        if ($name == $this->identity->getName()) {
            $this->identity = false;
        }

        $index = 0;
        $nc = [];
        for (; $index < count($this->columns); ++$index) {
            if ($this->columns[$index]->getName() != $name) {
                $nc[] = $this->columns[$index];
            }
        }

        $this->columns = $nc;
    }

    /**
     * Checks the table has an identity column.
     * @return bool
     */
    public function hasIdentityColumn() {
        return !!$this->identity;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}