<?php
/**
 * Created by PhpStorm.
 * User: Shweta
 * Date: 06-02-2019
 * Time: 04:11 PM
 */

namespace StageBit\Blog\Block\Adminhtml\Allblogs\Edit;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->getBlogId()) {
            $data = [
                'label' => __('Delete Blog'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\'' . __(
                        'Are you sure you want to do this?'
                    ) . '\', \'' . $this->getDeleteUrl() . '\')',
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    /**
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', ['post_id' => $this->getBlogId()]);
    }
}
?>