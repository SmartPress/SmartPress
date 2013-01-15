<?php
namespace SmartPress\Lib\Utility;
/**
 * 
 * Utility class for manipulating images
 * @author zachary
 *
 */

use SmartPress\Lib\Exceptions\Image as ImageException;

class Image {
	public $currentErrors	= array();
	public $newImageName	= null;
	public $currentImage	= null;
	public $lastImage	= array();
	
	private $_imageInfo	= array();
	private $_imagePath	= null;
	private $_errors	= array(
		'Image does not exist in path',
		'Unable to resize image'
	);
	
	private $_imageWatermark = null;
	
	private $_imageRsc 	= null;
	private $_outputImg	= null;
	
	
	
	
	/**
	 * @see Image
	 * @param string $imagePath path to image
	 */
	public function __construct($imagePath = null) {
		if ($imagePath !== null) $this->setImagePath($imagePath);
	}
	
	/**
	 * 
	 * Method for resizing an image by a scale factor
	 * @param string $image Path to an image or null if already set by setImage method
	 * @param float $scale scale factor
	 */
	public function scale($scale) {
		$newWidth	= ceil($this->width() * $scale);
		$newHeight	= ceil($this->height() * $scale);
		
		return $this->resize($newWidth, $newHeight);
	}
	
	
	/**
	 * Method to resize an image
	 * @param string $image Path to image or null
	 * @param integer $newImageWidth new width
	 * @param integer $newImageHeight new height
	 * @return string of image path
	 */
	public function resize($newImageWidth, $newImageHeight) {
		$imageType 	= image_type_to_mime_type($this->type());
		//$newImageWidth 	= ceil($this->width() * $scale); //ceil($width * $scale);
		//$newImageHeight	= ceil($this->height() * $scale); //ceil($height * $scale);
		//echo $newImageWidth . ' - ' . $newImageHeight . ' TEST<br/>';
		$newImage 	= imagecreatetruecolor($newImageWidth,$newImageHeight);
		$source 	= $this->resource();	

		if (!imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$this->width(),$this->height())) 
			throw new ImageException('Unable to copy a sample');
	
		//$this->newImageName	= substr_replace($image, "-$newImageWidth-$newImageHeight", strrpos($image, '.'), 0);
		$this->setResource($newImage);
		
