<?php

namespace Craft;

class MixVariable
{
    /**
     * Get the mix asset file path
     *
     * @param string $path
     * @param string $manifestDirectory
     *
     * @return string
     */
    public function getAssetPath(string $path, $manifestDirectory = '')
    {
        return craft()->mix->getAssetPath($path, $manifestDirectory);
    }
}
