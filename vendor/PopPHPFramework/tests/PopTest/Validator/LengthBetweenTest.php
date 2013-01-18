<?php
/**
 * Pop PHP Framework Unit Tests (http://www.popphp.org/)
 *
 * @link       https://github.com/nicksagona/PopPHP
 * @category   Pop
 * @package    Pop_Test
 * @author     Nick Sagona, III <nick@popphp.org>
 * @copyright  Copyright (c) 2009-2013 Moc 10 Media, LLC. (http://www.moc10media.com)
 * @license    http://www.popphp.org/license     New BSD License
 */

/**
 * @namespace
 */
namespace PopTest\Validator;

use Pop\Loader\Autoloader,
    Pop\Validator\LengthBetween;

// Require the library's autoloader.
require_once __DIR__ . '/../../../src/Pop/Loader/Autoloader.php';

// Call the autoloader's bootstrap function.
Autoloader::factory()->splAutoloadRegister();

class LengthBetweenTest extends \PHPUnit_Framework_TestCase
{

    public function testEvaluateTrue()
    {
        $v = new LengthBetween('5|10');
        $this->assertTrue($v->evaluate('abcdef'));
        $this->assertFalse($v->evaluate('123'));
    }

    public function testEvaluateFalse()
    {
        $v = new LengthBetween('5|10', null, false);
        $this->assertFalse($v->evaluate('abcdef'));
        $this->assertTrue($v->evaluate('123'));
    }

}

