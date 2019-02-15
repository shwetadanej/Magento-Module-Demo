<?php
/**
 * Created by PhpStorm.
 * User: Shweta
 * Date: 06-02-2019
 * Time: 04:07 PM
 */

namespace StageBit\Blog\Block\Adminhtml\Allblogs\Edit;

use Magento\Backend\Block\Widget\Context;
use StageBit\Blog\Api\AllblogsRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class GenericButton
{
    protected $context;

    protected $allblogsRepository;

    public function __construct(
        Context $context,
        AllblogsRepositoryInterface $allblogsRepository
    ) {
        $this->context = $context;
        $this->allblogsRepository = $allblogsRepository;
    }

    public function getBlogId()
    {
        try {
            return $this->allblogsRepository->getById(
                $this->context->getRequest()->getParam('post_id')
            )->getId();
        } catch (NoSuchEntityException $e) {
        }
        return null;
    }

    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
}