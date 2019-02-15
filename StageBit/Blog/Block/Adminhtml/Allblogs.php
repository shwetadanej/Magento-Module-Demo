<?php
/**
 * Created by PhpStorm.
 * User: Shweta
 * Date: 06-02-2019
 * Time: 04:04 PM
 */

namespace StageBit\Blog\Block\Adminhtml;


class Allblogs extends \Magento\Backend\Block\Widget\Grid\Container
{
    protected function _construct()
    {
        $this->_controller = 'adminhtml_allblogs';
        $this->_blockGroup = 'StageBit_Blog';
        $this->_headerText = __('Manage Blogs');

        parent::_construct();

        if ($this->_isAllowedAction('StageBit_Blog::save')) {
            $this->buttonList->update('add', 'label', __('Add Blog'));
        } else {
            $this->buttonList->remove('add');
        }
    }

    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
}
?>