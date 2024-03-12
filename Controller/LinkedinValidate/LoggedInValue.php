<?php declare(strict_types=1);

namespace Devall\Linkedin\Controller\LinkedinValidate;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;

class LoggedInValue implements HttpGetActionInterface
{
    public function __construct(
        private RequestInterface $request,
        protected JsonFactory $resultJsonFactory,
        private Session $customerSession,
        private CustomerRepositoryInterface $customerRepository,
    )
    {
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();

        $customerId = $this->customerSession->getCustomerId();

        try {
            $isLoggedIn = false;
            $loggedUserValue = '';

            if ($customerId) {
                $isLoggedIn = true;
                $customer = $this->customerRepository->getById($customerId);

                $linkedin_attribute = $customer->getCustomAttribute('linkedin_profile');

                if ($linkedin_attribute) {
                    $loggedUserValue = $linkedin_attribute->getValue();
                }
            }

            return $result->setData([
                'isLoggedIn' => $isLoggedIn,
                'loggedUserValue' => $loggedUserValue,
            ]);
        } catch (\Exception $exception) {
            return $result->setData(['error' => $exception->getMessage()]);
        }
    }
}
