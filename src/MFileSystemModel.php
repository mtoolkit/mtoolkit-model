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
use mtoolkit\core\MObject;

/**
 * The MFileSystemModel class provides a data model for the local filesystem.<br>
 * <br>
 * This class provides access to the local filesystem, providing functions for renaming and removing files and
 * directories, and for creating new directories. In the simplest case, it can be used with a suitable display widget
 * as part of a browser or filter.<br>
 * <br>
 * MFileSystemModel can be accessed using the standard interface provided by MAbstractDataModel, but it also provides
 * some convenience functions that are specific to a directory model. The fileInfo(), isDir(), fileName() and filePath()
 * functions provide information about the underlying files and directories related to items in the model. Directories
 * can be created and removed using mkdir(), rmdir().
 *
 * @package MToolkit\Model
 */
class MFileSystemModel extends MAbstractDataModel
{
    /**
     * @signal This signal is emitted whenever the root path has been changed to a newPath.
     */

    const ROOT_PATH_CHANGED = "ROOT_PATH_CHANGED";

    /**
     * @var string
     */
    private $rootPath;

    /**
     *
     * @var string[]
     */
    private $fileList = array();

    public function __construct(MObject $parent = null)
    {
        parent::__construct($parent);
    }

    /**
     * Returns true if the item at <i>$row</i> position represents a directory; otherwise returns false.
     * 
     * @param int $row
     * @param int $column
     * @return boolean
     */
    public function isDir($row, $column)
    {
        $file = $this->getData($row, $column);
        
        if( $file==null )
        {
            return false;
        }
        
        $file = rtrim($file, DIRECTORY_SEPARATOR);
        $file = $this->rootPath . DIRECTORY_SEPARATOR . $file;

        return is_dir($file);
    }
    
    /**
     * Returns the path of the item at <i>$row</i> position stored in the model under the index given.
     * 
     * @param int $row
     * @param int $column
     * @return null|string
     */
    public function filePath($row, $column)
    {
        $file=$this->getData($row, $column);
        
        if( $file==null )
        {
            return null;
        }
        
        $file = rtrim($file, DIRECTORY_SEPARATOR);
        $file = $this->rootPath . DIRECTORY_SEPARATOR . $file;
        
        return $file;
    }

    /**
     * Returns the date and time when index was last modified.
     * 
     * @param int $row
     * @param int $column
     * @return null|\DateTime
     */
    public function lastModified($row, $column)
    {
        $file=$this->getData($row, $column);
        
        if( $file==null )
        {
            return null;
        }
        
        $file = rtrim($file, DIRECTORY_SEPARATOR);
        $file = $this->rootPath . DIRECTORY_SEPARATOR . $file;
        
        $format="YmdHis";
        
        $dateTime=\DateTime::createFromFormat($format, date ($format, filemtime($file)));
        
        return $dateTime;
    }
    
    /**
     * @return int
     */
    public function columnCount()
    {
        return 1;
    }

    public function getData($row, $column)
    {
        if ($row < 0 || $row >= $this->rowCount() || $column < 0 || $column >= $this->columnCount())
        {
            return null;
        }
        
        return $this->fileList[$row];
    }

    /**
     * @return int
     */
    public function rowCount()
    {
        return count($this->fileList);
    }

    /**
     * The currently set root path.
     * 
     * @return null|string
     */
    public function getRoot()
    {
        return $this->rootPath;
    }

    /**
     * Sets the directory that is being watched by the model to newPath by installing a file system watcher on it. Any
     * changes to files and directories within this directory will be reflected in the model.<br>
     * If the path is changed, the ROOT_PATH_CHANGED signal will be emitted.
     * 
     * @param string $rootPath
     * @return \MToolkit\Model\MFileSystemModel
     */
    public function setRoot($rootPath)
    {
        if (file_exists($rootPath) === false)
        {
            return null;
        }

        $this->rootPath = $rootPath;

        $this->fileList = scandir($this->rootPath);

        $this->emit(MFileSystemModel::ROOT_PATH_CHANGED);

        return $this;
    }
    
    /**
     * Returns the size in bytes of file at <i>$row</i> position.
     * If the file does not exist, 0 is returned.
     * 
     * @param int $row
     * @param int $column
     * @return int
     */
    public function size($row, $column)
    {
        $file=$this->getData($row, $column);
        
        if( $file==null )
        {
            return 0;
        }
        
        $file = rtrim($file, DIRECTORY_SEPARATOR);
        $file = $this->rootPath . DIRECTORY_SEPARATOR . $file;
        
        if( file_exists($file)===false )
        {
            return 0;
        }
        
        return filesize($file);
    }

    /**
     * Returns the type of file at <i>$row</i> position, like <i>filetype</i>.
     * 
     * @param int $row
     * @param int $column
     * @return null|string
     */
    public function type($row, $column)
    {
        $file=$this->getData($row, $column);
        
        if( $file==null )
        {
            return null;
        }
        
        $file = rtrim($file, DIRECTORY_SEPARATOR);
        $file = $this->rootPath . DIRECTORY_SEPARATOR . $file;
        
        $fileType= filetype($file);
        
        if( $fileType!='file' )
        {
            return $fileType;
        }
        
        return mime_content_type($file);
    }
}
