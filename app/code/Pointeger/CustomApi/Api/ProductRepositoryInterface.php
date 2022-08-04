<?php
declare(strict_types=1);

namespace Pointeger\CustomApi\Api;

interface ProductRepositoryInterface
{
    /**
     * Return a filtered product.
     *
     * @param int $id
     * @return \Pointeger\CustomApi\Api\ResponseItemInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getItem(int $id);

    /**
     * Set descriptions for the products.
     *
     * @param \Pointeger\CustomApi\Api\RequestItemInterface[] $products
     * @return void
     */
    public function setDescription(array $product);
}
