<?php
namespace FatPanda\WordPress\Ship;

class Blade {

  static function load()
  {
    require_once(__DIR__.'/blade-engine-plugin.php');
  }

}