<?php 
namespace SmartPress\Helpers;


use SmartPress\Lib\Exceptions\Image as ImageException;
use SmartPress\Lib\View\Helpers\Base;
use SmartPress\Lib\Utility\Image;
use Speedy\View;
use Speedy\Utility\File;

class Resource extends Base {

	const CacheDirName = 'cache';

	protected $_basePath = PUBLIC_DIR;

	/**
	 * @deprecated
	 */
	protected $_resourcesDir = 'resources';



	/*public function resource($path) {
		return '/' . $t; 
	}*/

	public function image($path, $size = null) {
		if ($size) {
			return $this->imageResized($path, $size);	
		}

		return $path;
	}

	/**
	 * Get a resized copy of image
	 * @param string $image
	 * @param string $size
	 */
	protected function imageResized($image, $size) {
		$originalPath = $image;
		if (is_string($size)) {
			$size = $this->dimensionsFromString($size);	
		} 

		// Find sized name
		$sizedName = substr_replace($image, 
				"{$size['width']}x{$size['height']}", 
				strrpos($image, '.'), 
				0);
		$sizedPath = $this->cacheName($sizedName);

		// return sized image if it exists
		if (file_exists($this->fullPath($sizedPath))) {
			return $this->relativePath($sizedPath);
		}

		// Create sized image if not exists
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

			$cachedName = $this->cacheName($sizedName);
			$cachePath = $this->fullPath($cachedName);
			if (strpos($cachePath, DS) !== false) {
				$info = pathinfo($cachePath);
				if (!file_exists($info['dirname'])) {
					File::mkdir_p($info['dirname']);
				}
			}
			
			$imageObj->save($cachePath);

			$return = $this->relativePath($cachedName);
		} catch(ImageException $e) {
			$return = $image;
		}
		
		return $return;
	}

	/**
	 * Gets full path to the resource
	 * @param string $relPath
	 * @return string null on inability to find resource
	 */
	protected function fullPath($relPath) {
		return $this->_basePath . DS . $relPath;
	}

	/**
	 * Gets relative FS path
	 * @param string $resource
	 * @return string null on inability fo find resource
	 */
	protected function path($resource) {
		/*$foundFile = null;
		foreach ($this->_paths as $path) {
			$cachePath = $path . DS . $resource;

			\Speedy\Logger::debug("Attempting: " . $this->fullPath($path . DS . $cachePath));
			if (!file_exists($this->fullPath($path . DS . $cachePath))) {
				continue;
			}

			$foundFile = $cachePath;
			break;
		}*/

		return DS . $resource;
	}

	/**
	 * Get the cached name of the resource
	 * @param string $resource
	 * @return string
	 */
	protected function cacheName($resource) {
		return self::CacheDirName . DS . $resource;
	}

	/**
	 * Return path of resource relative to domain
	 * @param string $resource
	 * @return string
	 */
	protected function relativePath($resoure) {
		return '/' . $resoure;
	}

	/**
	 * Convenience method to retreive full path to cached resource
	 * @param string $resource
	 * @return string
	 */
	protected function fullCachePath($resource) {
		return $this->fullPath(
				$this->path(
					$this->cacheName($resource)
				)
			);
	}

	protected function dimensionsFromString($size) {
		$aDimensions = explode('x', $size);
		return ['width' => $aDimensions[0], 'height' => $aDimensions[1]];
	}

}

