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

class MDbConnection
{
    /**
     * @var array
     */
    private $connection=array();

    /**
     * @var MDbConnection
     */
    private static $instance=null;

    private function  __construct()
    {}

    /**
     * @param string $name
     * @return mixed
     */
    public static function getDbConnection( $name="DefaultConnection" )
    {
        if( is_null( MDbConnection::$instance ) )
        {
            return null;
        }

        return MDbConnection::$instance->connection[$name];
    }

    /**
     * @param mixed $connection
     * @param string $name
     */
    public static function addDbConnection( $connection, $name="DefaultConnection" )
    {
        if( is_null( MDbConnection::$instance ) )
        {
            MDbConnection::$instance=new MDbConnection();
        }

        MDbConnection::$instance->connection[$name]=$connection;
    }

    /**
     * @param string $name
     */
    public static function removeDbConnection( $name="DefaultConnection" )
    {
        if( is_null( MDbConnection::$instance ) )
        {
            return;
        }

        MDbConnection::$instance->connection[$name]=null;
    }
}

