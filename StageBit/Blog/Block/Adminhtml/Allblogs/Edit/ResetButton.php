<?php
/**
 * Created by PhpStorm.
 * User: Shweta
 * Date: 06-02-2019
 * Time: 04:14 PM
 */

namespace StageBit\Blog\Block\Adminhtml\Allblogs\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class ResetButton
 */
class ResetButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Reset'),
            'class' => 'reset',
            'on_click' => 'location.reload();',
            'sort_order' => 30
        ];
    }
}
?>