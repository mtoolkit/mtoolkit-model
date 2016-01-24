<?php

namespace mtoolkit\model\sql;

/*
 * This file is part of MToolkit.
 *
 * MToolkit is free software: you can redistribute it and/or modify
 * it under the terms of the LGNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * MToolkit is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * LGNU Lesser General Public License for more details.
 *
 * You should have received a copy of the LGNU Lesser General Public License
 * along with MToolkit.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * @author  Michele Pagnin
 */
use mtoolkit\core\MObject;

/**
 * The QSqlQuery class provides a means of executing and manipulating SQL 
 * statements.
 */
abstract class MAbstractSqlQuery extends MObject
{
    /**
     * @var MSqlError
     */
    private $lastError = null;

    public function __construct( MObject $parent = null )
    {
        parent::__construct( $parent );
        
        $this->lastError=new MSqlError();
    }

    /**
     * @var string 
     */
    private $query;

    /**
     * @var mixed The connection to db.
     */
    private $connection;

    /**
     * Executes a previously prepared SQL query. Returns true if the query 
     * executed successfully; otherwise returns false.<br />
     * Note that the last error for this query is reset when exec() is called.
     * 
     * @return bool The correct execution of the query.
     */
    public abstract function exec();

    /**
     * Returns the result associated with the query.
     * 
     * @return MAbstractSqlResult The resultset of the query, if exists.
     */
    public abstract function getResult();

    /**
     * @return string
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param string $query
     * @return \MToolkit\Model\Sql\MAbstractSqlQuery
     */
    public function setQuery( $query )
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param mixed $connection
     * @return \MToolkit\Model\Sql\MAbstractSqlQuery
     */
    protected function setConnection( $connection )
    {
        $this->connection = $connection;
        return $this;
    }

    /**
     * Returns the last error associated with the result.
     * 
     * @return MSqlError
     */
    public function getLastError()
    {
        return $this->lastError;
    }

    /**
     * This function is provided for derived classes to set the last error to <i>$error</i>.
     * 
     * @param MSqlError $lastError
     * @return \MToolkit\Model\Sql\MAbstractSqlResult
     */
    public function setLastError( $lastError )
    {
        $this->lastError = $lastError;
        return $this;
    }

}
