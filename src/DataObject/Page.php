<?php

namespace BootIq\CmsApiVendor\DataObject;

class Page
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var Layout
     */
    private $layout;

    /**
     * @var string
     */
    private $author;

    /**
     * @var \DateTime
     */
    private $created;

    /**
     * @var \DateTime
     */
    private $updated;

    /**
     * @var Validity|null
     */
    private $validity = null;

    /**
     * @var array|Metadata[]
     */
    private $metadata = [];

    /**
     * Page constructor.
     * @param int $id
     * @param Layout $layout
     * @param string $author
     * @param \DateTime $created
     * @param \DateTime $updated
     */
    public function __construct(int $id, Layout $layout, string $author, \DateTime $created, \DateTime $updated)
    {
        $this->id = $id;
        $this->layout = $layout;
        $this->author = $author;
        $this->created = $created;
        $this->updated = $updated;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Layout
     */
    public function getLayout(): Layout
    {
        return $this->layout;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * @return \DateTime
     */
    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated(): \DateTime
    {
        return $this->updated;
    }

    /**
     * @return Validity|null
     */
    public function getValidity()
    {
        return $this->validity;
    }

    /**
     * @return array|Metadata[]
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    /**
     * @param Validity|null $validity
     */
    public function setValidity($validity)
    {
        $this->validity = $validity;
    }

    /**
     * @param array|Metadata[] $metadata
     */
    public function setMetadata(array $metadata)
    {
        foreach ($metadata as $item) {
            $this->addMetadata($item);
        }
    }

    /**
     * @param Metadata $metadata
     */
    public function addMetadata(Metadata $metadata)
    {
        $this->metadata[$metadata->getKey()] = $metadata;
    }

    /**
     * @param string $key
     * @return Metadata|null
     */
    public function getMetadataItem(string $key)
    {
        return (isset($this->metadata[$key]) ? $this->metadata[$key] : null);
    }
}
