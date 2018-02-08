<?php

namespace BootIq\CmsApiVendor\DataObject;

class Layout
{

    /**
     * @var string
     */
    private $code;

    /**
     * @var array|Block[]
     */
    private $blocks = [];

    /**
     * @var array|string[]
     */
    private $requiredComponents = [];

    /**
     * Layout constructor.
     * @param string $code
     */
    public function __construct(string $code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return array|Block[]
     */
    public function getBlocks()
    {
        return $this->blocks;
    }

    /**
     * @param string $key
     * @return Block|null
     */
    public function getBlock(string $key)
    {
        return (isset($this->blocks[$key]) ? $this->blocks[$key] : null);
    }

    /**
     * @param array|Block[] $blocks
     */
    public function setBlocks($blocks)
    {
        foreach ($blocks as $block) {
            $this->addBlock($block);
        }
    }

    /**
     * @return array|string[]
     */
    public function getRequiredComponents()
    {
        return $this->requiredComponents;
    }

    /**
     * @param array|string[] $requiredComponents
     */
    public function setRequiredComponents($requiredComponents)
    {
        $this->requiredComponents = $requiredComponents;
    }

    /**
     * @param Block $block
     */
    public function addBlock(Block $block)
    {
        $this->blocks[$block->getCode()] = $block;
    }
}
