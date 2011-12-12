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
 * @package    Pop_Record
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2012 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/LICENSE.TXT     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Record;

/**
 * @category   Pop
 * @package    Pop_Record
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2012 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/LICENSE.TXT     New BSD License
 * @version    0.9
 */
abstract class AbstractRecord
{

    /**
     * Database connection adapter
     * @var Pop\Db\Db
     */
    public $db = null;

    /**
     * Rows of multiple return results from a database query
     * in an ArrayObject format.
     * @var array
     */
    protected $_rows = array();

    /**
     * Column names of the database table
     * @var array
     */
    protected $_columns = array();

    /**
     * Table name of the database table
     * @var string
     */
    protected $_tableName = null;

    /**
     * Primary ID column name of the database table
     * @var string
     */
    protected $_primaryId = 'id';

    /**
     * Property that determines whether or not the primary ID is auto-increment or not
     * @var boolean
     */
    protected $_auto = true;

    /**
     * Flag on which quote identifier to use.
     * @var int
     */
    protected $_idQuote = null;

    /**
     * Original query finder, if primary ID is not set.
     * @var array
     */
    protected $_finder = array();

    /**
     * Language object
     * @var Pop_Locale
     */
    protected $_lang = null;

    /**
     * Get the result rows.
     *
     * @return array
     */
    public function getResult()
    {
        return $this->_rows;
    }

    /**
     * Get the order by values
     *
     * @param  string $order
     * @return array
     */
    protected function _getOrder($order)
    {
        $by = null;
        $ord = null;

        if (stripos($order, 'ASC') !== false) {
            $by = trim(str_replace('ASC', '', $order));
            $ord = 'ASC';
        } else if (stripos($order, 'DESC') !== false) {
            $by = trim(str_replace('DESC', '', $order));
            $ord = 'DESC';
        } else if (stripos($order, 'RAND()') !== false) {
            $by = trim(str_replace('RAND()', '', $order));
            $ord = 'RAND()';
        } else {
            $by = $order;
            $ord = null;
        }

        return array('by' => $by, 'order' => $order);
    }

}
