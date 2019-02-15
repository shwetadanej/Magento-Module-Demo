<?php
/**
 * Created by PhpStorm.
 * User: Shweta
 * Date: 06-02-2019
 * Time: 01:09 PM
 */

namespace StageBit\Blog\Model;

use StageBit\Blog\Api\Data\AllblogsInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;

use StageBit\Blog\Model\Allblogs\FileInfo;
use Magento\Framework\App\ObjectManager;
use Magento\Store\Model\StoreManagerInterface;

class Allblogs extends AbstractModel implements AllblogsInterface, IdentityInterface
{

    protected $_storeManager;

    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;

    const CACHE_TAG = 'stagebit_blog';

    //Unique identifier for use within caching
    protected $_cacheTag = self::CACHE_TAG;

    protected function _construct()
    {
        $this->_init('StageBit\Blog\Model\ResourceModel\Allblogs');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];
        return $values;
    }

    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }

    public function getId(){
        return parent::getData(self::POST_ID);
    }

    public function getName(){
        return parent::getData(self::TITLE);
    }

    public function getPostExcerpt(){
        return parent::getData(self::EXCERPT);
    }

    public function getPostContent(){
        return parent::getData(self::DESCRIPTION);
    }

    public function getAuthorName(){
        return parent::getData(self::AUTHOR_NAME);
    }

    public function getAuthorWebsite(){
        return parent::getData(self::AUTHOR_WEBSITE);
    }

    public function getFeaturedImage(){
        return parent::getData(self::FEATURED_IMAGE);
    }

    public function getIsActive(){
        return parent::getData(self::STATUS);
    }

    public function getDisplayDate(){
        return parent::getData(self::DISPLAY_DATE);
    }

    public function getCreatedAt(){
        return parent::getData(self::CREATED_AT);
    }

    public function getUpdatedAt(){
        return parent::getData(self::UPDATED_AT);
    }

    public function getUrlKey(){
        return parent::getData(self::URL_KEY);
    }

    public function setId($id){
        return $this->setData(self::POST_ID, $id);
    }

    public function setName($title){
        return $this->setData(self::TITLE, $title);
    }

    public function setPostExcerpt($post_excerpt){
        return $this->setData(self::EXCERPT, $post_excerpt);
    }

    public function setPostContent($post_content){
        return $this->setData(self::DESCRIPTION, $post_content);
    }

    public function setAuthorName($author_name){
        return $this->setData(self::AUTHOR_NAME, $author_name);
    }

    public function setAuthorWebsite($author_website){
        return $this->setData(self::AUTHOR_WEBSITE, $author_website);
    }

    public function setFeaturedImage($featured_image){
        return $this->setData(self::FEATURED_IMAGE, $featured_image);
    }

    public function setIsActive($status){
        return $this->setData(self::STATUS, $status);
    }

    public function setDisplayDate($display_date){
        return $this->setData(self::DISPLAY_DATE, $display_date);
    }

    public function setCreatedAt($created_at){
        return $this->setData(self::CREATED_AT, $created_at);
    }

    public function setUpdatedAt($updated_at){
        return $this->setData(self::UPDATED_AT, $updated_at);
    }

    public function setUrlKey($url_key){
        return $this->setData(self::URL_KEY, $url_key);
    }

    /**
     * Retrieve the Image URL
     *
     * @param string $imageName
     * @return bool|string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getImageUrl($imageName = null)
    {
        $url = '';
        $image = $imageName;
        if (!$image) {
            $image = $this->getData('featured_image');
        }
        if ($image) {
            if (is_string($image)) {
                $url = $this->_getStoreManager()->getStore()->getBaseUrl(
                        \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
                    ).FileInfo::ENTITY_MEDIA_PATH .'/'. $image;
            } else {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('Something went wrong while getting the image url.')
                );
            }
        }
        return $url;
    }

    private function _getStoreManager()
    {
        if ($this->_storeManager === null) {
            $this->_storeManager = ObjectManager::getInstance()->get(StoreManagerInterface::class);
        }
        return $this->_storeManager;
    }

    public static function check_url_key_exist($key){
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('stagebit_blogs');

        $sql = "Select post_id FROM $tableName where url_key='".$key."' ";
        $result = $connection->fetchRow($sql);
        return $result;
    }
}
?>