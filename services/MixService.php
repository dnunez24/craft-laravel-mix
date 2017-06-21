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
     * @param string $path Source asset path
     * @param string $manifestDirectory Directory of the Mix manifest file
     *
     * @return string Resolved asset path
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
     *
     */
    protected function setManifestDirectory(string $manifestDirectory)
    {
        $this->hotModuleReplacement->setDirectory($manifestDirectory);
        $this->manifest->setDirectory($manifestDirectory);
    }

    /**
     * Gets assets file path from Mix manifest
     *
     * @param string $source Source path to asset file
     *
     * @return string Resolved path to asset file
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
     * @param string $path Original path to the asset
     *
     * @return string URL to the asset in hot module replacement mode
     */
    protected function getHotModuleReplacementAssetPath(string $path)
    {
        return $this->hotModuleReplacement->getAssetPath($path);
    }
}
