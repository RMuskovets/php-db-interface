<?php


namespace Db;


use Db\Sql\SqlWriter;

class Database
{
    /** @var array<Table> */
    protected $tables;

    /** @var string */
    protected $name;

    /**
     * Database constructor.
     * @param string $name
     */
    public function __construct(string $name) {
        $this->name = $name;
        $this->tables = [];
    }

    /**
     * @param Table $tbl
     */
    public function addTable(Table $tbl) {
        $tables[] = $tbl;
    }

    /**
     * @param string $name
     */
    public function removeTable(string $name) {
        $nt = [];
        for ($i = 0; $i < count($this->tables); ++$i) {
            if ($this->tables[$i]->getName() != $name) {
                $nt[] = $this->tables[$i];
            }
        }

        $this->tables = $nt;
    }

    /**
     * @param string $name
     * @return bool|Table
     */
    public function getTableByName(string $name) {
        foreach ($this->tables as $tbl) {
            if ($tbl->getName() == $name) {
                return $tbl;
            }
        }
        return false;
    }

    /**
     * @return array
     */
    public function getTables() {
        return $this->tables;
    }

    public function dump(SqlWriter $writer) {
        return $writer->dumpDatabase($this);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}