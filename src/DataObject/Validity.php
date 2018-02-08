<?php

namespace BootIq\CmsApiVendor\DataObject;

class Validity
{

    /**
     * @var \DateTime|null
     */
    private $issueDate;

    /**
     * @var \DateTime|null
     */
    private $expireDate;

    /**
     * @return \DateTime|null
     */
    public function getIssueDate()
    {
        return $this->issueDate;
    }

    /**
     * @param \DateTime|null $issueDate
     */
    public function setIssueDate($issueDate)
    {
        $this->issueDate = $issueDate;
    }

    /**
     * @return \DateTime|null
     */
    public function getExpireDate()
    {
        return $this->expireDate;
    }

    /**
     * @param \DateTime|null $expireDate
     */
    public function setExpireDate($expireDate)
    {
        $this->expireDate = $expireDate;
    }
}
