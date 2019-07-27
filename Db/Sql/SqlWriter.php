<?php


namespace Db\Sql;


use Db\Column;
use Db\Database;
use Db\Key;
use Db\Sql\Impl\MySqlWriter;
use Db\Table;

abstract class SqlWriter
{
    const DEFAULT = MySqlWriter::class;

    /**
     * @param Key $key
     * @return string
     */
    public abstract function dumpKey(Key $key);

    /**
     * @param Column $column
     * @return string
     */
    public abstract function dumpColumn(Column $column);

    /**
     * @param Table $tbl
     * @return string
     */
    public abstract function dumpTable(Table $tbl);

    /**
     * @param Database $db
     * @return string
     */
    public abstract function dumpDatabase(Database $db);
}