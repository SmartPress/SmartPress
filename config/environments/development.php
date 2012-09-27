<?php
use \Speedy\Loader;

App::instance()->configure(function($conf) {

	// Turn on short links
	$conf->set('short_links', true);
	$conf->set('logger', '\\Speedy\\Logger\\Console');
	//$conf->set('sprockets'
	date_default_timezone_set('America/Los_Angeles');
	
	Loader::instance()->registerNamespace("{$this->ns()}.lib", LIB_PATH);
	
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