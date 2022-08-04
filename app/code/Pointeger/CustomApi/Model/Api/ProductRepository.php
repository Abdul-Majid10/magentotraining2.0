<?php

namespace Pointeger\CustomApi\Model\Api;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\ResourceModel\Product\Action;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Pointeger\CustomApi\Api\ProductRepositoryInterface;
use Pointeger\CustomApi\Api\RequestItemInterfaceFactory;
use Pointeger\CustomApi\Api\ResponseItemInterfaceFactory;

/**
 * Class ProductRepository
 * @package Pointeger\CustomApi\Model\Api
 */
class ProductRepository implements ProductRepositoryInterface
{
    /**
     * @var Action
     */
    private $productAction;
    /**
     * @var CollectionFactory
     */
    private $productCollectionFactory;
    /**
     * @var RequestItemInterfaceFactory
     */
    private $requestItemFactory;
    /**
     * @var ResponseItemInterfaceFactory
     */
    private $responseItemFactory;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @param Action $productAction
     * @param CollectionFactory $productCollectionFactory
     * @param RequestItemInterfaceFactory $requestItemFactory
     * @param ResponseItemInterfaceFactory $responseItemFactory
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Action                       $productAction,
        CollectionFactory            $productCollectionFactory,
        RequestItemInterfaceFactory  $requestItemFactory,
        ResponseItemInterfaceFactory $responseItemFactory,
        StoreManagerInterface        $storeManager
    )
    {
        $this->productAction = $productAction;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->requestItemFactory = $requestItemFactory;
        $this->responseItemFactory = $responseItemFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * @param int $id
     * @return \Pointeger\CustomApi\Api\ResponseItemInterface
     * @throws NoSuchEntityException
     */
    public function getItem(int $id)
    {
        $collection = $this->getProductCollection()
            ->addAttributeToFilter('entity_id', ['eq' => $id]);
        $product = $collection->getFirstItem();
        if (!$product->getId()) {
            throw new NoSuchEntityException(__('Product not found'));
        }
        return $this->getResponseItemFromProduct($product);
    }

    /**
     * @param array $products
     * @return void
     * @throws NoSuchEntityException
     */
    public function setDescription(array $products)
    {
        foreach ($products as $product) {
            $this->setDescriptionForProduct(
                $product->getId(),
                $product->getDescription()
            );
        }
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    private function getProductCollection()
    {

        $collection = $this->productCollectionFactory->create();
        $collection
            ->addAttributeToSelect(
                [
                    'entity_id',
                    ProductInterface::SKU,
                    ProductInterface::NAME,
                    'description'
                ],
                'left'
            );
        return $collection;
    }

    /**
     * @param ProductInterface $product
     * @return \Pointeger\CustomApi\Api\ResponseItemInterface
     */
    private function getResponseItemFromProduct(ProductInterface $product)
    {
        $responseItem = $this->responseItemFactory->create();
        $responseItem->setId($product->getId())
            ->setSku($product->getSku())
            ->setName($product->getName())
            ->setDescription($product->getDescription());
        return $responseItem;
    }

    /**
     * @param int $id
     * @param string $description
     * @return void
     * @throws NoSuchEntityException
     */
    private function setDescriptionForProduct(int $id, string $description)
    {
        $this->productAction->updateAttributes(
            [$id],
            ['description' => $description],
            $this->storeManager->getStore()->getId()
        );
    }
}
