<?php /** @noinspection SqlNoDataSourceInspection */


namespace Db\Sql\Impl;


use Db\Column;
use Db\Database;
use Db\Exceptions\InvalidTypeException;
use Db\Key;
use Db\Sql\SqlWriter;
use Db\Table;

class MySqlWriter extends SqlWriter
{

    /**
     * @param Key $key
     * @return string
     * @throws InvalidTypeException
     */
    public function dumpKey(Key $key)
    {
        $str = "ALTER TABLE `" . $key->getTableName() . "` ADD " . $key->getTypeName() . " KEY ";
        if ($key->getType() == Key::TYPE_FOREIGN)
        {
            $str .= "(`" . $key->getColumn()->getName() . "`) REFERENCES `" . $key->getReferenceTable() . "` (" . $key->getReferenceColumn()->getName() . ")";
        }
        return $str . ";";
    }

    /**
     * @param Column $column
     * @return string
     * @throws InvalidTypeException
     */
    public function dumpColumn(Column $column)
    {
        return $column->toMysql();
    }

    /**
     * @param Table $tbl
     * @return string
     * @throws InvalidTypeException
     */
    public function dumpTable(Table $tbl)
    {
        $str = "CREATE TABLE `" . $tbl->getName() . "` (";
        foreach ($tbl->getColumns() as $col)
        {
            $str .= "\t" . $this->dumpColumn($col) . "\n,";
        }
        $str = substr($str, strlen($str) - 1);
        $str .= ") CHARSET=utf8_mb4;";
        foreach ($tbl->getKeys() as $key)
        {
            $str .= $this->dumpKey($key) . "\n";
        }
        foreach ($tbl->getAllRecords() as $record)
        {
            $str .= "INSERT INTO `" . $tbl->getName() . "` (" . join(", ", array_keys($record)) . ") VALUES (" . join(", ", array_values($record)) . ");\n";
        }
        return $str;
    }

    /**
     * @param Database $db
     * @return string
     * @throws InvalidTypeException
     */
    public function dumpDatabase(Database $db)
    {
        $str = "DROP DATABASE " . $db->getName() . ";";
        $str.= "CREATE DATABASE " . $db->getName() . ";";
        $str.= "USE " . $db->getName() . ";";
        foreach ($db->getTables() as $tbl)
        {
            $str .= $this->dumpTable($tbl);
        }

        return $str;
    }
}