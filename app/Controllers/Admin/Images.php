<?php 
namespace Cms\Controllers\Admin;


use Cms\Controllers\Admin\Admin;
use Cms\Lib\Exceptions\Image as ImageException;
use Cms\Lib\Utility\Image;
use Cms\Models\Upload;
use Speedy\Utility\File;

class Images extends Admin {

	public function resize() {
		$upload = Upload::find($this->params('id'));
		$this->image	= new Image(PUBLIC_UPLOADS_DIR . DS . $upload->filename);
		$cropWidth	= ($this->hasParam('crop.width')) ? 
			intval($this->params('crop.width')) : 0;
		$cropHeight	= ($this->hasParam('crop.height')) ?
			intval($this->params('crop.height')) : 0;
		$scaleWidth	= ($this->hasParam('scale.width')) ?
			intval($this->params('scale.width')) : 0;
		$scaleHeight= ($this->hasParam('scale.height')) ? 
			intval($this->params('scale.height')) : 0;
		$startX	= ($this->hasParam('crop.start_x')) ?
			intval($this->params('crop.start_x')) : 0;
		$startY	= ($this->hasParam('crop.start_y')) ?
			intval($this->params('crop.start_y')) : 0;
		
		if ($cropWidth == 0 && $cropHeight == 0) {
			$this->image->resize($scaleWidth, $scaleHeight);
		} else {
			if ($scaleWidth > 0 && $scaleHeight > 0) {
				$this->image->resize($scaleWidth, $scaleHeight);
			}
			
			$this->image->crop($cropWidth, $cropHeight, $startX, $startY);
		}
		
		$this->respondTo(function($format) {
			try {
				$savePath = $this->image->save();
				$saveData = [
					'filename'	=> str_replace(PUBLIC_UPLOADS_DIR . DS, '', $savePath),
					'type'	=> $this->image->mimeType(),
					'size'	=> filesize($savePath)
				];
				$upload = new Upload($saveData);
				
				if ($upload->save()) {
					$format->html = function() {
						$this->redirectTo($this->admin_uploads_url(), ['success' => 'Image successfully resized.']);
					};
					$format->json = function() use ($upload) {
						$this->render(['json' => [
									'success'	=> true,
									'image'		=> $upload->to_array()
								]]);
					};
				} else {
					$format->html	= function() use ($upload) {
						$this->redirectTo($this->admin_uploads_url(), ['error'	=> $upload->errors]);
					};
					$format->json	= function() use ($upload) {
						$this->render(['json' => [
									'success'	=> false,
									'error'	=> $upload->errors
								]]);
					};
				}
			} catch(ImageException $e) {
				$format->html	= function() use ($e) {
					$this->redirectTo($this->admin_uploads_url(), ['error' => $e->getMessage()]);
				};
				$format->json	= function() use ($e) {
					$this->render(['json' => [
								'success'	=> true,
								'error'	=> $e->getMessage(),
								'trace'	=> $e->getTrace()
							]]);
				};
			}
		});
	}
	
}
?>