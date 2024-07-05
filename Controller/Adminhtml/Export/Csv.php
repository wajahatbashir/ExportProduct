<?php
namespace WB\ExportProduct\Controller\Adminhtml\Export;

use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\ResultFactory;
use Magento\Catalog\Helper\Image;

class Csv extends Action
{
    protected $resultRawFactory;
    protected $csvProcessor;
    protected $directoryList;
    protected $productCollectionFactory;
    protected $storeManager;
    protected $imageHelper;

    public function __construct(
        Action\Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        \Magento\Framework\File\Csv $csvProcessor,
        DirectoryList $directoryList,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Image $imageHelper
    ) {
        $this->resultRawFactory = $resultRawFactory;
        $this->csvProcessor = $csvProcessor;
        $this->directoryList = $directoryList;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->storeManager = $storeManager;
        $this->imageHelper = $imageHelper;
        parent::__construct($context);
    }

    public function execute()
    {
        $fileName = 'products.csv';
        $filePath = $this->directoryList->getPath(DirectoryList::VAR_DIR) . "/wbexport-product/" . $fileName;

        $products = $this->productCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->load();

        $data = [];
        $headers = ['ID', 'Name', 'SKU', 'Price', 'Quantity', 'Status', 'Store', 'Base Image URL', 'Visibility', 'Manufacturer'];
        $data[] = $headers;

        foreach ($products as $product) {
            $store = $this->storeManager->getStore($product->getStoreId())->getName();
            $image = $this->imageHelper->init($product, 'product_page_image_large')->getUrl();
            $manufacturer = $product->getAttributeText('manufacturer');
            $data[] = [
                $product->getId(),
                $product->getName(),
                $product->getSku(),
                $product->getPrice(),
                $product->getExtensionAttributes()->getStockItem()->getQty(),
                $product->getStatus(),
                $store,
                $image,
                $product->getVisibility(),
                $manufacturer
            ];
        }

        $this->csvProcessor
            ->setEnclosure('"')
            ->setDelimiter(',')
            ->saveData($filePath, $data);

        return $this->resultFactory->create(ResultFactory::TYPE_PAGE)->setContents(file_get_contents($filePath));
    }
}
