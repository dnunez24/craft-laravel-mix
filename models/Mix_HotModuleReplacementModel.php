<?php

namespace Craft;

class Mix_HotModuleReplacementModel extends BaseModel
{
    /**
     * @var string Hot module replacement mode base URL
     */
    const BASE_URL = '//localhost:8080';

    /**
     * @var string Hot module replacement flag filename
     */
    const FLAG_FILENAME = 'hot';

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
        $attributes = null,
        $pathHelper = null,
        $urlHelper = null
    ) {
        $this->pathHelper = $pathHelper ?? (new Mix_PathHelper);
        $this->urlHelper = $urlHelper ?? (new UrlHelper);
        parent::__construct($attributes);
    }

    /**
     *
     * @param string $path
     *
     * @return string
     */
    public function getAssetPath(string $path)
    {
        if (!$this->urlHelper->isFullUrl($path)) {
            $path = "/{$path}";
        }

        return $this->urlHelper->getUrl(self::BASE_URL.$path);
    }

    /**
     *
     * @return bool
     */
    public function isEnabled()
    {
        $path = $this->directory.'/'.self::FLAG_FILENAME;
        return (bool)$this->pathHelper->publicPath($path);
    }

    protected function defineAttributes()
    {
        return [
            'directory' => [
                'type' => AttributeType::String,
            ],
        ];
    }
}
