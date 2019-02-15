<?php
/**
 * Created by PhpStorm.
 * User: Shweta
 * Date: 05-02-2019
 * Time: 11:40 AM
 */

namespace StageBit\Blog\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{

    const XML_PATH_BLOGS = 'blogs/';

    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue($field, ScopeInterface::SCOPE_STORE, $storeId);
    }

    public function getGeneralConfig($field, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_BLOGS .'general/'. $field, $storeId);
    }
    public function getStorefrontConfig($field, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_BLOGS .'storefront/'. $field, $storeId);
    }

}