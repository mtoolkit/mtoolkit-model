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

class MSqlRecord implements \ArrayAccess, \Iterator
{
    /**
     * @var int
     */
    private $at = 0;

    /**
     * @var array
     */
    private $record = array();

    /**
     * If no param is passed to method: constructs an empty record.
     * If param <i>$record</i> is passed to method: constructs a wrap for the array.
     *
     * @param array $record
     */
    public function __construct( array $record = array() )
    {
        $this->record = $record;
    }

    /**
     * Returns the number of fields in the record.
     *
     * @return int
     */
    public function count()
    {
        return count( $this->record );
    }

    /**
     * Removes all the record's fields.
     */
    public function clear()
    {
        $this->record = array();
    }

    /**
     * Clears the value of all fields in the record and sets each field to null.
     */
    public function clearValues()
    {
        foreach( $this->record as $key => $value )
        {
            $this->record[$key] = null;
        }
    }

    /**
     * Returns true if there is a field in the record called name; otherwise
     * returns false.
     *
     * @param string $name
     * @return mixed
     */
    public function contains( $name )
    {
        return array_key_exists( $name, $this->record );
    }

    /**
     * If param <i>$name</i> is an int: returns the field at position index.
     * If the index is out of range, function returns a null
     * value. <br />
     * <br />
     * If param <i>$name</i> is a string: returns the field called name.
     *
     * @param int|string $name
     * @return MSqlField|null
     */
    public function getField( $name )
    {
        if( $this->contains( $name ) === false )
        {
            return null;
        }

        $value = $this->record[$name];
        $type = MDataType::getType( $value );

        $field = new MSqlField( $name, $type );
        $field->setValue( $value );
        return $field;
    }

    /**
     * Returns the name of the field at position index. If the field does not
     * exist, an empty string is returned.
     *
     * @param int $index
     * @return null|string|int
     */
    public function getFieldName( $index )
    {
        $keyArray = array_keys( $this->record );

        if( $index > 0 && $index < $this->count() )
        {
            return $keyArray[$index];
        }

        return null;
    }

    /**
     * Returns the position of the field called name within the record, or -1 if
     * it cannot be found. Field names are not case-sensitive. If more than one
     * field matches, the first one is returned.
     *
     * @param string $name
     * @return int
     */
    public function getIndexOf( $name )
    {
        if( array_key_exists( $name, $this->record ) == false )
        {
            return -1;
        }

        $keyArray = array_keys( $this->record );

        return array_search( $name, $keyArray );
    }

    /**
     * Returns true if there are no fields in the record; otherwise returns false.
     *
     * @return boolean
     */
    public function isEmpty()
    {
        return (count( $this->record ) == 0);
    }

    /**
     * Returns true if the field <i>$name</i> is null or if there is no field at
     * position <i>$name</i>; otherwise returns false.
     *
     * @param string|int $name
     * @return boolean
     */
    public function isNull( $name )
    {
        if( $this->contains( $name ) == false )
        {
            return true;
        }

        $value = $this->record[$name];

        return ($value == null);
    }

    /**
     * Removes the field at position <i>$name</i>. If pos is out of range,
     * nothing happens.
     *
     * @param string|int $name
     */
    public function remove( $name )
    {
        if( $this->contains( $name ) == false )
        {
            return;
        }

        unset($this->record[$name]);
    }

    /**
     * Sets the value of field index to null. If the field does not exist,
     * nothing happens.
     *
     * @param string|int $name
     */
    public function setNull( $name )
    {
        if( $this->contains( $name ) == false )
        {
            return;
        }

        $this->record[$name] = null;
    }

    /**
     * Returns the value of the field located at position <i>$name</i> in the
     * record. If <i>$name</i> is out of bounds, a null value is returned.
     *
     * @param string|int $name
     * @return string
     * @deprecated Use {@link MSqlRecord#getBoolValue}, {@link MSqlRecord#getIntValue}, {@link MSqlRecord#getFloatValue}
     * or {@link MSqlRecord#getStringValue} instead.
     */
    public function getValue( $name )
    {
        if( $this->contains( $name ) == false )
        {
            return null;
        }

        return $this->record[$name];
    }

