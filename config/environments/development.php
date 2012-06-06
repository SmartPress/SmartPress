<?php
use \Speedy\Loader;

App::instance()->configure(function($conf) {

	// Turn on short links
	$conf->set('short_links', true);
	//$conf->set('sprockets'
	date_default_timezone_set('America/Los_Angeles');
	
	Loader::instance()->registerNamespace("{$this->ns()}.lib", LIB_PATH);
	Loader::instance()->registerNamespace('active_record', VENDOR_PATH . DS . "SpeedyPHP" . DS . 'ActiveRecord');
	
	$connections	= $this->config()->dbStrings();
	\Speedy\ActiveRecord\Config::initialize(function($conf) use ($connections) {
		$conf->set_connections($connections);
		$conf->set_default_connection('development');
		
		$conf->set_logging(true);
		$conf->set_logger(\Speedy\ActiveRecord\Logger\Runtime::instance());
	});
	
	$conf->addRenderer('php', 'cms.lib.view.php');
	
});
?>