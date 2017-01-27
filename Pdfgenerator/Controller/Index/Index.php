<?php

namespace Acidgreen\PdfGenerator\Controller\Index;
use WeProvide\Dompdf\Controller\Result\Dompdf;
class Index extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;
    protected $dompdfFactory;
    protected $layoutFactory;
    protected $coreRegistry = null;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \WeProvide\Dompdf\Controller\Result\DompdfFactory $dompdfFactory,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Sales\Api\ShipmentRepositoryInterface $shipmentRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Framework\Registry $coreRegistry
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->dompdfFactory = $dompdfFactory;
        $this->layoutFactory = $layoutFactory;
        $this->_coreRegistry = $coreRegistry;
        $this->shipmentRepository = $shipmentRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->request = $request;
        parent::__construct($context);
    }
    public function getData() {
        return $this->request->getParams();
    }
    public function createShipmentPdf() {
        $block = $this->layoutFactory->create()->createBlock('Magento\Framework\View\Element\Template');
        $block->setTemplate('Acidgreen_PdfGenerator::main.phtml');
        $data = $this->getData();
        $block->setData($data);
        
        return $block->toHtml();
    }

    public function execute()
    {
        $data = $this->getData();
        $criteria = $this->searchCriteriaBuilder->addFilter('entity_id', $data['shipment_id'])->create();
        $shipmentResult = $this->shipmentRepository->get($data['shipment_id']);
        $shipments = $shipmentResult->getItems();
        $this->_coreRegistry->register('shipments', $shipments);
        $this->_coreRegistry->register('data_test', $this->getData());

        /*****
        * Create PDF
        *****/
        // $response = $this->dompdfFactory->create();
        // $response->setData($this->createShipmentPdf());
        // return $response;

        /*****
        * Display on page
        *****/
        $page_object = $this->resultPageFactory->create();
        return $page_object;
    }
}
