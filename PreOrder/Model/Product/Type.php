<?php

namespace Acidgreen\PreOrder\Model\Product;

class Type extends \Magento\Catalog\Model\Product\Type
{
    const TYPE_CONFIGURABLE = 'configurable';

    const TYPE_DOWNLOADABLE = 'downloadable';

    const TYPE_GROUPED = 'grouped';
}