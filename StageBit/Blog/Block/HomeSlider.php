<?php
/**
 * Created by PhpStorm.
 * User: Shweta
 * Date: 08-02-2019
 * Time: 10:50 AM
 */

namespace StageBit\Blog\Block;

use StageBit\Blog\Model\Allblogs;

class HomeSlider extends \Magento\Framework\View\Element\Template
{
    protected $allBlogsFactory;
    protected $storeManager;
    protected $helperData;
    protected $model;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \StageBit\Blog\Model\AllblogsFactory $allBlogsFactory,
        \StageBit\Blog\Helper\Data $helperData,
        Allblogs $model, \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->model = $model;
        $this->storeManager = $storeManager;
        $this->helperData = $helperData;
        $this->allBlogsFactory = $allBlogsFactory;
        parent::__construct($context);
    }

    public function getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }


    public function getListBlogs()
    {
        $collection = $this->allBlogsFactory->create()->getCollection();
        $collection->addFieldToFilter('is_active',1);
        $collection->setPageSize($this->helperData->getStorefrontConfig('slider_post_count'));
        $collection->setOrder('post_id', 'DESC');

        return $collection;
    }
    public function getMediaUrl()
    {
        $mediaUrl = $this->storeManager->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        return $mediaUrl;
    }
}
?>