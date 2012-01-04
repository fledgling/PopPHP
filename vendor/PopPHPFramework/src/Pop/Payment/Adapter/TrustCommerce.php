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
 * @package    Pop_Payment
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2012 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/LICENSE.TXT     New BSD License
 */

/**
 * @namespace
 */
namespace Pop\Payment\Adapter;

use Pop\Curl\Curl,
    Pop\Locale\Locale;

/**
 * @category   Pop
 * @package    Pop_Payment
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2012 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/LICENSE.TXT     New BSD License
 * @version    0.9
 */
class TrustCommerce extends AbstractAdapter
{

    /**
     * Customer ID
     * @var string
     */
    protected $_custId = null;

    /**
     * Password
     * @var string
     */
    protected $_password = null;

    /**
     * URL
     * @var string
     */
    protected $_url = 'https://vault.trustcommerce.com/trans/';

    /**
     * Transaction data
     * @var array
     */
    protected $_transaction = array(
        'x_login'                           => null,
        'x_tran_key'                        => null,
        'x_allow_partial_Auth'              => null,
        'x_version'                         => '3.1',
        'x_type'                            => 'AUTH_CAPTURE',
        'x_method'                          => 'CC'
    );

}
