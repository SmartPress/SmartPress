<?php
namespace Cms\Models;


defined('MODULE_UPLOAD_DIR') or define("MODULE_UPLOAD_DIR", ROOT . DS . 'tmp' . DS . 'uploads');

class Module extends \Speedy\Model\ActiveRecord\Base {
	
	/*protected $_mixins = array(
		"\\Cms\\Lib\\Concerns\\Uploader" => array(
        	'uploadDir' => MODULE_UPLOAD_DIR,
          	'forceWebroot' => false, //if false, files will be upload to the exact path of uploadDir
          	//'fields' => array('name' => 'file_name', 'type' => 'file_type', 'size' => 'file_size'),
          	'allowedTypes' => array( 'zip' ),
          	'required' => true, //default is false, if true a validation error would occur if a file wsan't uploaded.
          	'maxFileSize' => '500000', //bytes OR false to turn off maxFileSize (default false)
          	'unique' => false, //filenames will overwrite existing files of the same name. (default true)
          	'fileNameFunction' => false //execute the Sha1 function on a filename before saving it (default false)
        )
	);*/
	
}

?>