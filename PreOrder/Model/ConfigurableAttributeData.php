<?php

namespace Acidgreen\PreOrder\Model;

use Magento\ConfigurableProduct\Model\Product\Type\Configurable\Attribute;

/**
 * Class ConfigurableAttributeData
 */
class ConfigurableAttributeData extends \Magento\ConfigurableProduct\Model\ConfigurableAttributeData
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @param Type $catalogProductType
     * @param LoggerInterface $logger
     */
    public function __construct(
        \Acidgreen\PreOrder\Model\Product\Type $catalogProductType,
        \PSR\Log\LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    /**
     * @param Product $product
     * @param array $options
     * @return array
     */
    public function getAttributesData(\Magento\Catalog\Model\Product $product, array $options = [])
    {
        $defaultValues = [];
        $attributes = [];

        foreach ($product->getTypeInstance()->getConfigurableAttributes($product) as $attribute) {
            $attributeOptionsData = $this->getAttributeOptionsData($attribute, $options);
            if ($attributeOptionsData) {
                $productAttribute = $attribute->getProductAttribute();
                $attributeId = $productAttribute->getId();
                $attributes[$attributeId] = [
                    'id' => $attributeId,
                    'code' => $productAttribute->getAttributeCode(),
                    'label' => $attribute->getLabel(),
                    'options' => $attributeOptionsData,
                ];
                $defaultValues[$attributeId] = $this->getAttributeConfigValue($attributeId, $product);
            }
        }

        $preorderAttr = ['pre_order_note'];
        $options = $this->preorderOptions($product);

        foreach ($preorderAttr as $key => $attr) {
            $result = $this->findAttr($attr, $product);

            if ($result) {
                $attributeId = $result->getId();

                $attributes[$attributeId] = [
                    'id' => $attributeId,
                    'code' => $result->getAttributeCode(),
                    'options' => $options[$key],
                ];
            }
        }

        return ['attributes' => $attributes, 'defaultValues' => $defaultValues,];
    }

    /**
     * @param array $attr
     * @param Product $product
     * @return array
     */
    protected function findAttr($attr, $product)
    {
        $attributes = $product->getAttributes();
        if ($attributes[$attr]) {
            return $attributes[$attr];
        }
        return null;
    }

    protected function preorderOptions($product)
    {
        $attributeOptionsPreOrder = [];
        $associatedProducts = $product->getTypeInstance()->getUsedProducts($product);
        $valuesPreorder = [];
        $counter = 0;

        foreach ($associatedProducts as $_product) {

            $preOrderNote = (string)$_product->getPreOrderNote();

            if($preOrderNote) {

                if (!isset($valuesPreorder[$preOrderNote])) {
                    $valuesPreorder[$preOrderNote] = [];
                    $valuesPreorder[$preOrderNote] = ["products" => []];
                }
                $valuesPreorder[$preOrderNote]['products'][] = $_product->getId();
            }

        }

        $inc = 0;
        foreach ($valuesPreorder as $key => $element) {
            $attributeOptionsPreOrder[] = ['id' => $inc, 'label' => $key, 'products' => $element['products'],];
            $inc++;
        }

        return [$attributeOptionsPreOrder];
    }

    /**
     * @param Attribute $attribute
     * @param array $config
     * @return array
     */
    protected function getAttributeOptionsData($attribute, $config)
    {
        $attributeOptionsData = [];
        foreach ($attribute->getOptions() as $attributeOption) {
            $optionId = $attributeOption['value_index'];
            $attributeOptionsData[] = [
                'id' => $optionId,
                'label' => $attributeOption['label'],
                'products' => isset($config[$attribute->getAttributeId()][$optionId])
                    ? $config[$attribute->getAttributeId()][$optionId]
                    : [],
            ];
        }
        return $attributeOptionsData;
    }

    /**
     * @param int $attributeId
     * @param Product $product
     * @return mixed|null
     */
    protected function getAttributeConfigValue($attributeId, $product)
    {
        return $product->hasPreconfiguredValues()
            ? $product->getPreconfiguredValues()->getData('super_attribute/' . $attributeId)
            : null;
    }
}
