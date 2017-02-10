<?php
namespace Acidgreen\PreOrder\Observer;

use Magento\Framework\Event\ObserverInterface;

use Magento\Catalog\Model\Product;
use Magento\Checkout\Model\Cart;

class SetPreOrderNote implements ObserverInterface
{
    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;

    public function __construct(
        \Magento\Checkout\Model\Cart $cart,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Quote\Model\Quote\Item $quote,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->cart = $cart;
        $this->productFactory = $productFactory;
        $this->quote = $quote;
        $this->logger = $logger;
    }
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $preOrderNote = '';
        $quoteItem = $observer->getQuoteItem();

        $simpleProduct = $quoteItem->getProduct()->getCustomOption('simple_product');

        $currentProduct = (isset($simpleProduct)) ? $simpleProduct->getProduct() : $quoteItem->getProduct();
        if (!empty($currentProduct)) {
            $product = $this->productFactory->create()->load($currentProduct->getId());
            $quoteItem->setPreOrderNote($product->getPreOrderNote());
        }
    }
}