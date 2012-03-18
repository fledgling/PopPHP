Pop PHP Framework
=================

Documentation : Code
--------------------

The Code component is a code generation component that not only allows you to generate standardized, well-structured PHP code on the fly, but also allows you to modify and extend existing code by leveraging PHP's Reflection extension.

<pre>
use Pop\Code\ClassGenerator,
    Pop\Code\DocblockGenerator,
    Pop\Code\Generator,
    Pop\Code\MethodGenerator,
    Pop\Code\NamespaceGenerator,
    Pop\Code\PropertyGenerator;

// Create the code generator object
$code = new Generator('MyClass.php', Generator::CREATE_CLASS);
$code->setDocblock(new DocblockGenerator('This is my test class file'))
     ->getDocblock()->setTag('category', 'Pop')
                    ->setTag('package', 'Pop_Code')
                    ->setTag('author', 'Joe Author');

// Create namespace object
$ns = new NamespaceGenerator('Some\\Other');
$ns->setUse('Some\\Other\\Thing')
   ->setUse('Some\\Other\\Blah', 'B')
   ->setUse('Some\\Other\\Another');

// Create property object
$prop = new PropertyGenerator('_testProp', 'string', 'test', 'protected');
$prop->setDesc('This is a test property');

// Create a method object
$method = new MethodGenerator('__construct');
$method->setDesc('This is a test method')
       ->setBody("// Let's get some stuff to happen here." . PHP_EOL . "\$blah = 'Sounds like a good idea';")
       ->appendToBody("echo \$blah;", false)
       ->addArgument('test', "null", "Pop\\Filter\\String")
       ->addArgument('other', "array()", 'array');

// Add code pieces to the code file
$code->setNamespace($ns);
$code->code()->setDocblock(new DocblockGenerator('This is my test class'))
             ->getDocblock()->setTag('category', 'Pop')
                            ->setTag('package', 'Pop_Code')
                            ->setTag('author', 'Joe Author');

$code->code()->addProperty($prop);
$code->code()->addMethod($method);

// Render and output the code
$code->output();
</pre>

(c) 2009-2012 [Moc 10 Media, LLC.](http://www.moc10media.com) All Rights Reserved.