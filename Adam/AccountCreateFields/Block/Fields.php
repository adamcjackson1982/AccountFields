<?php
namespace Adam\AccountCreateFields\Block;

use Magento\Eav\Model\Config;
use Magento\Framework\View\Element\Template\Context;

class Fields extends \Magento\Framework\View\Element\Template
{
    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        Config $eavConfig,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->eavConfig = $eavConfig;
    }

    public function getTradeOptions() {
        $options = [];

        $attribute = $this->eavConfig->getAttribute(\Magento\Customer\Model\Customer::ENTITY, 'trade');
        
    
        if ($attribute && $attribute->usesSource()) {
            $options = $attribute->getSource()->getAllOptions();
        }
        
        return $options;
    }

    public function getHeardAboutUsOptions()
    {
        $options = [];
    
        // Retrieve the attribute by code
        $attribute = $this->eavConfig->getAttribute(\Magento\Customer\Model\Customer::ENTITY, 'heard_about_us');
    
        // Check if attribute exists and has options
        if ($attribute && $attribute->usesSource()) {
            $attributeOptions = $attribute->getSource()->getAllOptions();
    
            // Remove the first (empty) option from the array
            if (!empty($attributeOptions)) {
                array_shift($attributeOptions);
            }
    
            // Insert the "Please select" option at the beginning of the array
            array_unshift($attributeOptions, ['value' => '', 'label' => __('Please select')]);
    
            $options = array_merge($options, $attributeOptions);
        }
    
        return $options;
    }
}
