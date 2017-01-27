<?php

namespace Acidgreen\PdfGenerator\Block;

use Magento\Framework\View\Element\Template;
use \Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Registry;

class Main extends Template
{
    protected $coreRegistry = null;

    public function __construct(
        Context $context,
        Registry $coreRegistry
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
    }

    public function getFoo()
    {
        // will return 'bar'
        return $this->_coreRegistry->registry('data_test');
    }
    public function getShipment()
    {
        return $this->_coreRegistry->registry('shipments');
    }
}

?>