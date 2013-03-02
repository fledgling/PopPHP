<?php
/**
 * Pop PHP Framework (http://www.popphp.org/)
 *
 * @link       https://github.com/nicksagona/PopPHP
 * @category   Pop
 * @package    Pop_Auth
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2013 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Auth\Adapter;

/**
 * File auth adapter class
 *
 * @category   Pop
 * @package    Pop_Auth
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2013 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/license     New BSD License
 * @version    1.2.2
 */
class File implements AdapterInterface
{

    /**
     * Field delimiter
     * @var string
     */
    protected $delimiter = null;

    /**
     * Users
     * @var array
     */
    protected $users = array();

    /**
     * User data array
     * @var array
     */
    protected $user = array();

    /**
     * Constructor
     *
     * Instantiate the AuthFile object
     *
     * @param string $filename
     * @param string $delimiter
     * @throws Exception
     * @return \Pop\Auth\Adapter\File
     */
    public function __construct($filename, $delimiter = '|')
    {
        if (!file_exists($filename)) {
            throw new Exception('The access file does not exist.');
        }

        $this->delimiter = $delimiter;
        $this->parse($filename);
    }

    /**
     * Method to authenticate the user
     *
     * @param  string $username
     * @param  string $password
     * @return int
     */
    public function authenticate($username, $password)
    {
        if (!array_key_exists($username, $this->users)) {
            $result = \Pop\Auth\Auth::USER_NOT_FOUND;
        } else if ($this->users[$username]['password'] != $password) {
            $result = \Pop\Auth\Auth::PASSWORD_INCORRECT;
        } else if ((strtolower($this->users[$username]['access']) == 'blocked') ||
            (is_numeric($this->users[$username]['access']) && ($this->users[$username]['access'] == 0))) {
            $result = \Pop\Auth\Auth::USER_IS_BLOCKED;
        } else {
            $this->user = $this->users[$username];
            $result = \Pop\Auth\Auth::USER_IS_VALID;
        }

        return $result;
    }

    /**
     * Method to the user data array
     *
     * @return array
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Method to parse the source file.
     *
     * @param  string $filename
     * @return void
     */
    protected function parse($filename)
    {
        $entries = explode("\n", trim(file_get_contents($filename)));

        foreach ($entries as $entry) {
            $ent = trim($entry);
            $entAry = explode($this->delimiter , $ent);
            if (isset($entAry[0]) && isset($entAry[1])) {
                $this->users[$entAry[0]] = array(
                    'username' => $entAry[0],
                    'password' => $entAry[1],
                    'access'   => (isset($entAry[2]) ? $entAry[2] : null)
                );
            }
        }
    }
}
