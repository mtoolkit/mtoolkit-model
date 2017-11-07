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

use mtoolkit\core\exception\MReadOnlyObjectException;
use mtoolkit\core\MDataType;
use mtoolkit\core\MObject;

class MPDOResult extends MAbstractSqlResult
{
    /**
     * Used to map a row of the resultset into an object.
     * 
     * @var string
     */
    private $className=null;
    
    /**
     * Contains the names of the fields.
     * 
     * @var array
     */
    private $fields = array();

    /**
     * @var \PDOStatement
     */
    private $statement;

    /**
     * @var int
     */
    private $at = 0;

    /**
     * @var array
     */
    private $rows = null;

    /**
     * @var int
     */
    private $rowCount = 0;

    /**
     * @var int
     */
    private $columnCount = 0;

    /**
     * @param \PDOStatement $statement
     * @param MObject|null $parent
     */
    public function __construct( \PDOStatement $statement, MObject $parent = null )
    {
        parent::__construct( $parent );

        $this->statement = $statement;
        $this->rows = $this->statement->fetchAll( \PDO::FETCH_ASSOC );
        $this->fields = empty( $this->rows ) ? array() : array_keys( (array) $this->rows[0] );
        $this->rowCount = count( $this->rows );
        $this->columnCount = count( $this->fields );
    }

    /**
     * Return the number of rows in resultset.
     * 
     * @return int
     */
    public function rowCount(): int
    {
        return $this->rowCount;
    }

    /**
     * Return the number of columns in resultset.
     * 
     * @return int
     */
    public function columnCount(): int
    {
        return $this->columnCount;
    }

    /**
     * Return an array contains the names of the fields.
     * 
     * @return array
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * Return the data at the <i>row</i> and <i>column</i>.
     *
     * @param int $row
     * @param int|string $column
     * @return mixed
     */
    public function getData(int $row, $column)
    {
        return $this->rows[$row][$column];
    }

    public function getNumRowsAffected()
    {
        return $this->statement->rowCount();
    }

    /**
     * Returns the current record if the query is active; otherwise returns an empty QSqlRecord. <br />
     * The default implementation always returns an empty QSqlRecord.
     * 
     * @return MSqlRecord
     */
    public function getRecord()
    {
        return new MSqlRecord( $this->rows[$this->at] );
    }

    /**
     * Returns the current (zero-based) row position of the result. May return 
     * the special values MSql\Location::BeforeFirstRow or MSql\Location::AfterLastRow.
     * 
     * @return int
     */
    public function getAt()
    {
        if( $this->at < 0 )
        {
            return Location::BeforeFirstRow;
        }

        if( $this->at > $this->rowCount() )
        {
            return Location::AfterLastRow;
        }

        return $this->at;
    }

    /**
     * This function is provided for derived classes to set the internal 
     * (zero-based) row position to <i>$at</i>.
     * 
     * @param int $at
     * @return MPDOResult
     */
    public function setAt( $at )
    {
        $this->at = $at;
        return $this;
    }

    /**
     * @return MSqlRecord
     */
    public function current()
    {
        return new MSqlRecord($this->rows[$this->getAt()]);
    }

    public function key()
    {
        return $this->getAt();
    }

    /**
     * Positions the result to the next available record (row) in the result.
     */
    public function next()
    {
        $this->at++;
    }

    public function offsetExists( $offset )
    {
        return (array_key_exists( $offset, $this->rows ) === true);
    }

    public function offsetGet( $offset )
    {
        if( $this->offsetExists( $offset ) )
        {
            return new MSqlRecord($this->rows[$offset]);
        }

        return null;
    }

    public function offsetSet( $offset, $value )
    {
        throw new MReadOnlyObjectException( __CLASS__, __METHOD__ );
    }

    public function offsetUnset( $offset )
    {
        throw new MReadOnlyObjectException( __CLASS__, __METHOD__ );
    }

    /**
     * Set current 0 as current row.
     */
    public function rewind()
    {
        $this->at = 0;
    }

    /**
     * Returns true if the result is positioned on a valid record (that is, the 
     * result is not positioned before the first or after the last record); 
     * otherwise returns false.
     * 
     * @return boolean
     */
    public function valid()
    {
        return ( $this->at >= 0 && $this->at < $this->rowCount() );
    }

    /**
     * Returns the resultset as array.
     * 
     * @return array
     */
    public function toArray()
    {
        return $this->rows;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param string $className
     * @return MPDOResult
     */
    public function setClassName( $className )
    {
        MDataType::mustBeNullableString($className);
        
        $this->className = $className;
        return $this;
    }

    /**
     * Returns instances of the specified class, mapping the columns of the 
     * current row to named properties in the class.
     * 
     * @return object
     */
    public function getCurrentObject()
    {
        return $this->getObjectAt($this->at);
    }
    
    /**
     * Returns instances of the specified class, mapping the columns of the 
     * <i>$at</i> pos row to named properties in the class.
     * 
     * @param int $at
     * @return object
     */
    public function getObjectAt( $at )
    {
        $row=$this->rows[$at];
        $obj=new $this->className();
        
        foreach( $row as $key => $value )
        {
            $obj->$key=$value;
        }
        
        return $obj;
    }
}
