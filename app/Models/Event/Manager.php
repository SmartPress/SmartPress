<?php
namespace SmartPress\Models\Event;


use \Speedy\Cache;
use \Speedy\Singleton;
use \SmartPress\Lib\Module\Site as SiteModules;

class Manager extends Singleton {
	
	private $_listeners = array();


	public function __construct() {
		$this->loadEvents();
	}

	/**
	 * Dispatchs events
	 * @param string $event
	 * @param mixed $params
	 */
	public static function dispatch($event, &$params = null) {
		$self	= self::instance();
		return $self->_dispatch($event, $params);
	}

	/**
	 * Static alias for _addListener
	 * @param string $event
	 * @param hash $callback keys expected: modcode(namespace), class, method
	 */
	public static function addListener($event, $callback) {
		$self 	= self::instance();
		return $self->_addListener($event, $callback);
	}
	
	/**
	 * Add an event listnener to the stack
	 * @param string $event
	 * @param hash $callback keys expected: modcode(namespace), class, method
	 */
	public function _addListener($event, $callback) {
		if (!is_array($callback) || !is_string($event)) return;

		$this->_listeners[$event][]	= $callback;
		return $this;
	}

	public function loadEvents() {
		$events	= $this->events();
		if (empty($events)) {
			return;
		}

		foreach($events as $event) {
			$this->addListener($event['event_name'], $event['callback']);
		}
	}

	public function events() {
		$events	= Cache::read('events');
		if (empty($events)) {
			$events = [];
			/*$siteModules	= SiteModules::all();
			if (!count($siteModules)) {
				return array();
			}
				
			foreach ($siteModules as $module) {
				if (empty($module['events'])) {
					continue;
				}

				$config		= Loader::getModuleConfig($module['file_path']);
				if (!isset($config->events->event)) {
					continue;
				}

				foreach ($config->events->event as $suspectEvent) {
					if (is_array($suspectEvent)) {
						foreach ($suspectEvent as $event) {
							$event->callback->modcode	= (string) $config->code;
							$events[]	= Set::reverse($event);
						}
					} elseif (is_object($suspectEvent)) {
						$suspectEvent->callback->modcode	= (string) $config->code;
						$events[]	= Set::reverse($suspectEvent);
					}
				}
			}
			Cache::write('events', $events);
			*/
		}

		return $events;
	}

	public function _dispatch($event, &$params = null) {
		if (empty($this->_listeners[$event])) return;

		foreach ($this->_listeners[$event] as $event) {
			if (empty($event['class']) || empty($event['method'])) {
				continue;
			}
			/*	
			if (empty($event['modcode']) && empty($event['import'])) {
				continue;
			}
			*/
				
			$class	= $event['class'];
			$method	= $event['method'];
			/*$import	= (isset($event['import'])) ? $event['import'] : "{$event['modcode']}.models." . strtolower($class);
				
			if (!Loader::import($import)) {
				continue;
			}
				
			if (!is_subclass_of($class, 'Events_Abstract')) {
				continue;
			}*/
				
			$class::{$method}($params);
		}
	}
}

?>
