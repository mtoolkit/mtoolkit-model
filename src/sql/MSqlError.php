<?php

namespace mtoolkit\model\sql;

/*
 * This file is part of MToolkit.
 *
 * MToolkit is free software: you can redistribute it and/or modify
 * it under the terms of the LGNU Lesser General Public License as published by
 * the Free Software Foundation; either version 3 of the License; or
 * (at your option) any later version.
 *
 * MToolkit is distributed in the hope that it will be useful;
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * LGNU Lesser General Public License for more details.
 *
 * You should have received a copy of the LGNU Lesser General Public License
 * along with MToolkit.  If not; see <http://www.gnu.org/licenses/>.
 *
 * @author  Michele Pagnin
 */

/**
 * The MSqlError class provides SQL database error information.<br />
 * A MSqlError object can provide database-specific error data, including the
 * <i>driverText()</i> and <i>databaseText()</i> messages (or both concatenated
 * together as  <i>text()</i>), and the <i>nativeErrorCode()</i> and <i>type()</i>.
 */
class MSqlError
{
    private $driverText;
    private $databaseText;
    private $type;
    private $code;

    /**
     * Constructs an error containing the driver error text <i>$driverText</i>, the database-specific error text
     * <i>$databaseText</i>, the type <i>$type</i> and the error code <i>$code</i>.
     *
     * @param string $driverText
     * @param string $databaseText
     * @param int $type
     * @param string $code
     */
    public function __construct(string $driverText = "", string $databaseText = "", int $type = ErrorType::NO_ERROR, string $code = "")
    {
        $this->driverText = $driverText;
        $this->databaseText = $databaseText;
        $this->type = $type;
        $this->code = $code;
    }

    /**
     * Returns the text of the error as reported by the driver. This may contain database-specific descriptions. It may
     * also be empty.
     *
     * @return null|string
     */
    public function getDriverText():?string
    {
        return $this->driverText;
    }

    /**
     * Returns the text of the error as reported by the database. This may contain database-specific descriptions; it
     * may be empty.
     *
     * @return string|null
     */
    public function getDatabaseText()
    {
        return $this->databaseText;
    }

    /**
     * Returns the error type, or -1 if the type cannot be determined.
     *
     * @return int|ErrorType
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * Returns the database-specific error code, or an empty string if it cannot be determined.
     *
     * @return string
     */
    public function getNativeErrorCode()
    {
        return $this->code;
    }

    /**
     * Returns true if an error is set, otherwise false.<br />
     * Example:
     * <code>
     * $sql=new MPDOQuery("select * from myTable");
     * $sql->exec();
     * if ($sql->getResult()->isValid())
     * {
     *      echo "ERROR!!!";
     * }
     * </code>
     *
     * @return boolean
     */
    public function isValid()
    {
        return !($this->driverText == "" && $this->databaseText == "" && $this->type == ErrorType::NO_ERROR && $this->code == "");
    }

    /**
     * This is a convenience function that returns <i>databaseText()</i> and <i>driverText()</i> concatenated into a
     * single string.
     *
     * @return string
     */
    public function text()
    {
        return $this->databaseText . " - " . $this->driverText;
    }

}

final class ErrorType
{
    const NO_ERROR = 0;
    const CONNECTION_ERROR = 1;
    const STATEMENT_ERROR = 2;
    const TRANSACTION_ERROR = 3;
    const UNKNOWN_ERROR = 4;
    const BINDING_ERROR = 5;
}
