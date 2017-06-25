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
     * Initialize the hot module replacement model
     *
     * @param array $attributes
     * @param Mix_PathHelper $pathHelper
     * @param UrlHelper $urlHelper
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
     * Get asset path for hot module replacement mode
     *
     * @param string $path
     *
     * @return string
     */
    public function getAssetPath(string $path)
    {
        if (!$this->urlHelper->isFullUrl($path)) {
            $path = $this->pathHelper->prefix($path);
        }

        return $this->urlHelper->getUrl(self::BASE_URL.$path);
    }

    /**
     * Check if hot module replacement mode is enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        $path = $this->directory.'/'.self::FLAG_FILENAME;
        return (bool)$this->pathHelper->getPublicPath($path);
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
