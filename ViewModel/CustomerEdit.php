<?php declare(strict_types=1);

namespace Devall\Linkedin\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Validator\Factory as ValidatorFactory;
use Magento\Framework\Validator\ValidatorInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Quote\Model\QuoteFactory;
use Magento\Quote\Model\ResourceModel\Quote as QuoteResourceModel;
use Magento\Framework\App\Request\Http;

class CustomerEdit implements ArgumentInterface
{

    public function __construct(
        private CustomerRepositoryInterface $customerRepository,
        private Session $customerSession,
        private ScopeConfigInterface $scopeConfig,
        private ValidatorFactory $validatorFactory,
        private CheckoutSession $checkoutSession,
        private QuoteFactory $quoteFactory,
        private QuoteResourceModel $quoteResourceModel,
        private Http $request,
    )
    {
    }

    public function validateLinkedinProfile($value): bool
    {
        /** @var ValidatorInterface $validator */
        $validator = $this->validatorFactory->create('linkedin_profile_validator');
        return $validator->isValid($value);
    }

    public function getCustomerLinkedin() {
        $previousUrl = $this->request->getServer('HTTP_REFERER');
        $previousUrlPath = parse_url($previousUrl)['path'];
        $isCheckoutRegister = $previousUrlPath === '/checkout/onepage/success/';

        $customerId = $this->customerSession->getCustomerId();

        if (!$customerId && $isCheckoutRegister) {
            $quoteId = $_SESSION['checkout']['last_quote_id'];
            $quote = $this->quoteFactory->create();
            $this->quoteResourceModel->load($quote, $quoteId);
            $shippingAddress = $quote->getShippingAddress();

            $linkedin_profile_value = $shippingAddress->getData()['linkedin_profile'];

            return $linkedin_profile_value;
        } else if ($customerId) {
            $customer = $this->customerRepository->getById($customerId);

            $linkedin_attribute = $customer->getCustomAttribute('linkedin_profile');

            if ($linkedin_attribute) {
                return $linkedin_attribute->getValue();
            }
        }

        return '';
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
