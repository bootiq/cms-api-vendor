<?php

namespace BootIq\CmsApiVendor\DataObject;

class Block
{

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string|null
     */
    private $content;

    /**
     * @var array|Attribute[]
     */
    private $attributes = [];

    /**
     * @var array|Setting[]
     */
    private $settings = [];

    /**
     * Block constructor.
     * @param string $code
     * @param string $type
     */
    public function __construct(string $code, string $type)
    {
        $this->code = $code;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return null|string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return array|Attribute[]
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * @return array|Setting[]
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @param null|string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @param array|Attribute[] $attributes
     */
    public function setAttributes($attributes)
    {
        foreach ($attributes as $attribute) {
            $this->addAttribute($attribute);
        }
    }

    /**
     * @param array|Setting[] $settings
     */
    public function setSettings($settings)
    {
        foreach ($settings as $setting) {
            $this->addSetting($setting);
        }
    }

    /**
     * @param Attribute $attribute
     */
    public function addAttribute(Attribute $attribute)
    {
        $this->attributes[$attribute->getKey()] = $attribute;
    }

    /**
     * @param Setting $setting
     */
    public function addSetting(Setting $setting)
    {
        $this->settings[] = $setting;
    }

    /**
     * @param string $key
     * @return Attribute|mixed|null
     */
    public function getAttribute(string $key)
    {
        return (isset($this->attributes[$key]) ? $this->attributes[$key] : null);
    }
}
