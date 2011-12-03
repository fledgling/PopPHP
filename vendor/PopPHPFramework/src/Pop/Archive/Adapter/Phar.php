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
namespace Pop\Archive\Adapter;

use Pop\Archive\ArchiveInterface,
    Pop\Dir\Dir,
    Pop\File\File,
    Pop\Filter\String;

/**
 * @category   Pop
 * @package    Pop_Archive
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2012 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/LICENSE.TXT     New BSD License
 * @version    0.9
 */
class Phar implements ArchiveInterface
{

    /**
     * ZipArchive object
     * @var ZipArchive
     */
    protected $_archive = null;

    /**
     * Archive path
     * @var string
     */
    protected $_path = null;

    /**
     * Method to instantiate an archive adapter object
     *
     * @param  string $archive
     * @return void
     */
    public function __construct($archive)
    {
        $this->_path = $archive->fullpath;
        $this->_archive = new \Phar($this->_path);
    }

    /**
     * Method to extract an archived and/or compressed file
     *
     * @param  string $to
     * @return void
     */
    public function extract($to = null)
    {
        $this->_archive->extractTo((null !== $to) ? $to : './');
    }

    /**
     * Method to create an archive file
     *
     * @param  string|array $files
     * @return void
     */
    public function addFiles($files)
    {
        if (!is_array($files)) {
            $files = array($files);
        }

        // Directory separator clean up
        $seps = array(
                    array('\\', '/'),
                    array('../', ''),
                    array('./', '')
                );

        foreach ($files as $file) {
            // If file is a directory, loop through and add the files.
            if (file_exists($file) && is_dir($file)) {
                $dir = new Dir($file, true, true);
                $this->_archive->addEmptyDir((string)String::factory($dir->path)->replace($seps));
                foreach ($dir->files as $fle) {
                    if (file_exists($fle) && is_dir($fle)) {
                        $this->_archive->addEmptyDir((string)String::factory($fle)->replace($seps));
                    } else if (file_exists($fle)) {
                        $this->_archive->addFile($fle, (string)String::factory($fle)->replace($seps));
                    }
                }
            // Else, just add the file.
            } else if (file_exists($file)) {
                $this->_archive->addFile($file, str_replace('\\', '/', $file));
            }
        }
    }

    /**
     * Method to set a default stub for the PHAR archive
     *
     * @param  string $file
     * @return array
     */
    public function setStub($file)
    {
        $this->_archive->setStub($this->_archive->createDefaultStub($file));
    }

    /**
     * Method to get a default stub for the PHAR archive
     *
     * @param  string $file
     * @return string
     */
    public function getStub()
    {
        return $this->_archive->getStub();
    }

    /**
     * Method to return a listing of the contents of an archived file
     *
     * @param  boolean $full
     * @return array
     */
    public function listFiles($full = false)
    {
        $list = array();

        foreach ($this->_archive as $file) {
            if ($file->isDir()) {
                $objects = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator((string)$file), \RecursiveIteratorIterator::SELF_FIRST);
                foreach ($objects as $fileInfo) {
                    if (($fileInfo->getFilename() != '.') && ($fileInfo->getFilename() != '..')) {
                        $f = ($fileInfo->isDir()) ? ($fileInfo->getPathname() . DIRECTORY_SEPARATOR) : $fileInfo->getPathname();
                        if (!$full) {
                            $list[] = substr($f, (stripos($f, '.phar') + 6));
                        } else {
                            $f = $fileInfo->getPath() . DIRECTORY_SEPARATOR . $fileInfo->getFilename();
                            $list[] = array(
                                          'name'  => substr($f, (stripos($f, '.phar') + 6)),
                                          'mtime' => $fileInfo->getMTime(),
                                          'size'  => $fileInfo->getSize()
                                      );
                        }
                    }
                }
            } else {
                if (!$full) {
                    $list[] = substr($f, (stripos($f, '.phar') + 6));
                } else {
                    $f = $fileInfo->getPath() . DIRECTORY_SEPARATOR . $fileInfo->getFilename();
                    $list[] = array(
                                  'name'  => substr($f, (stripos($f, '.phar') + 6)),
                                  'mtime' => $fileInfo->getMTime(),
                                  'size'  => $fileInfo->getSize()
                              );
                }
            }
        }

        return $list;
    }

}
