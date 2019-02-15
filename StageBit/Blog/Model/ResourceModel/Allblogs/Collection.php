<?php
/**
 * Created by PhpStorm.
 * User: Shweta
 * Date: 04-02-2019
 * Time: 04:54 PM
 */

namespace StageBit\Blog\Model\ResourceModel\Allblogs;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;


class Collection extends AbstractCollection
{
    protected $_idFieldName = 'post_id';

    protected $_eventPrefix = 'blog_allblogs_collection';

    protected $_eventObject = 'allblogs_collection';
    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('StageBit\Blog\Model\Allblogs', 'StageBit\Blog\Model\ResourceModel\Allblogs');
    }

}
