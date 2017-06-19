<?php

namespace Craft;

class MixVariable
{
    /**
     * Returns the mix asset file path
     *
     * @param string $path Path to the file relative to public directory
     * @return string
     */
    public function assetPath(string $path, $manifestDirectory = '')
    {
        return craft()->mix->getAssetPath($path, $manifestDirectory);
    }
}
