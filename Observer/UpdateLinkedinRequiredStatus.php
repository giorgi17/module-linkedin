<?php declare(strict_types=1);

namespace Devall\Linkedin\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetupFactory;

class UpdateLinkedinRequiredStatus implements ObserverInterface
{
    public function __construct(
        private CustomerSetupFactory $customerSetupFactory,
    )
    {
    }

    public function execute(Observer $observer)
    {
        $value = $observer->getData('value');

        $customerSetup = $this->customerSetupFactory->create();
        $attribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, 'linkedin_profile');
        $attribute->setIsRequired($value);
        $attribute->save();
    }
}
