Imagick Button
--------------

A beautifully PHP rendered button using Imagick extension.


Requirements
------------

PHP 5.3 and Imagick extension installed


Examples
--------
Orange (Glossy)
![ScreenShot](https://raw.github.com/chaoszcat/imagickbutton/master/demo/orange_glossy.gif)

Orange (Matte)
![ScreenShot](https://raw.github.com/chaoszcat/imagickbutton/master/demo/orange.gif)

Blue
![ScreenShot](https://raw.github.com/chaoszcat/imagickbutton/master/demo/blue.gif)

Green
![ScreenShot](https://raw.github.com/chaoszcat/imagickbutton/master/demo/green.gif)

Quick Start
-----------

- Get the `Button.php` class file
- Include the `Button.php` in your script

        include 'Button.php';
        $button = new Button();
        $button->draw();

- That's it!


Configurations
--------------

These are the default properties in the Button class.

	private $properties = array(
		'width' => 140,
		'height' => 40,
		'buttonColor' => '#ff6600', //Button face color
		'fontColor' => '#ffffff',
		'fontSize' => 18,
		'font' => 'arial.ttf',
		'text' => 'Hello World',
		'noText' => false,
		'backgroundColor' => '#ffffff', //Background color. We do not support transparency here
		'corner' => 8,
		'glossy' => true,
		
		/**
		 * Image type. GIF genenerally produces smaller file.
		 * Supported gif/png.
		 */
		'imageType' => 'gif',
		
		/**
		 * Unless specified, these values will be automatically
		 * calculated based on buttonColor
		 */
		'startColor' => null,
		'endColor' => null,
		'strokeColor' => null
	);

To set it, simply call the respective properties as a method. For example, a blue button with a customized text:

    $button->buttonColor('#36a')
           ->text('Click Here')
           ->draw();


A orange (Default color) button with no glossy effect

    $button->glossy(0)
           ->text('Click Here')
           ->draw();

To draw into a file instead, call this

    $button->draw('path/to/the/file');