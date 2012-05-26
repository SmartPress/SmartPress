<?php
use \Speedy\Loader;

App::instance()->configure(function($conf) {

	// Turn on short links
	$conf->set('short_links', true);
	//$conf->set('sprockets'
	date_default_timezone_set('America/Los_Angeles');
	
	Loader::instance()->registerNamespace("{$this->ns()}.lib", LIB_PATH);
	Loader::instance()->registerNamespace('active_record', VENDOR_PATH . DS . "SpeedyPHP" . DS . 'ActiveRecord');
	import('active_record.utils');
	import('active_record.exceptions');
	import('active_record.logger.runtime');
	
	$connections	= $this->config()->dbStrings();
	\ActiveRecord\Config::initialize(function($conf) use ($connections) {
		$conf->set_connections($connections);
		$conf->set_default_connection('development');
		
		$conf->set_logging(true);
		$conf->set_logger(\ActiveRecord\Logger\Runtime::instance());
	});
	
	$conf->addRenderer('php', 'cms.lib.view.php');
	
});
?>