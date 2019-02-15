<?php
/**
 * Created by PhpStorm.
 * User: Shweta
 * Date: 06-02-2019
 * Time: 04:44 PM
 */

namespace StageBit\Blog\Model\Allblogs;

use StageBit\Blog\Model\ResourceModel\Allblogs\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ObjectManager;
use StageBit\Blog\Model\Allblogs\FileInfo;
use Magento\Framework\Filesystem;


class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $collection;
    protected $dataPersistor;
    protected $loadedData;
    private $fileInfo;

    public function __construct(
        $name, $primaryFieldName, $requestFieldName,
        CollectionFactory $allblogsCollectionFacrory,
        DataPersistorInterface $dataPersistor,
        array $meta = [], array $data = []
    )
    {
        $this->collection = $allblogsCollectionFacrory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->meta = $this->prepareMeta($this->meta);
    }

    public function prepareMeta(array $meta){
        return $meta;
    }

    public function getData(){
        if(isset($this->loadedData)){
            return $this->loadedData;
        }
        $items = $this->collection->getItems();

        foreach ($items as $blogs){
            $blogs = $this->convertValues($blogs);
            $this->loadedData[$blogs->getId()] = $blogs->getData();
        }

        $data =  $this->dataPersistor->get('blog_allblogs');
        if(!empty($data)){
            $blogs = $this->collection->getNewEmptyItem();
            $blogs->setData($data);
            $this->loadedData[$blogs->getId()] =  $blogs->getData();
            $this->dataPersistor->clear('blog_allblogs');
        }
        return $this->loadedData;
    }

    private function convertValues($blog)
    {
        $fileName = $blog->getFeaturedImage();
        $image = [];
        if ($this->getFileInfo()->isExist($fileName)) {
            $stat = $this->getFileInfo()->getStat($fileName);
            $mime = $this->getFileInfo()->getMimeType($fileName);
            $image[0]['name'] = $fileName;
            $image[0]['url'] = $blog->getImageUrl();
            $image[0]['size'] = isset($stat) ? $stat['size'] : 0;
            $image[0]['type'] = $mime;
        }
        $blog->setFeaturedImage($image);
        return $blog;
    }
    /**
     * Get FileInfo instance
     *
     * @return FileInfo
     *
     * @deprecated 101.1.0
     */
    private function getFileInfo()
    {
        if ($this->fileInfo === null) {
            $this->fileInfo = ObjectManager::getInstance()->get(FileInfo::class);
        }
        return $this->fileInfo;
    }
}