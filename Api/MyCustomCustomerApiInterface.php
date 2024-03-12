<?php declare(strict_types=1);

namespace Devall\Linkedin\Api;

/**
 * Interface MyCustomCustomerApiInterface
 * @api
 */
interface MyCustomCustomerApiInterface
{
    /**
     * Retrieve customer data by email
     *
     * @param string $email
     * @return \Magento\Customer\Api\Data\CustomerInterface
     */
    public function getCustomerByEmail($email);
}
