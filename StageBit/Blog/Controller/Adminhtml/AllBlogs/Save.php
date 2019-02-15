<?php
/**
 * Created by PhpStorm.
 * User: Shweta
 * Date: 06-02-2019
 * Time: 05:18 PM
 */

namespace StageBit\Blog\Controller\Adminhtml\AllBlogs;

use Magento\Backend\App\Action;
use StageBit\Blog\Model\Allblogs;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use StageBit\Blog\Model\Allblogs\ImageUploader;

class Save extends \Magento\Backend\App\Action
{
    protected $dataPersistor;
    private $allblogsFactory;
    private $allblogsRepository;
    protected $imageUploader;

    /**
     * @param Action\Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param \StageBit\Blog\Model\AllblogsFactory $allblogsFactory
     * @param \StageBit\Blog\Api\AllblogsRepositoryInterface $allblogsRepository
     */
    public function __construct( Action\Context $context, DataPersistorInterface $dataPersistor, ImageUploader $imageUploader,
        \StageBit\Blog\Model\AllblogsFactory $allblogsFactory = null,
        \StageBit\Blog\Api\AllblogsRepositoryInterface $allblogsRepository = null
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->imageUploader = $imageUploader;
        $this->allblogsFactory = $allblogsFactory
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(\StageBit\Blog\Model\AllblogsFactory::class);
        $this->allblogsRepository = $allblogsRepository
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(\StageBit\Blog\Api\AllblogsRepositoryInterface::class);
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
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($data) {
            if (isset($data['is_active']) && $data['is_active'] === 'true') {
                $data['is_active'] = Allblogs::STATUS_ENABLED;
            }
            if (empty($data['post_id'])) {
                $data['post_id'] = null;
            }

            $imageName = '';
            if (!empty($data['featured_image'])) {
                $imageName = $data['featured_image'][0]['name'];
                $data['featured_image'] = $imageName;
            }

            $url_key = "";
            if(!empty($data['url_key'])){
                $url_key = $this->clean($data['url_key']);
            }else{
                $url_key = $this->clean($data['name']);
            }

            $urls = Allblogs::check_url_key_exist($url_key);
            if(!empty($urls)){
                $data['url_key'] = $url_key."-1";
            }else{
                $data['url_key'] = $url_key;
            }


            /** @var \StageBit\Blog\Model\Allblogs $model */
            $model = $this->allblogsFactory->create();
            $id = $this->getRequest()->getParam('post_id');
            if ($id) {
                try {
                    $model = $this->allblogsRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This blog no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }
            $model->setData($data);

            $this->_eventManager->dispatch(
                'stagebit_blog_allblogs_prepare_save',
                ['allblogs' => $model, 'request' => $this->getRequest()]
            );

            try {
                $this->allblogsRepository->save($model);
                if ($imageName) {
                    $this->imageUploader->moveFileFromTmp($imageName);
                }
                $this->messageManager->addSuccessMessage(__('You saved the blog.'));
                $this->dataPersistor->clear('stagebit_blog_allblogs');
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['post_id' => $model->getId(), '_current' => true]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addExceptionMessage($e->getPrevious() ?:$e);
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the news.'));
            }

            $this->dataPersistor->set('stagebit_blog_allblogs', $data);
            return $resultRedirect->setPath('*/*/edit', ['post_id' => $this->getRequest()->getParam('post_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }


    function clean($string)
    {
        $string = strtolower(str_replace(' ', '-', trim($string))); // Replaces all spaces with hyphens.
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }
}
?>