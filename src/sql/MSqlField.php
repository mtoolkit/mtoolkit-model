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
use mtoolkit\core\MDataType;

/**
 * The MSqlField class manipulates the fields in SQL database tables and
 * views.<br />
 * MSqlField represents the characteristics of a single column in a database
 * table or view, such as the data type and column name. A field also contains
 * the value of the database column, which can be viewed or changed.
 */
class MSqlField
{

    private $value = null;
    private $defaultValue = null;
    private $lenght = -1;
    private $type;
    private $name = null;

    /**
     * Constructs an empty field called <i>$name</i> of variant type <i>$type</i>.
     *
     * @param string $name
     * @param int $type MDataType::INT, MDataType::LONG, MDataType::BOOLEAN, etc
     */
    public function __construct(string $name = "", int $type = 99)
    {
        $this->clear();

        $this->name = $name;
        $this->type = $type;
    }

    /**
     * Clears the value of the field and sets it to NULL.
     */
    public function clear(): void
    {
        $this->value = null;
        $this->defaultValue = null;
        $this->lenght = -1;
        $this->type = MDataType::UNKNOWN;
        $this->name = null;
    }

    /**
     * Returns the field's default value (which may be NULL).
     *
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * Sets the default value used for this field to value.
     *
     * @param mixed $defaultValue
     * @return MSqlField
     */
    public function setDefaultValue($defaultValue): MSqlField
    {
        $this->defaultValue = $defaultValue;
        return $this;
    }

    /**
     * Returns the value of the field.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Sets the value of the field to value.<br />
     * To set the value to NULL, use clear().
     *
     * @param mixed $value
     * @return MSqlField
     */
    public function setValue($value): MSqlField
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Returns the field's length.<br />
     * If the returned value is negative, it means that the information is not
     * available from the database.
     *
     * @return int
     */
    public function getLenght(): int
    {
        return $this->lenght;
    }

    /**
     * Sets the field's length to <i>$lenght</i>. For strings this is the
     * maximum number of characters the string can hold; the meaning varies for
     * other types.
     *
     * @param int $lenght
     * @return MSqlField
     */
    public function setLenght(int $lenght): MSqlField
    {
        $this->lenght = $lenght;
        return $this;
    }

    /**
     * Returns true if the field's value is NULL; otherwise returns false.
     *
     * @return boolean
     */
    public function isNull()
    {
        return ($this->value == null);
    }

    /**
     * @return int MDataType::INT, MDataType::LONG, MDataType::BOOLEAN, etc
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * Set's the field's variant type to <i>$type</i>.
     *
     * @param int $type MDataType::INT, MDataType::LONG, MDataType::BOOLEAN, etc
     * @return MSqlField
     */
    public function setType(int $type): MSqlField
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Returns the name of the field.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return MSqlField
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

//    bool	isAutoValue() const
//    bool	isGenerated() const
//    bool	isReadOnly() const
//    bool	isValid() const
//    int	precision() const
//    RequiredStatus	requiredStatus() const
//    void	setAutoValue(bool autoVal)
//    void	setGenerated(bool gen)
//    void	setPrecision(int precision)
//    void	setReadOnly(bool readOnly)
//    void	setRequired(bool required)
//    void	setRequiredStatus(RequiredStatus required)
}
