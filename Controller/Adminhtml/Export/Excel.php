<?php
namespace WB\ExportProduct\Controller\Adminhtml\Export;

use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Response\Http\FileFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Magento\Catalog\Helper\Image;

class Excel extends Action
{
    protected $resultRawFactory;
    protected $directoryList;
    protected $productCollectionFactory;
    protected $fileFactory;
    protected $storeManager;
    protected $imageHelper;

    public function __construct(
        Action\Context $context,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory,
        DirectoryList $directoryList,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        FileFactory $fileFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Image $imageHelper
    ) {
        $this->resultRawFactory = $resultRawFactory;
        $this->directoryList = $directoryList;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->fileFactory = $fileFactory;
        $this->storeManager = $storeManager;
        $this->imageHelper = $imageHelper;
        parent::__construct($context);
    }

    public function execute()
    {
        $fileName = 'products.xlsx';
        $filePath = $this->directoryList->getPath(DirectoryList::VAR_DIR) . "/wbexport-product/" . $fileName;

        $products = $this->productCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->load();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = ['ID', 'Name', 'SKU', 'Price', 'Quantity', 'Status', 'Store', 'Base Image URL', 'Visibility', 'Manufacturer'];
        $sheet->fromArray($headers, NULL, 'A1');

        $rowNumber = 2;
        foreach ($products as $product) {
            $store = $this->storeManager->getStore($product->getStoreId())->getName();
            $image = $this->imageHelper->init($product, 'product_page_image_large')->getUrl();
            $manufacturer = $product->getAttributeText('manufacturer');
            $sheet->fromArray([
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
            ], NULL, 'A' . $rowNumber);
            $rowNumber++;
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        return $this->fileFactory->create(
            $fileName,
            [
                'type' => 'filename',
                'value' => "wbexport-product/" . $fileName,
                'rm' => true
            ],
            DirectoryList::VAR_DIR,
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );
    }
}
