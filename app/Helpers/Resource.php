<?php 
namespace SmartPress\Helpers;


use SmartPress\Lib\Exceptions\Image as ImageException;
use SmartPress\Lib\View\Helpers\Base;
use SmartPress\Lib\Utility\Image;
use Speedy\View;
use Speedy\Utility\File;

class Resource extends Base {

	protected $_basePath = PUBLIC_DIR;

	protected $_resourcesDir = 'resources';


	public function resource($path) {
		$cachePath = $this->path($path);
		return $cachePath ? $cachePath : $path; 
	}

	public function image($path, $size = null) {
		if ($size) {
			return $this->imageResized($path, $size);	
		}

		$path = $this->resource($path);
		return $path;
	}

	protected function imageResized($image, $size) {
		$originalPath = $image;
		if (is_string($size)) {
			$size = $this->dimensionsFromString($size);	
		} 

		$sizedName = substr_replace($image, 
				"{$size['width']}x{$size['height']}", 
				strrpos($image, '.'), 
				0);
		$sizedPath = $this->path($sizedName);

		// return image if it exists
		if ($sizedPath !== null) {
			return $sizedPath;
		}

		// Create image if not exists
		$resourcePath = $this->fullPath($image);
		if (!file_exists($resourcePath)) {
			return '';
		}

		try {		
			$imageObj = new Image($resourcePath);
			if ($size['width'] > $imageObj->width() || $size['height'] > $imageObj->width()) {
				return $this->relativePath($image);
			}

			$imageObj->crop_resize(
				$size['width'], 
				$size['height'], 
				Image::GRAVITY_CENTER);

			$cachePath = $this->fullCachePath($sizedName);
			if (strpos($cachePath, DS) !== false) {
				$info = pathinfo($cachePath);
				if (!file_exists($info['dirname'])) {
					File::mkdir_p($info['dirname']);
				}
			}
			
			$imageObj->save($cachePath);

			$return = $this->path($sizedName);
		} catch(ImageException $e) {
			$return = $image;
		}
		
		return $return;
	}

	protected function fullPath($resource) {
		return $this->_basePath . DS . $this->_resourcesDir . DS . $resource;
	}

	protected function relativePath($resoure) {
		return '/' . $this->_resourcesDir . '/' . $resoure;
	}

	protected function fullCachePath($resource) {
		return $this->_basePath . DS . $this->_resourcesDir . DS .
			'cache' . DS . $resource;
	}

	protected function dimensionsFromString($size) {
		$aDimensions = explode('x', $size);
		return ['width' => $aDimensions[0], 'height' => $aDimensions[1]];
	}

	protected function path($resource) {
		$cachePath = $this->_resourcesDir . DS . 'cache' . DS . $resource;
		$fullCachePath = $this->_basePath . DS . 
						$cachePath;
		return file_exists($fullCachePath) ?
					'/' . $cachePath : null;
	}

}

