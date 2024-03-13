<?php declare(strict_types=1);

namespace Devall\Linkedin\Model;

use Devall\Linkedin\Api\MyCustomCustomerApiInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;

class MyCustomCustomerApi implements MyCustomCustomerApiInterface
{
    /**
     * MyCustomCustomerApi constructor.
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        private CustomerRepositoryInterface $customerRepository
    )
    {
    }


    /**
     * {@inheritdoc}
     */
    public function getCustomerByEmail($email)
    {
        return $this->customerRepository->get($email);
    }
}
