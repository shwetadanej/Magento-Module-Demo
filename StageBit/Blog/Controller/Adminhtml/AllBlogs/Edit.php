<?php
/**
 * Created by PhpStorm.
 * User: Shweta
 * Date: 06-02-2019
 * Time: 05:35 PM
 */

namespace StageBit\Blog\Controller\Adminhtml\AllBlogs;

use Magento\Backend\App\Action;

class Edit extends \Magento\Backend\App\Action
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Registry $registry
     */
    public function __construct(
        Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Registry $registry
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->_coreRegistry = $registry;
        parent::__construct($context);
    }

    /**
     * Authorization level
     *
     * @see _isAllowed()
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('StageBit_Blog::save');
    }

    /**
     * Init actions
     *
     * @return \Magento\Backend\Model\View\Result\Allblogs
     */
    protected function _initAction()
    {
        // load layout, set active menu and breadcrumbs
        /** @var \Magento\Backend\Model\View\Result\Allblogs $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('StageBit_Blog::stagebit_blog_allblogs')
            ->addBreadcrumb(__('Blogs'), __('Blogs'))
            ->addBreadcrumb(__('Manage All Blogs'), __('Manage All Blogs'));
        return $resultPage;
    }

    /**
     * Edit Allblogs
     *
     * @return \Magento\Backend\Model\View\Result\Allblogs|\Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('post_id');
        $model = $this->_objectManager->create(\StageBit\Blog\Model\Allblogs::class);

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This blogs no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }

        $this->_coreRegistry->register('stagebit_blog_allblogs', $model);

        // 5. Build edit form
        /** @var \Magento\Backend\Model\View\Result\Allblogs $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $id ? __('Edit Blogs') : __('Add Blog'),
            $id ? __('Edit Blogs') : __('Add Blog')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Allblogs'));
        $resultPage->getConfig()->getTitle()
            ->prepend($model->getId() ? $model->getTitle() : __('Add Blog'));

        return $resultPage;
    }
}
?>