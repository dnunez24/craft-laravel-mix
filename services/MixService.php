<?php

namespace Craft;

use Mix\PathResolver;

class MixService extends BaseApplicationComponent
{
    /**
     * @var Mix_HotModuleReplacementModel
     */
    protected $hotModuleReplacement;

    /**
     * @var Mix_ManifestModel
     */
    protected $manifest;

    /**
     * @var Mix_PathHelper
     */
    protected $pathHelper;

    /**
     * Initialize the Mix Service
     *
     * @param Mix_HotModuleReplacementModel $hotModuleReplacement
     * @param Mix_ManifestModel $manifest
     * @param Mix_PathHelper $pathHelper
     */
    public function __construct(
        $hotModuleReplacement = null,
        $manifest = null,
        $pathHelper = null
    ) {
        $this->hotModuleReplacement = $hotModuleReplacement ?? (new Mix_HotModuleReplacementModel);
        $this->manifest = $manifest ?? (new Mix_ManifestModel);
        $this->pathHelper = $pathHelper ?? (new Mix_PathHelper);
    }

    /**
     * Get the resolved asset path
     *
     * @param string $path
     * @param string $manifestDirectory
     *
     * @return string
     */
    public function getAssetPath(string $path, $manifestDirectory = '')
    {
        $path = $this->pathHelper->resolvePath($path);
        $manifestDirectory = $this->pathHelper->resolvePath($manifestDirectory);

        $this->setManifestDirectory($manifestDirectory);

        if ($this->isHotModuleReplacementEnabled()) {
            return $this->getHotModuleReplacementAssetPath($path);
        }

        return $this->getManifestAssetPath($path);
    }

    /**
     * Set the manifest directory for model dependencies
     *
     * @param string $manifestDirectory
     *
     * @return void
     */
    protected function setManifestDirectory(string $manifestDirectory)
    {
        $this->manifest->directory = $manifestDirectory;
        $this->hotModuleReplacement->directory = $manifestDirectory;    
    }

    /**
     * Gets assets file path from Mix manifest
     *
     * @param string $source
     *
     * @return string
     */
    protected function getManifestAssetPath(string $source)
    {
        return $this->manifest->getAssetPath($source);
    }

    /**
     * Check if Mix is running in hot module replacement mode
     *
     * @return bool
     */
    protected function isHotModuleReplacementEnabled()
    {
        return $this->hotModulereplacement->isEnabled();
    }

    /**
     * Gets asset path for hot module replacement mode
     *
     * @param string $path
     *
     * @return string
     */
    protected function getHotModuleReplacementAssetPath(string $path)
    {
        return $this->hotModuleReplacement->getAssetPath($path);
    }
}
