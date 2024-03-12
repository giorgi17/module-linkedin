<?php declare(strict_types=1);

namespace Devall\Linkedin\Controller\LinkedinValidate;

use Devall\Linkedin\Model\Attribute\Validation\LinkedinProfileValidator;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;

class RequiredVisible implements HttpGetActionInterface
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

        $isVisible = $this->linkedinProfileValidator->isLinkedinProfileVisible();
        $isRequired = $this->linkedinProfileValidator->isLinkedinProfileRequired();

        try {
            return $result->setData([
                'isVisible' => $isVisible,
                'isRequired' => $isRequired,
            ]);
        } catch (\Exception $exception) {
            return $result->setData(['error' => $exception->getMessage()]);
        }
    }
}
