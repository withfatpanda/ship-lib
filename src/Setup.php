<?php
namespace FatPanda\WordPress\Ship;

use Illuminate\Support\Str;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;
use League\CLImate\CLImate;

class Setup {

  private static $defaultMeta = [
    'Theme Name' => 'Ship',
    'Theme URI' => 'https://github.com/collegeman/ship',
    'Author' => 'Fat Panda, LLC',
    'Author URI' => 'https://www.withfatpanda.com',
    'Description' => 'A WordPress Starter Theme',
    'Version' => '1.0.3',
    'License' => 'GPL-2.0',
    'License URI' => 'http://www.gnu.org/licenses/gpl-2.0.html',
    'Text Domain' => 'understrap',
    'Tags' => 'one-column, custom-menu, featured-images, theme-options, translation-ready',
  ];

  private static $instance;

  private static $basePath;

  private static $files;

  private $cli;

  static function postCreateProjectCmd($event)
  {
    $args = $event->getArguments();

    $useDefaults = !empty($args[0]) && $args[0] === 'defaults';

    $vendorPath = $event->getComposer()->getConfig()->get('vendor-dir');

    self::setBasePath(realpath($vendorPath.DIRECTORY_SEPARATOR.'..'));

    return self::getInstance()->setupLocalProject($useDefaults);
  }

  private function __construct() {
    $this->cli = new CLImate;
  }

  static function getInstance()
  {
    return self::$instance ? self::$instance : ( self::$instance = new self() );
  }

  static function getFiles()
  {
    return self::$files ? self::$files : ( self::$files = new Filesystem(new Local(self::getBasePath())));
  }

  function setupLocalProject($useDefaults = false)
  {
    $meta = array_merge(self::$defaultMeta, []);

    $finalMeta = [];

    $styleStub = $this->readFile('vendor/withfatpanda/ship-lib/resources/stubs/style.stub');

    foreach($meta as $field => $default) {
      $finalMeta[] = "{$field}: " . ($value = $useDefaults ? $default : $this->prompt($field, $default));
      $meta[$field] = $value;
    }

    $style = str_replace('{{meta}}', implode("\n", $finalMeta), $styleStub);

    $this->writeFile('style.css', $style);

    $composer = (array) json_decode($this->readFile('vendor/withfatpanda/ship-lib/resources/stubs/composer.stub'));

    $composerFields = [
      'name' => 'Package Name',
      'type' => 'Project Type',
    ];

    $composer['version'] = $meta['Version'];

    if (!$useDefaults) {
      $composer['scripts'] = (object) [];

      foreach($composerFields as $field => $label) {
        $composer[$field] = $this->prompt($label, $composer[$field]);
      }
    }

    $this->writeFile('composer.json', str_replace('\/', '/', json_encode($composer, JSON_PRETTY_PRINT)));

    $package = (array) json_decode($this->readFile('vendor/withfatpanda/ship-lib/resources/stubs/package.stub'));

    $package['version'] = $meta['Version'];

    if (!$useDefaults) {
      $package['name'] = Str::slug($meta['Theme Name']);
      $package['bugs'] = (object) [];
      $package['homepage'] = $meta['Theme URI'];
      $package['description'] = $meta['Description'];
      $package['author'] = $meta['Author'];
      $package['license'] = $meta['License'];
    }

    $this->writeFile('package.json', str_replace('\/', '/', json_encode($package, JSON_PRETTY_PRINT)));

    $engine = $this->choose("Which template engine do you want to use?", [ 'php', 'blade' ], 'php');

    $baseline = $this->choose("Do you want the simple or the complete baseline?", [ 'complete', 'simple' ], 'complete');

    $this->publishTemplates($engine, $baseline);
  }

  static function getBasePath($filePath = '')
  {
    $basePath = self::$basePath ? self::$basePath : ( self::$basePath = realpath(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..') );

    return $filePath ? $basePath.DIRECTORY_SEPARATOR.ltrim($filePath, DIRECTORY_SEPARATOR) : $basePath;
  }

  static function setBasePath($basePath)
  {
    self::$basePath = $basePath;
  }

  function publishTemplates($engine, $baseline)
  {
    // delete placeholder index.php
    $files = self::getFiles();
    if ($files->has('index.php')) {
      $files->delete('index.php');
    }

    return $this->copyDirectory("vendor/withfatpanda/ship-lib/resources/templates/{$engine}/{$baseline}", '/');
  }

  function copyDirectory($from, $to)
  {
    $files = self::getFiles();
    $copyList = $files->listContents($from, true);
    foreach($copyList as $entry) {
      if ('file' === $entry['type']) {
        $dest = preg_replace('#/+#', '/', implode('/', [ $to, Str::replaceFirst($from, '', $entry['dirname']), $entry['basename'] ]));
        if ($files->has($dest)) {
          $files->delete($dest);
        }
        $files->copy($entry['path'], $dest);
      }
    }
  }

  function readFile($path)
  {
    return self::getFiles()->read($path);
  }

  function writeFile($path, $contents)
  {
    $files = self::getFiles();
    if ($files->has($path)) {
      $files->delete($path);
    }
    return $files->write($path, $contents);
  }

  function choose($question, $options = [], $default = null)
  {
    $input = $this->cli->radio($question, $options);

    return $input->prompt();
  }

  function prompt($question, $default = null)
  {
    $prompt = $question;

    if (!is_null($default)) {
      $prompt .= ' [' . $default . ']:';
    }

    $input = $this->cli->input($question);

    if ($default) {
      $input->defaultTo($default);
    }

    return $input->prompt();
  }

}
