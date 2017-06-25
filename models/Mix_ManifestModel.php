<?php

namespace Craft;

class Mix_ManifestModel extends BaseModel
{
    /**
     * @var string
     */
    const MANIFEST_FILENAME = 'mix-manifest.json';

    /**
     * @var IOHelper
     */
    protected $ioHelper;

    /**
     * @var JsonHelper
     */
    protected $jsonHelper;

    /**
     * @var Mix_PathHelper
     */
    protected $pathHelper;

    /**
     * @var UrlHelper
     */
    protected $urlHelper;

    /**
     * Initialize the manifest model
     *
     * @param array $attributes
     * @param IOHelper $ioHelper
     * @param JsonHelper $jsonHelper
     * @param Mix_PathHelper $pathHelper
     * @param UrlHelper $urlHelper
     */
    public function __construct(
        $attributes = null,
        $ioHelper = null,
        $jsonHelper = null,
        $pathHelper = null,
        $urlHelper = null
    ) {
        $this->ioHelper = $ioHelper ?? (new IOHelper);
        $this->jsonHelper = $jsonHelper ?? (new JsonHelper);
        $this->pathHelper = $pathHelper ?? (new Mix_PathHelper);
        $this->urlHelper = $urlHelper ?? (new UrlHelper);
        parent::__construct($attributes);
    }

    /**
     * Get mapped asset path from manifest file
     *
     * @param string $sourcePath
     *
     * @return string
     * @throws Exception
     */
    public function getAssetPath(string $sourcePath)
    {
        $manifest = $this->readFile();
        $path = $this->pathHelper->prefix($sourcePath);

        if (!array_key_exists($path, $manifest)) {
            throw new Exception("Unable to locate path '{$path}' in Mix manifest.");
        }

        return $this->urlHelper->getUrl($manifest[$path]);
    }

    /**
     * Get manifest file
     *
     * @return string
     */
    protected function getFile()
    {
        $path = $this->directory.'/'.self::MANIFEST_FILENAME;
        $file = $this->pathHelper->getPublicPath($path, true);
        return $file;
    }

    /**
     * Read & decode contents of manifest file
     *
     * @return mixed
     */
    protected function readFile()
    {
        $file = $this->getFile();
        $raw = $this->ioHelper->getFileContents($file);
        return $this->jsonHelper->decode($raw);
    }

    /**
     * Define model attributes
     *
     * @return array
     */
    protected function defineAttributes()
    {
        return [
            'directory' => [
                'type' => AttributeType::String,
            ],
        ];
    }
}
