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

class MTableModel extends MAbstractDataModel
{
    private $data = array();

    public function __construct(MObject $parent = null)
    {
        parent::__construct($parent);
    }

    /**
     * @param array $data
     */
    public function setDataFromArray(array $data): void
    {
        $this->data = $data;
    }

    /**
     * @return int
     */
    public function columnCount(): int
    {
        if ($this->rowCount() <= 0) {
            return 0;
        }

        return count($this->data[0]);
    }

    /**
     * @param int $row
     * @param mixed $column
     * @return mixed
     */
    public function getData(int $row, $column)
    {
        return $this->data[$row][$column];
    }

    /**
     * @return int
     */
    public function rowCount(): int
    {
        return count($this->data);
    }

    /**
     * Returns the data for the given <i>$section</i> in the header with the specified <i>$orientation</i>.
     *
     * @param int|string $section
     * @param int|Orientation $orientation
     * @return mixed
     */
    public function getHeaderData($section, int $orientation)
    {
        $headerData = null;

        if (count($this->data) < 0) {
            return $headerData;
        }

//        echo $section;

        switch ($orientation) {
            case Orientation::HORIZONTAL:
                $fields = array_keys($this->data[0]);
                $headerData = $fields[$section];
                break;
            case Orientation::VERTICAL:
                $fields = array_keys($this->data);
                $headerData = $fields[$section];
                break;
        }

        return $headerData;
    }

    /**
     * Sets the data for the given <i>$section</i> in the header with the specified <i>$orientation</i> to the value supplied.<br />
     * Returns true if the header's data was updated; otherwise returns false.
     *
     * @param int|string $section
     * @param int|Orientation $orientation
     * @param mixed $value
     * @return bool
     */
    public function setHeaderData($section, int $orientation, $value): bool
    {
        $toReturn = false;

        if (count($this->data) > 0) {
            return $toReturn;
        }

        switch ($orientation) {
            case Orientation::HORIZONTAL:
                for ($i = 0; $i < $this->rowCount(); $i++) {
                    $fields = array_keys($this->data[$i]);
                    $values = array_values($this->data[$i]);
                    $fields[$section] = $value;
                    $this->data[$i] = array_combine($fields, $values);
                }
                $toReturn = true;
                break;
            case Orientation::VERTICAL:
                $fields = array_keys($this->data);
                $values = array_values($this->data);
                $fields[$section] = $value;
                $this->data = array_combine($fields, $values);
                $toReturn = true;
                break;
        }

        return $toReturn;
    }

}
