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
use mtoolkit\core\MList;
use mtoolkit\core\MObject;

class MListModel extends MAbstractDataModel
{

    /**
     * @var MList
     */
    private $data = null;

    /**
     * @param \MToolkit\Core\MObject $parent
     */
    public function __construct(MObject $parent = null)
    {
        parent::__construct($parent);

        $this->data = new MList();
    }

    public function setDataFromArray(array $data)
    {
        $this->data->fromArray($data);
    }

    /**
     * Return the number of rows in resultset.
     *
     * @return int
     */
    public function rowCount(): int
    {
        return $this->data->size();
    }

    /**
     * Return the number of columns in resultset.
     *
     * @return int
     */
    public function columnCount(): int
    {
        return 1;
    }

    /**
     * Return the data at the <i>row</i> and <i>column</i>.
     *
     * @param int $row
     * @param int $column
     * @return mixed
     */
    public function getData(int $row, $column = 0)
    {
        return $this->data->at($row);
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

        if ($this->data->size() > 0) {
            return $headerData;
        }

        switch ($orientation) {
            case Orientation::HORIZONTAL:
                break;
            case Orientation::VERTICAL:
                $fields = array_keys($this->data->toArray());
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

        if ($this->data->size() > 0) {
            return $toReturn;
        }

        switch ($orientation) {
            case Orientation::HORIZONTAL:
                break;
            case Orientation::VERTICAL:
                $fields = array_keys($this->data->toArray());
                $values = array_values($this->data->toArray());
                $fields[$section] = $value;
                $this->data = array_combine($fields, $values);
                $toReturn = true;
                break;
        }

        return $toReturn;
    }

}

