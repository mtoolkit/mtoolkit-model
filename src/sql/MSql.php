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

/**
 * This enum type describes special SQL navigation locations:
 * <ul>
 * <li>BeforeFirstRow    -1    Before the first record.</li>
 * <li>AfterLastRow    -2    After the last record.</li>
 * </ul>
 */
final class Location
{
    public const BeforeFirstRow = -1;
    public const AfterLastRow = -2;
}