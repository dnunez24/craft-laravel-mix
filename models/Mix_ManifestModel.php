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
     *
     */
    public function __construct(
        $ioHelper = null,
        $jsonHelper = null,
        $pathHelper = null,
        $urlHelper = null,
        $attributes = null
    ) {
        $this->ioHelper = $ioHelper ?? (new IOHelper);
        $this->jsonHelper = $jsonHelper ?? (new JsonHelper);
        $this->pathHelper = $pathHelper ?? (new Mix_PathHelper);
        $this->urlHelper = $urlHelper ?? (new UrlHelper);
        parent::__construct($attributes);
    }

    /**
     *
     */
    public function getAssetPath(string $sourcePath)
    {
        $manifest = $this->readFile();

        if (!array_key_exists($sourcePath, $manifest)) {
            throw new Exception("Unable to locate path '{$sourcePath}' in Mix manifest.");
        }

        return $this->urlHelper->getUrl($manifest[$sourcePath]);
    }

    /**
     *
     */
    protected function getFile()
    {
        $path = $this->directory.'/'.self::MANIFEST_FILENAME;
        $file = $this->pathHelper->getPublicPath($path);
        return $file;
    }

    /**
     *
     */
    protected function readFile()
    {
        $file = $this->getFile();
        $raw = $this->ioHelper->getFileContents($file);
        return $this->jsonHelper->decode($raw);
    }

    /**
     *
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
