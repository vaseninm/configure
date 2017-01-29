<?php

namespace vaseninm\configure;


class Configure
{
    /**
     * @var string Base config path
     */
    protected $folder = 'config';

    /**
     * @var array Array of configs: name => baseFile.parentFile.resultFile
     */
    protected $configs = [];

    /**
     * @var string Current config
     */
    protected $config = null;


    protected $data = null;

    protected static $instance = null;

    protected function __construct() {}
    protected function __clone() {}

    /**
     * @return Configure
     */
    public static function me()
    {
        if (self::$instance === null) {
            self::$instance = new Configure();
        }

        return self::$instance;
    }

    /**
     * @param $folder
     * @return $this
     */
    public function setFolder($folder)
    {
        $this->folder = $folder;

        return $this;
    }

    public function addConfig($name, $path)
    {
        $this->configs[$name] = $path;

        return $this;
    }

    public function selectConfig($name)
    {
        if (! array_key_exists($name, $this->configs)) throw new \Exception("Config not [{$name}] found");

        $this->config = $name;

        return $this;
    }

    /**
     * @param string $item
     *
     * @return mixed
     */
    public function get($item)
    {
        if ($this->data === null) {
            $this->loadConfig();
        }

        return array_key_exists($item, $this->data) ? $this->data[$item] : null;
    }

    protected function loadConfig()
    {
        if (! $this->config) throw new \Exception("Config must be selected");

        $configs = explode('.', $this->configs[$this->config]);
        $this->data = [];

        foreach ($configs as $config) {
            $data = require($this->getConfigPath() . "{$config}.php");

            $this->data = array_merge($this->data, $data);
        }
    }

    protected function getConfigPath() {

        return dirname($_SERVER["SCRIPT_FILENAME"]) . '/' . $this->folder . '/';
    }
}