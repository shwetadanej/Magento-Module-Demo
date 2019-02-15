<?php
/**
 * Created by PhpStorm.
 * User: Shweta
 * Date: 06-02-2019
 * Time: 04:29 PM
 */

namespace StageBit\Blog\Model;

use StageBit\Blog\Api\Data;
use StageBit\Blog\Api\AllblogsRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use StageBit\Blog\Model\ResourceModel\Allblogs as ResourceAllblogs;
use StageBit\Blog\Model\ResourceModel\Allblogs\CollectionFactory as AllblogsCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class AllblogsRepository implements AllblogsRepositoryInterface
{
    protected $resource;

    protected $allblogsFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataAllblogsFactory;

    private $storeManager;

    public function __construct(
        ResourceAllblogs $resource,
        AllblogsFactory $allblogsFactory,
        Data\AllblogsInterfaceFactory $dataAllblogsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->allblogsFactory = $allblogsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataAllblogsFactory = $dataAllblogsFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    public function save(\StageBit\Blog\Api\Data\AllblogsInterface $blogs)
    {
        if ($blogs->getStoreId() === null) {
            $storeId = $this->storeManager->getStore()->getId();
            $blogs->setStoreId($storeId);
        }
        try {
            $this->resource->save($blogs);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(
                __('Could not save the blogs: %1', $exception->getMessage()),
                $exception
            );
        }
        return $blogs;
    }

    public function getById($blogsId)
    {
        $blogs = $this->allblogsFactory->create();
        $blogs->load($blogsId);
        if (!$blogs->getId()) {
            throw new NoSuchEntityException(__('Blog with id "%1" does not exist.', $blogsId));
        }
        return $blogs;
    }

    public function delete(\StageBit\Blog\Api\Data\AllblogsInterface $blogs)
    {
        try {
            $this->resource->delete($blogs);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the blogs: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    public function deleteById($blogsId)
    {
        return $this->delete($this->getById($blogsId));
    }
}