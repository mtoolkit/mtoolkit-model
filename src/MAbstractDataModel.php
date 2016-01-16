<?php
namespace mtoolkit\model;

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

use mtoolkit\core\enum\Orientation;
use mtoolkit\core\MObject;

abstract class MAbstractDataModel extends MObject
{

    public function __construct( MObject $parent = null )
    {
        parent::__construct( $parent );
    }

    /**
     * Returns the data for the given <i>$section</i> in the header with the specified <i>$orientation</i>.
     * 
     * @param int|string $section
     * @param int|Orientation $orientation
     * @return null
     */
    public function getHeaderData( $section, $orientation )
    {
        return null;
    }
    
    /**
     * Sets the data for the given <i>$section</i> in the header with the specified <i>$orientation</i> to the value supplied.<br />
     * Returns true if the header's data was updated; otherwise returns false.
     * 
     * @param int|string $section
     * @param int|Orientation $orientation
     * @param mixed $value
     * @return false
     */
    public function setHeaderData( $section, $orientation, $value )
    {
        return false;
    }

    /**
     * Return the number of rows in resultset.
     * 
     * @return int
     */
    public abstract function rowCount();

    /**
     * Return the number of columns in resultset.
     * 
     * @return int
     */
    public abstract function columnCount();

    /**
     * Return the data at the <i>row</i> and <i>column</i>.
     * 
     * @param int $row
     * @param mixed $column
     */
    public abstract function getData( $row, $column );

    /**
     * Returns true if element has any children; otherwise returns false.
     * @param int $row
     * @param int $column
     * @return boolean
     */
    public function hasChildren( $row, $column )
    {
        return false;
    }

}
