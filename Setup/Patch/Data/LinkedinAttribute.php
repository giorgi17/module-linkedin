<?php declare(strict_types=1);

namespace Devall\Linkedin\Setup\Patch\Data;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class LinkedinAttribute implements DataPatchInterface
{
    const ATTRIBUTE_CODE = 'linkedin_profile';

    public function __construct(
        private CustomerSetupFactory $customerSetupFactory,
        private ModuleDataSetupInterface $moduleDataSetup,
    )
    {
    }

    public static function getDependencies(): array
    {
        return [];
    }

    public function getAliases(): array
    {
        return [];
    }

    public function apply(): void
    {
        $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $customerSetup->addAttribute(Customer::ENTITY, self::ATTRIBUTE_CODE, [
            'type' => 'varchar',
            'label' => 'LinkedIn Profile',
            'input' => 'text',
            'required' => true,
            'visible' => true,
            'system' => false,
            'position' => 160,
            'sort_order' => 160,
        ]);

        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, self::ATTRIBUTE_CODE);
        $attribute->setData('used_in_forms', [
            'adminhtml_customer',
            'customer_account_create',
            'customer_account_edit',
            'checkout_register',
        ]);
        $attribute->save();
    }
}
