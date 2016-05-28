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
class MPDOQuery extends MAbstractSqlQuery
{

    /**
     * @var int[]|string[]|double[]
     */
    private $bindedValues = array();

    /**
     * @var MPDOResult
     */
    private $result = null;

    /**
     * Constructs a MPDOQuery object using the SQL <i>$query</i> and the database
     * <i>$connection</i>. If <i>$connection</i> is not specified, or is invalid,
     * the application's default database is used. <br />
     * If query is not an empty string, it will be executed.
     *
     * @param null $query
     * @param \PDO|null $connection
     * @param MObject|null $parent
     */
    public function __construct( $query = null, \PDO $connection = null, MObject $parent = null )
    {
        parent::__construct( $parent );

        $this->setQuery( $query )
            ->setConnection( $connection );

        if( $this->getConnection() == null )
        {
            $this->setConnection( MDbConnection::getDbConnection() );
        }
    }

    /**
     * @return \PDO
     */
    public function getConnection()
    {
        return parent::getConnection();
    }

    /**
     * Prepares the SQL query query for execution. Returns true if the query is
     * prepared successfully; otherwise returns false.<br />
     * The query may contain placeholders for binding values. Both Oracle style
     * colon-name (e.g., :surname), and ODBC style (?) placeholders are
     * supported; but they cannot be mixed in the same query. See the Detailed
     * Description for examples.<br />
     * Portability note: Some databases choose to delay preparing a query until
     * it is executed the first time. In this case, preparing a syntactically
     * wrong query succeeds, but every consecutive exec() will fail.<br />
     * For SQLite, the query string can contain only one statement at a time. If
     * more than one statements are give, the function returns false.
     *
     * @param string $query
     * @return bool
     */
    public function prepare( $query )
    {
        $sqlStmt = $this->getConnection()->prepare( $query );

        if( $sqlStmt == false )
        {
            return false;
        }

        $this->setQuery( $query );
        return true;
    }

    /**
     * Bind the <i>value</i> to query.
     * Call this method in order with the '<i>?</i>' in the sql statement.
     *
     * @param mixed $value
     * @throws \Exception
     */
    public function bindValue( $value )
    {
        if( $this->getPDOType( $value ) === false )
        {
            throw new \Exception( gettype( $value ) . " is an invalid type to bind. " );
        }

        $this->bindedValues[] = $value;
    }

    /**
     * Bind the <i>values</i> to query.
     * Sort the values in the array in order with the '<i>?</i>' in the sql statement.
     *
     * @param array|int $values,...
     */
    public function bindValues( $values )
    {
        $params = func_get_args();
        if( isset($params[0]) && is_array( $params[0] ) )
        {
            $this->bindedValues = new \ArrayObject( $params[0] );
        }
        else
        {
            $this->bindedValues = new \ArrayObject( $params );
        }
    }

    /**
     *
     * @param mixed $value
     * @return \PDO::PARAM_INT|\PDO::PARAM_BOOL|\PDO::PARAM_NULL|\PDO::PARAM_STR
     */
    private function getPDOType( $value )
    {
        switch( true )
        {
            case is_int( $value ):
                return \PDO::PARAM_INT;
            case is_bool( $value ):
                return \PDO::PARAM_BOOL;
            case is_null( $value ):
                return \PDO::PARAM_NULL;
            case is_string( $value ):
                return \PDO::PARAM_STR;
        }

        return false;
    }

    /**
     * Executes a previously prepared SQL query. Returns true if the query
     * executed successfully; otherwise returns false.<br />
     * Note that the last error for this query is reset when exec() is called.
     *
     * @return bool
     * @throws \Exception
     */
    public function exec()
    {
        /* @var $sqlStmt \PDOStatement */
        $sqlStmt = $this->getConnection()->prepare( $this->getQuery() );

        if( $sqlStmt === false )
        {
            return $this->execFails( $sqlStmt, ErrorType::CONNECTION_ERROR );
        }

        // Bind input
        foreach( $this->bindedValues as /* @var $i int */
                 $i => $bindedValue )
        {
            $type = $this->getPDOType( $bindedValue );

            if( $type === false )
            {
                throw new \Exception( 'Invalid type of binded value at position ' . ($i + 1) . '.' );
            }

            $bindParamsResult = $sqlStmt->bindValue( $i + 1, $bindedValue, $type );

            if( $bindParamsResult === false )
            {
                return $this->execFails( $sqlStmt, ErrorType::BINDING_ERROR );
            }
        }

        // Exec query
        $result = $sqlStmt->execute();

        if( $result == false )
        {
            return $this->execFails( $sqlStmt, ErrorType::STATEMENT_ERROR );
        }

        $this->result = new MPDOResult( $sqlStmt );

        $sqlStmt->closeCursor();

        return true;
    }

    private function execFails( \PDOStatement $sqlStmt, $errorType )
    {
        $errorInfo = $sqlStmt->errorInfo();

        parent::setLastError( new MSqlError( $errorInfo[2], (string)$errorInfo[1], $errorType, $sqlStmt->errorCode() ) );
        $this->result = new MPDOResult( new \PDOStatement(), $this );

        return false;
    }

    /**
     * Returns the result associated with the query.
     *
     * @return MPDOResult
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Returns the object ID of the most recent inserted row if the database
     * supports it. An invalid QVariant will be returned if the query did not
     * insert any value or if the database does not report the id back. If more
     * than one row was touched by the insert, the behavior is undefined.
     *
     * @return string
     */
    public function getLastInsertId()
    {
        return $this->getConnection()->lastInsertId();
    }

    /**
     * Returns the number of rows affected by the result's SQL statement, or -1
     * if it cannot be determined. Note that for SELECT statements, the value is
     * undefined; use size() instead. If the query is not active, -1 is returned.
     *
     * @return int
     */
    public function getNumRowsAffected()
    {
        if( $this->result == null )
        {
            return -1;
        }

        return $this->result->getNumRowsAffected();
    }

}
