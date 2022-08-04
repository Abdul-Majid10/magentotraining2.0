<?php
declare(strict_types=1);

namespace Pointeger\CustomApi\Model\Api;

use Magento\Framework\DataObject;
use Pointeger\CustomApi\Api\RequestItemInterface;

/**
 * Class RequestItem
 * @package Pointeger\CustomApi\Model\Api
 */
class RequestItem extends DataObject implements RequestItemInterface
{
    /**
     * @return int|mixed|null
     */
    public function getId()
    {
        return $this->_getData(self::DATA_ID);
    }

    /**
     * @return mixed|string|null
     */
    public function getDescription()
    {
        return $this->_getData(self::DATA_DESCRIPTION);
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId(int $id)
    {
        return $this->setData(self::DATA_ID, $id);
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description)
    {
        return $this->setData(self::DATA_DESCRIPTION, $description);
    }
}
