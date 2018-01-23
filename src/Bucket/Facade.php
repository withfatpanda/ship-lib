<?php
namespace FatPanda\WordPress\Ship\Bucket;

class Facade {

  private static $instance;

  static function __callStatic($name, $args)
  {
    return call_user_func_array([ 
      self::$instance ? self::$instance : ( self::$instance = new Bucket ), 
      $name 
    ], $args);
  }

}