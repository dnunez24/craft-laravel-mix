<?php

namespace Craft;

use Twig_Extension;
use Twig_SimpleFilter;
use Twig_SimpleFunction;

class MixTwigExtension extends \Twig_Extension
{
    /**
    * Get the extension filters
    *
    * @return array
    */
    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('mix', [$this, 'mix']),
        ];
    }

    /**
    * Get the extension functions
    *
    * @return string
    */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('mix', [$this, 'mix']),
        ];
    }

    /**
    * Get file path for mix asset
    *
    * @param string $path
    * @param string $manifestDirectory
    *
    * @return string
    */
    public function mix(string $path, $manifestDirectory = '')
    {
        return craft()->mix->getAssetPath($path, $manifestDirectory);
    }
}
