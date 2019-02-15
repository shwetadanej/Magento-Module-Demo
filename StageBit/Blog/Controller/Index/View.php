<?php
/**
 * Created by PhpStorm.
 * User: Shweta
 * Date: 04-02-2019
 * Time: 06:08 PM
 */

namespace StageBit\Blog\Controller\Index;

class View extends \Magento\Framework\App\Action\Action
{
    protected $pageFactory;

    public function __construct(
    \Magento\Framework\App\Action\Context $context,
    \Magento\Framework\View\Result\PageFactory $pageFactory)
{
    $this->pageFactory = $pageFactory;
    return parent::__construct($context);
}

    public function execute()
{
    return $this->pageFactory->create();
}
}
?>