<?php

namespace Bootcamp\StockStatus\Block\Catalog;

use Magento\Catalog\Api\ProductRepositoryInterface;

class Link extends \Magento\Catalog\Block\Product\View
{

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Customer\Model\Session $customerSession,
        ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        array $data = [],
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\CatalogInventory\Api\StockRegistryInterface $stockRegistry
    ) {

        $this->_scopeConfig = $scopeConfig;
        $this->stockRegistry = $stockRegistry;

        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepository,
            $priceCurrency,
            $data
        );
    }

    public function isActive(){
        return $this->_scopeConfig->getValue('stock_status/settings/enable');
    }

    public function getQty(){
        $productStock = $this->stockRegistry->getStockItem($this->getProduct()->getId());
        $productQty = $productStock->getQty();
        $inStock = $this->_scopeConfig->getValue('stock_status/quanty/in_stock');
        $lowStock = $this->_scopeConfig->getValue('stock_status/quanty/low_stock');
        $outStock = $this->_scopeConfig->getValue('stock_status/quanty/out_of_stock');
        if( $productQty >=  $inStock){
            $label =  '<span class="status-stock status-in-stock">'.__('In Stock').'</span>';
            
        }
        elseif($productQty >=  $lowStock){
            $label =  '<span class="status-stock status-low-stock">'.__('Low Stock').'</span>';
            
        }
        else{
            $label =  '<span class="status-stock status-out-of-stock">'.__('Out of Stock').'</span>';
            
        }
        return $label;
    }

}
