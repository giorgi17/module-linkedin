<?php declare(strict_types=1);

namespace Devall\Linkedin\Controller\LinkedinValidate;

use Devall\Linkedin\Model\Attribute\Validation\LinkedinProfileValidator;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;

class IsValid implements HttpPostActionInterface
{
    public function __construct(
        private LinkedinProfileValidator $linkedinProfileValidator,
        private RequestInterface $request,
        protected JsonFactory $resultJsonFactory,
    )
    {
    }

    public function execute()
    {
        $result = $this->resultJsonFactory->create();

        // Get the raw POST data
        $rawData = file_get_contents('php://input');

        // Decode the JSON string to an associative array
        $data = json_decode($rawData, true);

        try {
            // Check if the 'linkedinLink' key exists in the array
            if (!array_key_exists('linkedinLink', $data)) {
                throw new Exception('Missing required parameter: linkedinLink');
            }

            // Access the 'linkedinLink' value
            $linkedinLink = $data['linkedinLink'];

            $isValid = $this->linkedinProfileValidator->isValid($linkedinLink);
            $messages = $this->linkedinProfileValidator->getMessages();

            return $result->setData([
                'isValid' => $isValid,
                'messages' => $messages
            ]);
        } catch (\Exception $exception) {
            return $result->setData(['error' => $exception->getMessage()]);
        }
    }
}