    /**
     * Retrieves the value of the designated column in the current row of this {@link MSqlRecord} object as an int in
     * the PHP programming language.
     *
     * @param string|int $name
     * @return int|null
     */
    public function getIntValue( $name )
    {
        $value = $this->offsetGet( $name );
        if( is_null( $value ) )
        {
            return null;
        }

        return (int)$value;
    }

    /**
     * Retrieves the value of the designated column in the current row of this {@link MSqlRecord} object as an float in
     * the PHP programming language.
     *
     * @param string|int $name
     * @return float|null
     */
    public function getFloatValue( $name )
    {
        $value = $this->offsetGet( $name );
        if( is_null( $value ) )
        {
            return null;
        }

        return (float)$value;
    }

    /**
     * Retrieves the value of the designated column in the current row of this {@link MSqlRecord} object as an bool in
     * the PHP programming language.
     *
     * @param string|int $name
     * @return bool|null
     */
    public function getBoolValue( $name )
    {
        $value = $this->offsetGet( $name );
        if( is_null( $value ) )
        {
            return null;
        }

        return (boolean)$value;
    }

    /**
     * Retrieves the value of the designated column in the current row of this {@link MSqlRecord} object as an string in
     * the PHP programming language.
     *
     * @param string|int $name
     * @return null|string
     */
    public function getStringValue( $name )
    {
        $value = $this->offsetGet( $name );
        if( is_null( $value ) )
        {
            return null;
        }

        return (string)$value;
    }

    /**
     * Return the current element
     *
     * @link  http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        $keys = array_keys( $this->record );
        return $keys[$this->at];
    }

    /**
     * Move forward to next element
     *
     * @link  http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        $this->at++;
    }

    /**
     * Return the key of the current element
     *
     * @link  http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        $keys = array_keys( $this->record );
        return $keys[$this->at];
    }

    /**
     * Checks if current position is valid
     *
     * @link  http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     *        Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        $fieldCount = count( $this->record );
        return ($this->at >= 0 && $this->at < $fieldCount);
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @link  http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->at = 0;
    }

    /**
     * Whether a offset exists
     *
     * @param int|string $offset
     * @return bool
     */
    public function offsetExists( $offset )
    {
        MDataType::mustBe( MDataType::INT | MDataType::STRING );

        if( is_int( $offset ) )
        {
            return $this->offsetExists_int( $offset );
        }

        return $this->offsetExists_string( $offset );
    }

    private function offsetExists_string( $offset )
    {
        MDataType::mustBe( MDataType::STRING );

        return array_key_exists( $offset, $this->record );
    }

    /**
     * @param int $offset
     * @return bool
     */
    private function offsetExists_int( $offset )
    {
        MDataType::mustBe( MDataType::INT );

        $fieldCount = count( $this->record );
        return ($offset >= 0 && $offset < $fieldCount);
    }

    /**
     * Offset to retrieve
     *
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet( $offset )
    {
        MDataType::mustBe( MDataType::INT | MDataType::STRING );

        if( is_int( $offset ) )
        {
            return $this->offsetGet_int( $offset );
        }

        return $this->offsetGet_string( $offset );
    }

    /**
     * @param $offset
     * @return mixed
     */
    public function offsetGet_int( $offset )
    {
        MDataType::mustBe( MDataType::INT );

        if( $this->offsetExists_int( $offset ) )
        {
            $keys = array_keys( $this->record );
            return $this->record[$keys[$offset]];
        }

        return null;
    }

    public function offsetGet_string( $offset )
    {
        MDataType::mustBe( MDataType::STRING );

        if( $this->offsetExists_string( $offset ) )
        {
            return $this->record[$offset];
        }

        return null;
    }

    /**
     * Offset to set
     *
     * @param mixed $offset
     * @param mixed $value
     * @throws MReadOnlyObjectException
     */
    public function offsetSet( $offset, $value )
    {
        throw new MReadOnlyObjectException( __CLASS__, __METHOD__ );
    }

    /**
     * Offset to unset
     *
     * @param mixed $offset
     * @throws MReadOnlyObjectException
     */
    public function offsetUnset( $offset )
    {
        throw new MReadOnlyObjectException( __CLASS__, __METHOD__ );
    }
}
