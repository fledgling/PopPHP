Pop PHP Framework
=================

Documentation : Service
-----------------------

Home

         
    .  -  
 ,         
  .       
   ,       
.

    use Pop\Service\Locator;

    // Load the services config via the constructor
    $locator = new Locator(array(
        'config' => array(
            'call'   => 'Pop\Config',
            'params' => array(array('test' => 123), true)
        ),
        'rgb' => array(
            'call'   => 'Pop\Color\Space\Rgb',
            'params' => function() { return array(255, 0, 0); }
        ),
        'color' => function($locator) {
            return new \Pop\Color\Color($locator->get('rgb'));
        }
    ));

    // Get a service
    $config = $locator->get('config');  // Returns a config object
    $rgb    = $locator->get('rgb');     // Returns an RGB colorspace object
    $color  = $locator->get('color');   // Returns a color object that is loaded with the RGB object

\(c) 2009-2013 [Moc 10 Media, LLC.](http://www.moc10media.com) All
Rights Reserved.
