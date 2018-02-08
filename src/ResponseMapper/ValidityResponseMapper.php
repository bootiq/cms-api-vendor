<?php

namespace BootIq\CmsApiVendor\ResponseMapper;

use BootIq\CmsApiVendor\DataObject\Validity;

class ValidityResponseMapper
{

    /**
     * @param array $responseData
     * @return Validity
     */
    public function mapFromPage(array $responseData): Validity
    {
        if (!isset($responseData['validity'])) {
            return new Validity();
        }
        return $this->map($responseData['validity']);
    }

    /**
     * @param array $responseData
     * @return Validity
     */
    public function map(array $responseData): Validity
    {
        $validity = new Validity();
        if (isset($responseData['issueDate'])) {
            $validity->setIssueDate(new \DateTime($responseData['issueDate']));
        }
        if (isset($responseData['expireDate'])) {
            $validity->setExpireDate(new \DateTime($responseData['expireDate']));
        }
        return $validity;
    }
}
