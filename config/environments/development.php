<?php
use Speedy\Loader;
use ActiveRecord\Config as ARConfig;

App::instance()->configure(function($conf) {

	// Turn on short links
	$conf->set('short_links', true);
	$conf->set('logger', '\\Speedy\\Logger\\Console');
	$conf->set('Security.salt', 'salt'); // Change this to your liking
	$conf->set('Session.manager', '\\Speedy\\Session\\File');
	$conf->set('Config.manager', '\\Speedy\\Cache\\File');
	
	//$conf->set('sprockets'
	date_default_timezone_set('America/Los_Angeles');
	
	Loader::instance()->registerNamespace("{$this->ns()}.lib", LIB_PATH);
	
	
	$connections	= $this->config()->dbStrings();
	ARConfig::initialize(function($conf) use ($connections) {
		$conf->set_connections($connections);
		$conf->set_default_connection('development');
		
		$conf->set_logging(true);
		$conf->set_logger(\ActiveRecord\Logger\Runtime::instance());
		
		//$conf->set_cache('\\Speedy\\Cache', 'table');
	});
	
	$conf->addRenderer('php', 'cms.lib.view.php');
	
});
?>