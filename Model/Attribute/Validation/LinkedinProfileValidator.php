<?php declare(strict_types=1);

namespace Devall\Linkedin\Model\Attribute\Validation;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Validator\AbstractValidator;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory as CustomerCollectionFactory;
use Magento\Customer\Model\Session as CustomerSession;

class LinkedinProfileValidator extends AbstractValidator
{
    public function __construct(
        private CustomerCollectionFactory $customerCollectionFactory,
        private CustomerSession $customerSession,
        private ScopeConfigInterface $scopeConfig,
    )
    {
    }

    /**
     * @param $value string
     * @param $currentUserId string
     * @return bool
     */
    public function isValid($value): bool
    {
        $this->_clearMessages();

        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            $this->_addMessages(['Invalid URL format.']);
            return false;
        }

        if (strlen($value) > 250) {
            $this->_addMessages(['Attribute value exceeds maximum length of 250 characters.']);
            return false;
        }

        if (!$this->isUnique($value)) {
            $this->_addMessages(['The LinkedIn profile must be unique.']);
            return false;
        }

        return true;
    }

    /**
     * @param $value
     * @return bool
     */
    private function isUnique($value): bool
    {
        $customerCollection = $this->customerCollectionFactory->create();
        $customerCollection->addAttributeToSelect('*');
        $customerCollection->addFieldToFilter('linkedin_profile', $value);
        $customers = $customerCollection->getItems();

        $firstRetrievedUserWithThisAttributeValue = reset($customers);

        if (sizeof($customers) > 1) {
            return false;
        } else if (sizeof($customers) === 1) {
            if ($this->customerSession->isLoggedIn()) {
                $loggedInCustomerId = $this->customerSession->getCustomerId();
                $retrievedCustomerId = $firstRetrievedUserWithThisAttributeValue->getData()['entity_id'];
                if ($loggedInCustomerId !== $retrievedCustomerId) {
                    return false;
                }
            } else {
                return false;
            }
        }

        return true;
    }

    public function isLinkedinProfileVisible(): bool
    {
        return (bool)$this->scopeConfig->getValue('devall_linkedin/general/linkedin_visibility');
    }

    public function isLinkedinProfileRequired(): bool
    {
        return (bool)$this->scopeConfig->getValue('devall_linkedin/general/linkedin_required');
    }
}
