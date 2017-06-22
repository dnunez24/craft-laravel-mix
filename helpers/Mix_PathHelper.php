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
     * Initializes the path resolver
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
     *
     * @return string
     * @throws Exception
     */
    public function getPublicPath($path = '')
    {
        $publicDir = $this->config->get('publicDir', self::PLUGIN_HANDLE);
        $publicPath = $this->ioHelper->getRealPath($publicDir.'/'.$path);

        if (!$publicPath) {
            throw new Exception("{$publicPath} does not exist");
        }

        return $publicPath;
    }
}
