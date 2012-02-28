<?php
/**
 * Pop PHP Framework Unit Tests
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
 */

namespace PopTest\Code;

use Pop\Loader\Autoloader,
    Pop\Code\DocblockGenerator;

// Require the library's autoloader.
require_once __DIR__ . '/../../../src/Pop/Loader/Autoloader.php';

// Call the autoloader's bootstrap function.
Autoloader::factory()->splAutoloadRegister();

class DocblockTest extends \PHPUnit_Framework_TestCase
{

    public function testFactory()
    {
        $this->assertInstanceOf('Pop\\Code\\DocblockGenerator', DocblockGenerator::factory());
    }

    public function testDocblockParse()
    {
        $this->setExpectedException('Pop\\Code\\Exception');
        $d = DocblockGenerator::parse('Bad doc block');
        $docBlock = "/*\n * @param \$var\n * @return array\n */";
        $d = DocblockGenerator::parse($docBlock);
        $this->assertEquals('array', $d->getReturn());
    }

    public function testDocblock()
    {
        $d = DocblockGenerator::factory('This is the description');
        $d->setTag('category', 'Category')
          ->setTag('package', 'Package_Name')
          ->setTag('author', 'John Doe')
          ->setTag('throws', 'Exception')
          ->setParam('array', '$ary')
          ->setParam('boolean', '$blah')
          ->setReturn('mixed');

        $doc = (string)$d;

        ob_start();
        $d->render();
        $output = ob_get_clean();
        $this->assertContains('* @package  Package_Name', $output);

        $this->assertContains('* This is the description', $doc);
        $this->assertContains('* @category Category', $doc);
        $this->assertContains('* @package  Package_Name', $doc);
        $this->assertContains('* @author   John Doe', $doc);
        $this->assertContains('* @param    array   $ary', $doc);
        $this->assertContains('* @param    boolean $blah', $doc);
        $this->assertContains('* @throws   Exception', $doc);
        $this->assertContains('* @return   mixed', $doc);
    }

    public function testSetAndGetDesc()
    {
        $d = DocblockGenerator::factory('This is the description');
        $d->setDesc('This is the new description');
        $this->assertEquals('This is the new description', $d->getDesc());
    }

    public function testSetAndGetIndent()
    {
        $d = DocblockGenerator::factory('This is the description');
        $d->setIndent('    ');
        $this->assertEquals('    ', $d->getIndent());
    }

    public function testSetAndGetTags()
    {
        $d = DocblockGenerator::factory('This is the description');
        $d->setTags(array('Company' => 'Test Company'));
        $this->assertEquals('Test Company', $d->getTag('Company'));
    }

    public function testSetAndGetParams()
    {
        $d = DocblockGenerator::factory('This is the description');
        $d->setParams(array('type' => 'string', 'var' => 'testString', 'desc' => 'Test desc'));
        $p = $d->getParam(0);
        $this->assertTrue(is_array($p));
        $this->assertEquals('string', $p['type']);
        $this->assertEquals('testString', $p['var']);
        $this->assertEquals('Test desc', $p['desc']);
    }

    public function testSetAndGetReturn()
    {
        $d = DocblockGenerator::factory('This is the description');
        $d->setReturn('void');
        $r = $d->getReturn();
        $this->assertEquals('void', $r['type']);
    }

}
