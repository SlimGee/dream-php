<?php
namespace Dream\Patterns\Observer;

/**
 * Event class for observer
 * @author Given Ncube
 */

class Event
{
  /**
   * @var mixed events that can be triggered
   */
  public static $events = [];


  /**
   * register a new event
   * @param string name of event
   * @param callable callable to call when event is fired it could be a class that
   * implements --invoke method
   * @return void
   */
  public static function register(string $name,callable $callback){
    if (!is_callable($callback)) {
      throw new \Exception("Callback should be callable", 1);
    }
    self::$events[$name][] = $callback;
  }

  /**
   * trigger an event
   * @param string event to fire
   * @param mixed data to pass to event handler
   * @return void
   */
  public static function trigger(string $event,$data){
    if (!array_key_exists($event,self::$events)) {
      throw new \Exception("Event not found", 1);
    }
    foreach (self::$events[$event] as $callback) {
      $callback($data);
    }
  }
}
