<?php

namespace Acidgreen\PdfGenerator\Controller\Invoice;
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
    public function createInvoicePdf($orderid) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://127.0.0.1:8888/magetuts/magetuts/pdfgenerator/index/index/invoice_id/' . $orderid . '/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $my_html = curl_exec($ch);
        $loc = strpos($my_html, "<input name=\"form_key\"");
        $render = substr($my_html, $loc);
        curl_close($ch);
        
        return $render;
    }
    public function execute()
    {
        $data = $this->getData();
        $orderId = $data['invoice_id'];
        /*****
        * Create PDF
        *****/
        $response = $this->dompdfFactory->create();
        $response->setData($this->createInvoicePdf($orderId));
        return $response;

    }
}
