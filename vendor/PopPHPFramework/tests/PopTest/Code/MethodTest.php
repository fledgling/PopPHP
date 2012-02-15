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
    Pop\Code\MethodGenerator;

// Require the library's autoloader.
require_once __DIR__ . '/../../../src/Pop/Loader/Autoloader.php';

// Call the autoloader's bootstrap function.
Autoloader::factory()->splAutoloadRegister();

class MethodTest extends \PHPUnit_Framework_TestCase
{

    public function testFactory()
    {
        $this->assertInstanceOf('Pop\\Code\\MethodGenerator', MethodGenerator::factory('testMethod'));
    }

    public function testStatic()
    {
        $m = MethodGenerator::factory('testMethod');
        $m->setStatic(true);
        $this->assertTrue($m->isStatic());
    }

    public function testAbstract()
    {
        $m = MethodGenerator::factory('testMethod');
        $m->setAbstract(true);
        $this->assertTrue($m->isAbstract());
    }

    public function testFinal()
    {
        $m = MethodGenerator::factory('testMethod');
        $m->setFinal(true);
        $this->assertTrue($m->isFinal());
    }

    public function testVisibility()
    {
        $m = MethodGenerator::factory('testMethod');
        $m->setVisibility('protected');
        $this->assertEquals('protected', $m->getVisibility());
    }

    public function testGetName()
    {
        $m = MethodGenerator::factory('testMethod');
        $this->assertEquals('testMethod', $m->getName());
    }

    public function testSetAndGetIsInterface()
    {
        $m = MethodGenerator::factory('testMethod');
        $m->setInterface(true);
        $this->assertTrue($m->isInterface());
    }

    public function testSetAndGetDesc()
    {
        $m = MethodGenerator::factory('testMethod');
        $m->setDesc('This is the desc.');
        $m->setDesc('This is the new desc.');
        $this->assertEquals('This is the new desc.', $m->getDesc());
        $this->assertInstanceOf('Pop\\Code\\DocblockGenerator', $m->getDocblock());
    }

    public function testSetAndGetIndent()
    {
        $m = MethodGenerator::factory('testMethod');
        $m->setIndent('    ');
        $this->assertEquals('    ', $m->getIndent());
    }

    public function testSetAndGetName()
    {
        $m = MethodGenerator::factory('testMethod');
        $m->setName('newTestMethod');
        $this->assertEquals('newTestMethod', $m->getName());
    }

    public function testAddAndGetArguments()
    {
        $m = MethodGenerator::factory('testMethod');
        $m->addArgument('testVar', 123, 'int');
        $m->addParameter('oneMoreTestVar', 789, 'int');
        $m->addArguments(array(
        	array('name' => 'anotherTestVar', 'value' => 456, 'type' => 'int')
        ));
        $m->addParameters(array(
        	array('name' => 'yetAnotherTestVar', 'value' => 987, 'type' => 'int')
        ));
        $this->assertTrue(is_array($m->getArguments()));
        $this->assertTrue(is_array($m->getParameters()));
        $arg = $m->getArgument('testVar');
        $par = $m->getParameter('oneMoreTestVar');
        $this->assertEquals(123, $arg['value']);
        $this->assertEquals(789, $par['value']);
    }

    public function testSetAndGetBody()
    {
        $m = MethodGenerator::factory('testMethod');
        $m->setBody('some body code', true);
        $m->appendToBody('some more body code');
        $m->appendToBody('even more body code', false);
        $this->assertContains('body code', $m->getBody());
    }

    public function testRender()
    {
        $m = MethodGenerator::factory('testMethod');
        $m->setBody('some body code', true);
        $m->appendToBody('some more body code');
        $m->appendToBody('even more body code', false);
        $m->addArgument('testVar', 123, 'int');
        $m->addParameter('oneMoreTestVar', 789, 'int');

        $codeStr = (string)$m;
        $code = $m->render(true);

        ob_start();
        $m->render();
        $output = ob_get_clean();
        $this->assertContains('function testMethod($testVar = 123, $oneMoreTestVar = 789)', $output);
        $this->assertContains('function testMethod($testVar = 123, $oneMoreTestVar = 789)', $code);
        $this->assertContains('function testMethod($testVar = 123, $oneMoreTestVar = 789)', $codeStr);
    }

}

