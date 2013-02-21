<?php
/**
 * Create beautifully rendered button using Imagick
 * @author Lionel Chan <chaoszcat@gmail.com>. All rights reserved.
 * 
 * Requires Imagick [http://www.php.net/manual/en/class.imagick.php]
 * 
 * Why:
 * In email world, not all email clients support the so called "web2.0"
 * glossy, css-rendered-buttons. So this piece of script is basically making use
 * of the Imagick and some PHP scripting to create a beautiful button without
 * much effort. Yes I understand that most email clients blocks images, but
 * this is a good fallback if the client doesn't support css-gradient button
 * right?
 * 
 * This piece of code is crystallized from various sources on how to make a button
 * using imagick. Tried GD, and it's sucky.
 * 
 * 
 * Usage:
 * $button = new Button();
 * $button->draw();
 * 
 * To customize it:
 * $button = new Button();
 * $button->text('Button Text')
 *        ->fontColor('#ffffff')
 *        ->buttonColor('#219801')
 *        .... (refering to the properties)
 *        ->draw();
 * 
 * To draw it into a file instead
 * $button->draw('path/to/the/file');
 */
class Button {
	
	/**
	 * Default button properties
	 * @var array
	 */
	private $properties = array(
		'width' => 140,
		'height' => 40,
		'buttonColor' => '#ff6600',     //Button face color
		'fontColor' => '#ffffff',
		'fontSize' => 18,
		'font' => 'arial.ttf',          //font use. Drop your font into the font folder
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
	
	private $fontdir = 'font';
	
	public function __construct() {
		$this->here = dirname(__FILE__);
		$this->font($this->font());
	}
	
	/**
	 * For get/set properties
	 * 
	 * @param type $name
	 * @param type $arguments
	 * @return null|Button
	 */
	public function __call($name, $arguments) {
		if (empty($arguments)) {
			if (isset($this->properties[$name])) {
				return $this->properties[$name];
			}else{
				return null;
			}
		}else{
			//attempt to set prop
			$this->properties[$name] = $arguments[0];
			return $this;
		}
	}
	
	/**
	 * Set font path. If this path found, use it. If not, look for it in font
	 * folder instead
	 * @param type $path
	 */
	public function font($path=null) {
		
		if (empty($path)) {
			return $this->properties['font'];
		}
		
		if (is_file($path)) {
			$this->properties['font'] = $path;
		}else{
			$path = $this->here.'/'.$this->fontdir.'/'.$path;
			if (is_file($path)) {
				$this->properties['font'] = $path;
			}else{
				$this->font('arial.ttf');
			}
		}
	}
	
	/**
	 * Do background gradient
	 */
	private function doGradient() {
		$pattern = new Imagick();
		
		if ($this->startColor() && $this->endColor()) {
			$startColor = $this->startColor();
			$endColor = $this->endColor();
		}else{
			$startColor = $this->buttonColor();
			$endColor = $this->darken($startColor);
		}
		
		if ($this->strokeColor()) {
			$strokeColor = $this->strokeColor();
		}else{
			$strokeColor = $this->darken($endColor);
		}
		
		$pattern->newpseudoimage(2, $this->height(), "gradient:{$startColor}-{$endColor}");

		//Gradient as pattern
		$background = new ImagickDraw();
		$background->pushPattern('gradient', 0, 0, 2, $this->height());
		$background->composite(Imagick::COMPOSITE_OVER, 0, 0, 2, $this->height(), $pattern);
		$background->popPattern();

		/* Set the button color.
		Changing this value changes the color of the button */
		$background->setFillPatternURL('#gradient');
		$background->setStrokeColor($strokeColor);
		//Weird here. Need to reduce 1px down for some strange reason.
		$background->roundRectangle(0, 0, $this->width()-1, $this->height()-1, $this->corner(), $this->corner());
		
		$this->final->drawImage($background);
		$background->destroy();
	}
	
	/**
	 * Do text
	 */
	private function doText() {
		
		if ($this->noText()) return;
		
		$text = new ImagickDraw();
		$text->setFont($this->font());
		$text->setFontSize($this->fontSize());
		$text->setFillColor($this->fontColor());
		$text->setGravity(Imagick::GRAVITY_CENTER); /*center*/
		$this->final->annotateImage($text, 0, 0, 0, $this->text());
		$text->destroy();
	}
	
	/**
	 * Do the glossy effect
	 */
	private function doGlossy() {
		
		if (!$this->glossy()) return;
		
		$shine = new ImagickDraw();
		$shine->setFillColor("white");
		$shine->setFillAlpha(0.2);
		$shine->rectangle(1,1,$this->width()-1,$this->height()/2);
		$this->final->drawImage($shine);
		$shine->destroy();
	}
	
	/**
	 * Helper to convert hex string to rgb
	 * @param string $hex
	 * @return array
	 */
	private function hex2rgb($hex) {
		$hex = str_replace("#", "", $hex);

		if(strlen($hex) == 3) {
			$r = hexdec(substr($hex,0,1).substr($hex,0,1));
			$g = hexdec(substr($hex,1,1).substr($hex,1,1));
			$b = hexdec(substr($hex,2,1).substr($hex,2,1));
		} else {
			$r = hexdec(substr($hex,0,2));
			$g = hexdec(substr($hex,2,2));
			$b = hexdec(substr($hex,4,2));
		}
		$rgb = array($r, $g, $b);
		return $rgb; // returns an array with the rgb values
	}
	
	/**
	 * Helper to convert rgb array to hex string
	 * @param array $rgb
	 * @return string
	 */
	private function rgb2hex($rgb) {
		$hex = "#";
		$hex .= str_pad(dechex($rgb[0]), 2, "0", STR_PAD_LEFT);
		$hex .= str_pad(dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
		$hex .= str_pad(dechex($rgb[2]), 2, "0", STR_PAD_LEFT);
		return $hex;
	}
	
	/**
	 * Darken a given color by $steps number
	 * @param type $color
	 * @return hex string
	 */
	private function darken($color, $steps=30) {
		//Convert string color #xxxxxx to rgb first
		if (is_string($color)) $color = $this->hex2rgb($color);
		for ($i = 0 ; $i < 3 ; $i++) {
			$color[$i] = $color[$i] - $steps;
			if ($color[$i] < 0) $color[$i] = 0;
		}
		return $this->rgb2hex($color);
	}
	
	/**
	 * Draw the button on screen, or into a file specified
	 */
	public function draw($file=null) {
		
		//Start the fun!
		$this->final = new Imagick();
		$this->final->newImage($this->width(), $this->height(), $this->backgroundColor(), $this->imageType());
		
		$this->doGradient();
		$this->doText();
		$this->doGlossy();
		
		
		if (!empty($file)) {
			$this->final->writeImage($file);
		}else{
			//echo
			$this->final->trimImage(0);
			$mime = $this->imageType();
			header("Content-Type: image/{$mime}");
			echo $this->final;
		}
		
		//Cleanup
		$this->final->destroy();
		
		return true;
	}
}