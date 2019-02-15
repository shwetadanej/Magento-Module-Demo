<?php
/**
 * Created by PhpStorm.
 * User: Shweta
 * Date: 06-02-2019
 * Time: 05:39 PM
 */

namespace StageBit\Blog\Controller\Adminhtml\AllBlogs;

use Magento\Backend\App\Action\Context;
use StageBit\Blog\Api\AllblogsRepositoryInterface as AllblogsRepository;
use Magento\Framework\Controller\Result\JsonFactory;
use StageBit\Blog\Api\Data\AllblogsInterface;

class InlineEdit extends \Magento\Backend\App\Action
{
    protected $allblogsRepository;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $jsonFactory;

    public function __construct(
        Context $context,
        AllblogsRepository $allblogsRepository,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->allblogsRepository = $allblogsRepository;
        $this->jsonFactory = $jsonFactory;
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
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        $postItems = $this->getRequest()->getParam('items', []);
        if (!($this->getRequest()->getParam('isAjax') && count($postItems))) {
            return $resultJson->setData([
                'messages' => [__('Please correct the data sent.')],
                'error' => true,
            ]);
        }

        foreach (array_keys($postItems) as $blogsId) {
            $blogs = $this->allblogsRepository->getById($blogsId);
            try {
                $blogsData = $postItems[$blogsId];
                $extendedBlogData = $blogs->getData();
                $this->setBlogData($blogs, $extendedBlogData, $blogsData);
                $this->allblogsRepository->save($blogs);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $messages[] = $this->getErrorWithBlogId($blogs, $e->getMessage());
                $error = true;
            } catch (\RuntimeException $e) {
                $messages[] = $this->getErrorWithBlogId($blogs, $e->getMessage());
                $error = true;
            } catch (\Exception $e) {
                $messages[] = $this->getErrorWithBlogId(
                    $blogs,
                    __('Something went wrong while saving the blogs.')
                );
                $error = true;
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    protected function getErrorWithBlogId(AllblogsInterface $blogs, $errorText)
    {
        return '[Blog ID: ' . $blogs->getId() . '] ' . $errorText;
    }

    public function setBlogData(\StageBit\Blog\Model\Allblogs $blogs, array $extendedBlogData, array $blogsData)
    {
        $blogs->setData(array_merge($blogs->getData(), $extendedBlogData, $blogsData));
        return $this;
    }
}
?>