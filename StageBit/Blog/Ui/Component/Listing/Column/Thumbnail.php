<?php
/**
 * Created by PhpStorm.
 * User: Shweta
 * Date: 07-02-2019
 * Time: 04:44 PM
 */

namespace StageBit\Blog\Ui\Component\Listing\Column;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

/**
 * @api
 * @since 100.0.2
 */
class Thumbnail extends \Magento\Ui\Component\Listing\Columns\Column
{
    const BLOG_URL_PATH_EDIT = 'stagebit_blog/allblogs/edit';

    protected $allblogs;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
        \StageBit\Blog\Model\Allblogs $allblogs,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->urlBuilder = $urlBuilder;
        $this->allblogs = $allblogs;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                $blog = new \Magento\Framework\DataObject($item);
                $item[$fieldName . '_src'] = $this->allblogs->getImageUrl($blog['featured_image']);
                $item[$fieldName . '_orig_src'] = $this->allblogs->getImageUrl($blog['featured_image']);
                $item[$fieldName . '_link'] = $this->urlBuilder->getUrl(
                    self::BLOG_URL_PATH_EDIT,
                    ['id' => $blog['id']]
                );
                $item[$fieldName . '_alt'] = $blog['name'];
            }
        }

        return $dataSource;
    }

}