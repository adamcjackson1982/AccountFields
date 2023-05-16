<?php
namespace Adam\AccountCreateFields\Setup;

use Magento\Customer\Model\Customer;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class upgradeData implements \Magento\Framework\Setup\UpgradeDataInterface
{
    private $eavSetupFactory;
    
    private $eavConfig;
    
    private $attributeResource;

    public function __construct(
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Customer\Model\ResourceModel\Attribute $attributeResource
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->eavConfig = $eavConfig;
        $this->attributeResource = $attributeResource;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.2', '<')) {
    
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        
        $attributeSetId = $eavSetup->getDefaultAttributeSetId(Customer::ENTITY);
        $attributeGroupId = $eavSetup->getDefaultAttributeGroupId(Customer::ENTITY);

        $eavSetup->addAttribute(Customer::ENTITY, 'heard_about_us', [
            // Attribute parameters
            'label' => 'Where did you hear about us?',
            'system' => 0,
            'position' => 721,
            'sort_order' => 721,
            'visible' => true,
            'note' => '',
            'type' => 'varchar',
            'input' => 'select',
            'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Table',
            'option' => [
                'values' => [
                    'Publication','Event/Exhibition','Recommendation','Search Engine','Sales Representative','Social Media','Other - Please specify' 
                ]
            ],
        ]);

        $attribute = $this->eavConfig->getAttribute(Customer::ENTITY, 'heard_about_us');
        $attribute->setData('attribute_set_id', $attributeSetId);
        $attribute->setData('attribute_group_id', $attributeGroupId);

            /*
            //You can use this attribute in the following forms
            adminhtml_checkout
            adminhtml_customer
            adminhtml_customer_address
            customer_account_create
            customer_account_edit
            customer_address_edit
            customer_register_address
            */

            $attribute->setData('used_in_forms', [
                'adminhtml_customer',
                'customer_account_create',
                'customer_account_edit'
            ]);

            $this->attributeResource->save($attribute);

        }

        $setup->endSetup();
    }

}
