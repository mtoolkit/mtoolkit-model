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
use mtoolkit\model\MAbstractDataModel;

class MSqlQueryModel extends MAbstractDataModel
{
    /**
     * @var MAbstractSqlQuery
     */
    private $query;

    /**
     * Creates an empty MSqlQueryModel with the given parent.
     *
     * @param \MToolkit\Core\MObject $parent
     */
    public function __construct(MObject $parent = null)
    {
        parent::__construct($parent);
    }

    /**
     * Returns the QSqlQuery associated with this model.
     *
     * @param string $query
     * @param \PDO|null $db
     * @throws \Exception
     */
    public function setQuery($query, $db = null)
    {
        if ($db == null) {
            $db = MDbConnection::getDbConnection();
        }

        if ($db instanceof \PDO) {
            $this->query = new MPDOQuery($query, $db);
        } else {
            throw new \Exception("Database connection not supported.");
        }

        $this->query->exec();
    }

    /**
     * Reimplemented from MAbstractDataModel::columnCount().
     *
     * @return int
     */
    public function columnCount(): int
    {
        return $this->query->getResult()->columnCount();
    }

    /**
     * Reimplemented from MAbstractDataModel::getData().
     *
     * @param int $row
     * @param int $column
     * @return mixed
     */
    public function getData(int $row, $column)
    {
        return $this->query->getResult()->getData($row, $column);
    }

    /**
     * Reimplemented from MAbstractDataModel::rowCount().
     *
     * @return int
     */
    public function rowCount(): int
    {
        return $this->query->getResult()->rowCount();
    }
}
