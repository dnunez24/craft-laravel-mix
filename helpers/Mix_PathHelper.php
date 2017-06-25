<?php

namespace Craft;

class Mix_PathHelper
{
    /**
     * @var string PLUGIN_NAME
     */
    const PLUGIN_HANDLE = 'mix';

    /**
     * @var ConfigService
     */
    protected $config;

    /**
     * @var IOHelper
     */
    protected $ioHelper;

    /**
     * Initialize the path helper
     *
     * @param Config $config
     * @param IOHelper $ioHelper
     */
    public function __construct(
        $config = null,
        $ioHelper = null
    ) {
        $this->config = $config ?? craft()->config;
        $this->ioHelper = $ioHelper ?? (new IOHelper);
    }
    
    /**
     * Get a path relative to the site public directory
     *
     * @param string $path
     * @param bool $raiseException
     *
     * @return string
     * @throws Exception
     */
    public function getPublicPath($path = '', $raiseException = false)
    {
        $publicDir = $this->config->get('publicDir', self::PLUGIN_HANDLE);
        $publicPath = $this->ioHelper->getRealPath($publicDir.'/'.$path);

        if (!$publicPath && $raiseException) {
            throw new Exception("{$publicPath} does not exist");
        }

        return (string)$publicPath;
    }
    
    /**
     * Prefix character to path if not present
     *
     * @param string $path
     *
     * @return string
     */
    public function prefix(string $path, $char = '/')
    {
        if (!$this->startsWith($path, $char)) {
            $path = "{$char}{$path}";
        }

        return $path;
    }

    /**
     * Check if string starts with the specified character
     *
     * @param string $str
     * @param string $char
     *
     * @return bool
     */
    protected function startsWith(string $str, string $char)
    {
        return strpos($str, $char) === 0;
    }
}
