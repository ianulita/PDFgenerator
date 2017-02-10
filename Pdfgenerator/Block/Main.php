<?php

namespace Acidgreen\PdfGenerator\Block;

use Magento\Framework\View\Element\Template;
use \Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;
use Magento\Sales\Model\Order;
use \Magento\Theme\Block\Html\Header\Logo;
use Magento\Customer\Model\Customer;
use \Magento\Catalog\Api\ProductRepositoryInterface;
use \Magento\Sales\Model\ResourceModel\Order\Payment\CollectionFactory;
use \Magento\Framework\View\Asset\Repository;
class Main extends Template
{
    protected $coreRegistry = null;

    public function __construct(
        Context $context,
        Registry $coreRegistry,
        Order $orderFactory,
        Logo $logo,
        Customer $customer,
        ProductRepositoryInterface $productRepo,
        CollectionFactory $paymentCollection,
        Repository $assetRepository
    ) {
        $this->_coreRegistry = $coreRegistry;
        $this->_logo = $logo;
        $this->orderFactory = $orderFactory;
        $this->customer = $customer;
        $this->productRepository = $productRepo;
        $this->paymentCollection = $paymentCollection;
        $this->assetRepository = $assetRepository;
        parent::__construct($context);
    }
    public function getAssets(){
        $asset_repository = $this->assetRepository;
        return $asset_repository->createAsset('Acidgreen_PdfGenerator/css/print-styles.css');
    }
    public function getPaymentsCollection() {
        $collection = $this->_paymentCollectionFactory->create()->addFieldToSelect('*');
        return $collection;
    }
    public function getOrderDetails($orderid = "") {
        if($orderid == "") 
            return $this->orderFactory->load($this->_coreRegistry->registry('orderId'));
        else 
            return $this->orderFactory->load($orderid);
    }
    public function getShipment()
    {
        return $this->_coreRegistry->registry('shipments');
    }

    public function getLogoSrc()
    {    
        return $this->_logo->getLogoSrc();
    }
    public function getOrderCustomerDetails($customerId) {
        return $this->customer->load($customerId);
    }
    public function getProductDetails($sku) {
        return $this->productRepository->get($sku);
    }
}

?>