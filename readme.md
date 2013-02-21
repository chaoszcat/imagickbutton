Imagick Button
--------------

A beautifully PHP rendered button using Imagick extension.

__Why__

In the email world, creating beautiful buttons is always pain in the a**. To overcome this,
email templates usually uses css buttons with image fallback. This is fine if your template
is fixed and not much variations. What if you are a developer that need to generate dynamic
buttons?

__GD/Imagick__

That's why I created this script for those who wanted to create dynamic yet beautiful buttons
in PHP. The only hard requirement here is Imagick extension. GD is too tedious to do such
task. Two lines and you will get a beautifully rendered button.


Requirements
------------

PHP 5.3 and [Imagick extension](http://php.net/manual/en/class.imagick.php) installed. Installation
steps is out of scope here. Google it, should have a lot of resources.


Examples
--------
Orange (Glossy)<br>
![ScreenShot](https://raw.github.com/chaoszcat/imagickbutton/master/demo/orange_glossy.gif)

Orange (Matte)<br>
![ScreenShot](https://raw.github.com/chaoszcat/imagickbutton/master/demo/orange.gif)

Blue<br>
![ScreenShot](https://raw.github.com/chaoszcat/imagickbutton/master/demo/blue.gif)

Green<br>
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