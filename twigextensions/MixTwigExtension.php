<?php

namespace Craft;

use Twig_Extension;
use Twig_SimpleFilter;
use Twig_SimpleFunction;

class MixTwigExtension extends \Twig_Extension
{
    /**
    * Returns the extensions filters
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
    * Returns the extensions functions
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
    * Returns file path for mix asset
    *
    * @param string $path
    * @return string
    */
    public function mix(string $path, $manifestDirectory = '')
    {
        return craft()->mix->getAssetPath($path, $manifestDirectory);
    }
}