		return $this;
	}
	
	
	/**
	 * Method to crop a portion of an image
	 * @param mixed $image String to image or null
	 * @param float $cropWidth crop width
	 * @param float $cropHeight crop height
	 * @param int $start_x Start X position of crop
	 * @param int $start_y Start Y position of crop
	 * @param mixed $imageWidth Original width or null
	 * @param mixed $imageHeight Original height or null
	 * @return string of image path
	 */
	public function crop($cropWidth, $cropHeight, $start_x, $start_y, $imageWidth = null, $imageHeight = null) {
		if ($imageHeight == null) $imageHeight	= $cropHeight;
		if ($imageWidth == null) $imageWidth	= $cropWidth;
		
		$imageType 	= image_type_to_mime_type($this->type());
		$newImage	= imagecreatetruecolor($cropWidth,$cropHeight);
		
		$this->lastImage = array(
			'crop'	=> array(
				'width'	=> $cropWidth,
				'height'=>	$cropHeight
			),
			'image'	=> array(
				'width'	=> $imageWidth,
				'height'=> $imageHeight
			)
		);
		
		$source = $this->resource();	
  		
  		/**
  		 *  resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
  		 */
		if (!imagecopyresampled($newImage,$source,0,0,$start_x,$start_y,$cropWidth,$cropHeight,$imageWidth,$imageHeight))
			throw new ImageException('Unable to copy a sample');
		//$this->newImageName	= substr_replace($image, "-$cropWidth-$cropHeight-crop", strrpos($image, '.'), 0);
		$this->setResource($newImage);
		
		return $this;
	}

	/**
	 * Setter for resource
	 * @param resource $imageRsc
	 * @return object $this
	 */
	public function setResource($imageRsc) {
		$this->_imageRsc	= $imageRsc;
		return $this;
	}

	/**
	 * Resource getter
	 * @return resource _imageRsc
	 */
	public function &resource() {
		if ($this->_imageRsc === null) {
			$imagePath	= $this->imagePath();
			$imageType 	= image_type_to_mime_type($this->type());
			
			switch($imageType) {
				case "image/gif":
					$this->_imageRsc	= imagecreatefromgif($imagePath); 
					break;
		    	case "image/pjpeg":
				case "image/jpeg":
				case "image/jpg":
					$this->_imageRsc	= imagecreatefromjpeg($imagePath); 
					break;
		    	case "image/png":
				case "image/x-png":
					$this->_imageRsc	= imagecreatefrompng($imagePath); 
					break;
	  		}
		}
		
		return $this->_imageRsc;
	}

	/**
	 * Save the image
	 * @param string $path
	 * @throws ImageException
	 * @return string
	 */
	public function save($path = null) {
		$imageType 	= image_type_to_mime_type($this->type());
		$savePath	= (isset($path)) ? $path : $this->generateName();
		
		switch ($imageType) {
			case "image/gif":
	  			if (!imagegif($this->resource(),	$savePath)) 
	  				throw new ImageException('Unable to save gif', 2);
				break;
      		case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
	  			if (!imagejpeg($this->resource(),	$savePath, 90)) 
	  				throw new ImageException('Unable to save jpg', 3);
	  			
				break;
			case "image/png":
			case "image/x-png":
				if (!imagepng($this->resource(),	$savePath)) 
					throw new ImageException('Unable to save png', 4);
				break;
			default:
				throw new ImageException('Unable to figure out type match for save - ' . $imageType, 1);
				break;
		}
		
		chmod($savePath, 0604);
		return $savePath;
	}
	
	/**
	 * Checks if errors exists
	 * @return bool
	 * @deprecated in favor of exceptions
	 */
	public function hasError() {
		return (count($this->currentErrors)) ? true : false; 
	}
	
	/**
	 * Get errors as string
	 */
	public function errorString() {
		if (!$this->hasError()) return '';
		$errors	= '';
		
		foreach ($this->currentErrors as $error) {
			$errors .= $error . "\n";
		}
		
		return $errors;
	}
	
	/**
	 * Add error to instance
	 */
	private function _addError($msg) {
		$this->currentErrors[]	= $msg;
		return $this;
	}
	
	/**
	 * Getter for image path
	 * @return string
	 */
	public function imagePath() {
		return $this->_imagePath;
	}
	
	/**
	 * Set the image of the class
	 * @param string $image path to image
	 */
	public function setImagePath($image = null) {
		if ($image == null && $this->_imagePath != null) return $this->_imagePath;
		elseif ($image == null && $this->_imagePath == null) {
			throw new ImageException('No image path given');
			//return false;
		}
		
		if (!file_exists($image)) {
			throw new ImageException("No image found for $image");
			//return false;
		}
		
		$this->_imagePath	= $image;
		list(
			$this->_imageInfo['width'], 
			$this->_imageInfo['height'], 
			$this->_imageInfo['type'])	= getimagesize($this->_imagePath);
		
		return $this->_imagePath;
	}
	
	/**
	 * 
	 
	private function _resource() {
		return $this->_imageRsc;
	}*/
	
	/**
	 * Generates a new name for image
	 * @return string
	 */
	public function generateName() {
		$file	= substr_replace(
				$this->_imagePath,
				"-edited",
				strrpos($this->_imagePath, '.'), 0
		);
			
		$i = 1;
		while (file_exists($file)) {
			$file = substr_replace(
					$file,
					"-$i",
					strrpos($file, '.'),
					0
			);
		}
		
		return $file;
	}
	
	/**
	 * Getter for newImageName
	 * @return string
	 * @deprecated
	 */
	public function newImageName() {
		if (!$this->newImageName) {
			$this->newImageName	= $this->generateName();
		}
		return $this->newImageName; 
	}
	
	/**
	 * Watermark getter
	 * @return resource
	 */
	public function &watermark() {
		return $this->_imageWatermark;
	}
	
	/**
	 * Setter for watermark
	 * @param resource $wm
	 * @return object $this
	 */
	public function setWatermark($wm) {
		$this->_imageWatermark	= $wm;
		return $this;
	}
	
	/**
	 * Add given watermark to current image
	 * @param resource $watermark
	 * @throws SmartPress\Lib\Exceptions\Image
	 * @return object $this
	 */
	public function addWatermarkToImage($watermark = null) {
		if ($watermark) {
			$this->setWatermark($watermark);
		}
		$image	=& $this->resource();
		$wm		=& $this->watermark();
		
		imagealphablending($wm, true);
		if (!imagesettile($image, $wm)) 
			throw new ImageException('Unable to set watermark', 10);
		
		if (!imagefilledrectangle($image, 0, 0, $this->width(), $this->height(), IMG_COLOR_TILED)) 
			throw new ImageException('Unable to fill image with tile', 11);
		
		return $this;
	}
	
	/**
	 * @deprecated
	 */
	public function &outputImage() {
		if (!$this->_outputImg) {
			$this->_outputImg	= $this->resource();
		}
		
		return $this->_outputImg;
	}

	/**
	 * @deprecated
	 * @param unknown_type $image
	 * @return \SmartPress\Lib\Utility\Image
	 */
	public function setOutputImage($image) {
		$this->_outputImg	= $image;
		return $this;
	}
	
	/**
	 * Get image height
	 * @return int
	 */
	public function height() {
		return $this->_imageInfo['height'];
	}
	
	/**
	 * Get image width
	 * @return int
	 */
	public function width() {
		return $this->_imageInfo['width'];
	}
	
	/**
	 * Get image type
	 * @return string
	 */
	public function type() {
		return $this->_imageInfo['type'];
	}

	/**
	 * Get image mime type
	 * @return string
	 */
	public function mimeType() {
		return image_type_to_mime_type($this->type());
	}

	/**
	 * Errors getter
	 * @return array
	 * @deprecated
	 */
	public function errors() {
		return $this->currentErrors;
	}
	
	/**
	 * Gettter for file size
	 * @return int
	 */
	public function fileSize() {
		return filesize($this->_imagePath);
	}
	
	/**
	 * Release current resources
	 * @return SmartPress\Lib\Utility\Image $this
	 */
	public function release() {
		$this->currentErrors	= array();
		$this->newImageName	= null;
	
		$this->_imageInfo	= array();
		$this->_imagePath	= null;
		
		if ($this->_outputImg) {
			imagedestroy($this->_outputImg);
			$this->_outputImg 	= null;
		}
		
		if ($this->_imageWatermark) {
			imagedestroy($this->_imageWatermark);
			$this->_imageWatermark	= null;
		}
		
		if ($this->_imageRsc) {
			imagedestroy($this->_imageRsc);
			$this->_imageRsc	= null;
		}
		
		return $this;
	}
}

?>
