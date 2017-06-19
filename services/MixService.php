<?php

namespace Craft;

class MixService extends BaseApplicationComponent
{
    /**
     * @var string PLUGIN_NAME Name of the plugin
     */
    const PLUGIN_NAME = 'mix';

    /**
    * @var string $manifestDirectory Directory of the Mix manifest file
    */
    protected $manifestDirectory;
    
    /**
     * @var object $config Craft configuration object
     */
    protected $config;
    
    /**
     * @var IOHelper $ioHelper Helper class for I/O operations
     */
    protected $ioHelper;
    
    /**
     * @var JsonHelper $jsonHelper Helper class for JSON operations
     */
    protected $jsonHelper;
    
    /**
     * @var UrlHelper $urlHelper Helper class for URL operations
     */
    protected $urlHelper;
    
    /**
     * Initialize the Mix Service
     *
     * @param ConfigService $config
     * @param IOHelper $ioHelper
     * @param JsonHelper $jsonHelper
     * @param UrlHelper $urlHelper
     */
    public function __construct(
        $config = null,
        $ioHelper = null,
        $jsonHelper = null,
        $urlHelper = null
    ) {
        $this->config = $config ?? craft()->config;
        $this->ioHelper = $ioHelper ?? (new IOHelper);
        $this->jsonHelper = $jsonHelper ?? (new JsonHelper);
        $this->urlHelper = $urlHelper ?? (new UrlHelper);
    }
    
    /**
     * Find the files version
     *
     * @param $path
     * @return string
     *
     * @throws Exception
     */
    public function getAssetPath($path, $manifestDirectory = '')
    {
        $this->setManifestDirectory($manifestDirectory);
        
        if ($this->isHotModuleReplacementMode()) {
            return $this->getHotModuleReplacementPath($path);
        }
        
        return $this->getManifestPath($path);
    }
    
    /**
     * 
     */
    protected function setManifestDirectory($directory)
    {
        $this->manifestDirectory = $this->resolvePath($directory);
        return $this->manifestDirectory;
    }
    
    /**
     * 
     */
    protected function getManifestDirectory()
    {
        return $this->manifestDirectory;
    }
    
    /**
     * Gets assets file path from Mix manifest
     *
     * @param string $path Relative path to asset file
     * @return string Resolved path to asset file
     * @throws \Craft\Exception
     */
    protected function getManifestPath($path = '')
    {
        $manifestFile = $this->getManifestFile();
        $contents = $this->ioHelper->getFileContents($manifestFile);
        $manifest = $this->jsonHelper->decode($contents);
    
        if (! array_key_exists($path, $manifest)) {
            throw new Exception(
                "Unable to locate Mix file: {$path}. Please check your ".
                "webpack.mix.js output paths and try again."
            );
        }
        
        // return $this->getManifestDirectory().$manifest[$path];
        return $this->urlHelper->getUrl($manifest[$path]);
    }
    
    /**
     * Returns path to the Mix manifest file
     *
     * @return string Path to manifest file
     */
    protected function getManifestFile()
    {
        $manifestFile = $this->getPublicPath($this->getManifestDirectory().'/mix-manifest.json');
        
        if (! $this->ioHelper->fileExists($manifestFile)) {
            throw new Exception('The Mix manifest does not exist.');
        }
        
        return $manifestFile;
    }
    
    /**
     * 
     */
    protected function getPublicPath($path = '')
    {
        $publicPath = $this->config->get('publicPath', self::PLUGIN_NAME);
        return $publicPath.$this->resolvePath($path);
    }

    /**
     * 
     */
    protected function isHotModuleReplacementMode()
    {
        $file = $this->getPublicPath($this->getManifestDirectory().'/hot');
        
        if ($this->ioHelper->fileExists($file)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * 
     */
    protected function getHotModuleReplacementPath(string $path)
    {
        return $this->urlHelper->getUrl("//localhost:8080{$this->resolvePath($path)}");
    }
    
    /**
     * 
     */
    protected function resolvePath($path)
    {
        if (! strpos($path, '/')) {
            $path = "/{$path}";
        }
        
        return $path;
    }
}
