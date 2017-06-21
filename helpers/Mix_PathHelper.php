<?php

namespace Craft;

class Mix_PathHelper
{
    /**
     * @var string PLUGIN_NAME Name of the plugin
     */
    const PLUGIN_NAME = 'mix';

    protected $config;

    protected $ioHelper;

    /**
     * Initializes the path resolver
     *
     * @param IOHelper $ioHelper
     * @return MixPathResolver Path resolver instance
     */
    public function __construct(
        $config = null,
        $ioHelper = null
    ) {
        $this->config = $config ?? craft()->config;
        $this->ioHelper = $ioHelper ?? (new IOHelper);
    }

    /**
     * Resolves path with proper directory separators
     *
     * @param string $path Original path
     * @return string Resolved path
     */
    public function resolvePath(string $path)
    {
        if (!$this->startsWith($path, '/')) {
            $path = "/{$path}";
        }

        return $this->ioHelper->getRealPath($path);
    }

    /**
     * Get a path relative to the site public directory
     *
     * @param string $path
     *
     * @return string
     * @throws Exception
     */
    public function getPublicPath($path = '')
    {
        $publicDir = $this->config->get('publicDir', self::PLUGIN_NAME);
        $publicPath = $this->ioHelper->getRealPath($publicDir.'/'.$path);

        if (!$publicPath) {
            throw new Exception("{$publicPath} does not exist");
        }

        return $publicPath;
    }

    /**
     * Tests if string starts with the specified character
     *
     * @param string $str String to test
     * @param string $char Character to look for
     * @return bool Whether the string starts with the specified character
     */
    protected function startsWith(string $str, string $char)
    {
        return strpos($str, $char) === 0;
    }
}
