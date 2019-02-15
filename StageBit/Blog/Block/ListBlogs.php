<?php
/**
 * Created by PhpStorm.
 * User: Shweta
 * Date: 04-02-2019
 * Time: 06:11 PM
 */

namespace StageBit\Blog\Block;

use StageBit\Blog\Model\Allblogs;


class ListBlogs extends \Magento\Framework\View\Element\Template
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
        $page = ($this->getRequest()->getParam('p'))? $this->getRequest()->getParam('p') : 1;
        $limit = ($this->getRequest()->getParam('limit'))? $this->getRequest()->getParam('limit') : $this->helperData->getStorefrontConfig('post_count');

        $collection = $this->allBlogsFactory->create()->getCollection();
        $collection->addFieldToFilter('is_active',1);
        $collection->setOrder('post_id', 'DESC');
        $collection->setPageSize($limit);
        $collection->setCurPage($page);

        return $collection;
    }

    protected function _prepareLayout(){
        parent::_prepareLayout();
        $this->pageConfig->getTitle()->set(__('Latest Blogs'));

        if ($this->getListBlogs()){
            $pager = $this->getLayout()->createBlock('Magento\Theme\Block\Html\Pager', 'stagebit.stagebit_blog.pager')
                ->setAvailableLimit(array(5=>5,10=>10,15=>15,20=>20))
                ->setShowPerPage(true)
                ->setCollection($this->getListBlogs());

            $this->setChild('pager', $pager);

            $this->getListBlogs()->load();
        }
        return $this;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    public function getMediaUrl()
    {
        $mediaUrl = $this->storeManager->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        return $mediaUrl;
    }
}