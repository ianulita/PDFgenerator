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
   
    public function execute()
    {
        $data = $this->getData();
        if($data['invoice_id']) {
            $criteria = $this->searchCriteriaBuilder->addFilter('entity_id', $data['invoice_id'])->create();
            $this->_coreRegistry->register('orderId', $data['invoice_id']);
            $this->_coreRegistry->register('shipments', 'test');
        }
        /*****
        * Display on page
        // *****/
        $page_object = $this->resultPageFactory->create();
        return $page_object;
    }
}
