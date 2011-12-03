<?php
/**
 * Pop PHP Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.TXT.
 * It is also available through the world-wide-web at this URL:
 * http://www.popphp.org/LICENSE.TXT
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to info@popphp.org so we can send you a copy immediately.
 *
 * @category   Pop
 * @package    Pop_Archive
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2012 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/LICENSE.TXT     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Archive;

use Pop\Archive\Adapter\Phar,
    Pop\Archive\Adapter\Rar,
    Pop\Archive\Adapter\Tar,
    Pop\Archive\Adapter\Zip,
    Pop\Compress\Bzip2,
    Pop\Compress\Gzip,
    Pop\File\File;

/**
 * @category   Pop
 * @package    Pop_Archive
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2012 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/LICENSE.TXT     New BSD License
 * @version    0.9
 */
class Archive extends File implements ArchiveInterface
{

    /**
     * Array of allowed file types.
     * @var array
     */
    protected $_allowed = array('bz2'  => 'application/bzip2',
                                'gz'   => 'application/x-gzip',
                                'phar' => 'application/x-phar',
                                'rar'  => 'application/x-rar-compressed',
                                'tar'  => 'application/x-tar',
                                'tbz'  => 'application/bzip2',
                                'tbz2' => 'application/bzip2',
                                'tgz'  => 'application/x-gzip',
                                'zip'  => 'application/x-zip');

    /**
     * Archive adapter
     * @var mixed
     */
    protected $_adapter = null;

    /**
     * Constructor
     *
     * Instantiate the archive object
     *
     * @param  string $archive
     * @param  string $password
     * @return void
     */
    public function __construct($archive, $password = null)
    {
        // Check if Bzip2 is available.
        if (!function_exists('bzcompress')) {
            unset($this->_allowed['bz2']);
            unset($this->_allowed['tbz2']);
        }
        // Check if Gzip is available.
        if (!function_exists('gzcompress')) {
            unset($this->_allowed['gz']);
            unset($this->_allowed['tgz']);
        }
        // Check if Phar is available.
        if (!class_exists('Phar')) {
            unset($this->_allowed['phar']);
        }
        // Check if Rar is available.
        if (!class_exists('RarArchive')) {
            unset($this->_allowed['tar']);
        }
        // Check if Tar is available.
        if (!class_exists('Archive_Tar')) {
            unset($this->_allowed['tar']);
        }
        // Check if Zip is available.
        if (!class_exists('ZipArchive')) {
            unset($this->_allowed['zip']);
        }

        parent::__construct($archive);

        $this->_setAdapter($password);
    }

    /**
     * Static method to instantiate the archive object and return itself
     * to facilitate chaining methods together.
     *
     * @param  string $archive
     * @return Pop\Archive\Archive
     */
    public static function factory($archive)
    {
        return new self($archive);
    }

    /**
     * Method to return the adapter object
     *
     * @return mixed
     */
    public function adapter()
    {
        return $this->_adapter;
    }

    /**
     * Method to extract an archived and/or compressed file
     *
     * @param  string $to
     * @return Pop\Archive\Archive
     */
    public function extract($to = null)
    {
        $this->_adapter->extract($to);
        return $this;
    }

    /**
     * Method to create an archive file
     *
     * @param  string|array $files
     * @return Pop\Archive\Archive
     */
    public function addFiles($files)
    {
        $this->_adapter->addFiles($files);
        return $this;
    }

    /**
     * Method to return a listing of the contents of an archived file
     *
     * @param  boolean $full
     * @return array
     */
    public function listFiles($full = false)
    {
        return $this->_adapter->listFiles($full);
    }

    /**
     * Method to compress an archive file with Gzip or Bzip2
     *
     * @param  string $ext
     * @return Pop\Archive\Archive
     */
    public function compress($ext = 'gz')
    {
        if ($ext == 'bz') {
            $ext .= '2';
        }
        switch ($ext) {
            case 'gz':
                $newArchive = Gzip::compress($this->fullpath);
                break;
            case 'tgz':
                $tmpArchive = Gzip::compress($this->fullpath);
                $newArchive = str_replace('.tar.gz', '.tgz', $tmpArchive);
                rename($tmpArchive, $newArchive);
                break;
            case 'bz2':
                $newArchive = Bzip2::compress($this->fullpath);
                break;
            case 'tbz':
                $tmpArchive = Bzip2::compress($this->fullpath);
                $newArchive = str_replace('.tar.bz2', '.tbz', $tmpArchive);
                rename($tmpArchive, $newArchive);
                break;
            case 'tbz2':
                $tmpArchive = Bzip2::compress($this->fullpath);
                $newArchive = str_replace('.tar.bz2', '.tbz2', $tmpArchive);
                rename($tmpArchive, $newArchive);
                break;
        }

        if (file_exists($this->fullpath)) {
            unlink($this->fullpath);
        }

        self::__construct($newArchive);
        return $this;
    }

    /**
     * Method to set the adapter based on the file name
     *
     * @return void
     */
    protected function _setAdapter($password = null)
    {
        $ext = strtolower($this->ext);

        if ($ext == 'phar') {
            $this->_adapter = new Phar($this);
        } else if ($ext == 'rar') {
            $this->_adapter = new Rar($this, $password);
        } else if (($ext == 'tar') || (stripos($ext, 'bz') !== false) || (stripos($ext, 'gz') !== false)) {
            $this->_adapter = new Tar($this);
        } else if ($ext == 'zip') {
            $this->_adapter = new Zip($this);
        }
    }

}
