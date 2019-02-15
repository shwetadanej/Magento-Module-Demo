<?php
/**
 * Created by PhpStorm.
 * User: Shweta
 * Date: 07-02-2019
 * Time: 11:52 AM
 */

namespace StageBit\Blog\Block;

use Magento\Framework\View\Element\Template\Context;
use StageBit\Blog\Model\Allblogs;

class ViewBlog extends \Magento\Framework\View\Element\Template
{
    protected $allBlogsFactory;

    protected $storeManager;

    protected $model;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \StageBit\Blog\Model\AllblogsFactory $allBlogsFactory,
        Allblogs $model, \Magento\Store\Model\StoreManagerInterface $storeManager
    ){
        parent::__construct($context);
        $this->model = $model;
        $this->storeManager = $storeManager;
        $this->allBlogsFactory = $allBlogsFactory;
    }

    public function getBlog()
    {
        $id = $this->getRequest()->getParam('url');
        $news = $this->allBlogsFactory->create()->load($id,'url_key');

        return $news;
    }

    protected function _prepareLayout(){

        parent::_prepareLayout();

        $blog = $this->getBlog();
        $this->pageConfig->getTitle()->set($blog->getName());

        return $this;
    }

    public function getMediaUrl()
    {
        $mediaUrl = $this->storeManager->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        return $mediaUrl;
    }
}
?>