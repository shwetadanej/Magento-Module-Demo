<?php
/**
 * Created by PhpStorm.
 * User: Shweta
 * Date: 06-02-2019
 * Time: 11:55 AM
 */

namespace StageBit\Blog\Model\Allblogs\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Status implements OptionSourceInterface
{
    protected $allBlogs;

    public function __construct(\StageBit\Blog\Model\Allblogs $allBlogs)
    {
        $this->allBlogs = $allBlogs;
    }

    public function toOptionArray()
    {
        $availableOptions = $this->allBlogs->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
?>