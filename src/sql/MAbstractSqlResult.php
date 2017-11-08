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
use mtoolkit\model\MTableModel;

abstract class MAbstractSqlResult extends MTableModel implements \ArrayAccess, \Iterator
{
    /**
     * @var MSqlError
     */
    private $lastError = null;

    public function __construct(MObject $parent = null)
    {
        parent::__construct($parent);

        $this->lastError = new MSqlError();
    }

    /**
     * Return an array contains the names of the fields.
     *
     * @return array
     */
    public abstract function getFields(): array;

    /**
     * Returns the last error associated with the result.
     *
     * @return MSqlError
     */
    protected function getLastError(): MSqlError
    {
        return $this->lastError;
    }

    /**
     * This function is provided for derived classes to set the last error to <i>$error</i>.
     *
     * @param MSqlError $lastError
     * @return MAbstractSqlResult
     */
    protected function setLastError(MSqlError $lastError): MAbstractSqlResult
    {
        $this->lastError = $lastError;
        return $this;
    }

}
