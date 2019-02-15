<?php
/**
 * Created by PhpStorm.
 * User: Shweta
 * Date: 04-02-2019
 * Time: 03:34 PM
 */

namespace StageBit\Blog\Block;


class LinkBlogs extends \Magento\Framework\View\Element\Template
{
    protected $dataHelper;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \StageBit\Blog\Helper\Data $dataHelper
    ){
        parent::__construct($context);
        $this->dataHelper = $dataHelper;
    }

    public function getBlogLink()
    {
        return $this->dataHelper->getStorefrontConfig('display_text');
    }

    public function getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }
}