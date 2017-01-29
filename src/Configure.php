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

    /**
     * @param $folder
     * @return $this
     */
    public function setFolder($folder)
    {
        $this->folder = $folder;

        return $this;
    }

    public function addConfig($config)
    {
        $this->configs[] = $config;

        return $this;
    }

    public function setConfig($config)
    {
        if (! in_array($config, $this->configs)) throw new \Exception("Config not [{$config}] found");

        $this->config = $config;
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

        return dirname($_SERVER["SCRIPT_FILENAME"]) . '/' . $this->config;
    }
}