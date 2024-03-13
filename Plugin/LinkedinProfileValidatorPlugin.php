<?php declare(strict_types=1);

namespace Devall\Linkedin\Plugin;

use Devall\Linkedin\Model\Attribute\Validation\LinkedinProfileValidator;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Exception\InputException;

class LinkedinProfileValidatorPlugin
{
    public function __construct(
        private LinkedinProfileValidator $linkedinProfileValidator,
    )
    {
    }

    /**
     * @param AbstractModel $subject
     * @return array
     */
    public function beforeSave(AbstractModel $subject): array
    {

        if ($subject->getEventPrefix() === 'customer') {
            $validate = !$subject->getData('ignore_validation_flag');

            if (array_key_exists('linkedin_profile', $_POST) && $validate) {
                $linkedinProfileValue = $_POST['linkedin_profile'];
                if ($linkedinProfileValue) {
                    if (!$this->linkedinProfileValidator->isValid($linkedinProfileValue)) {
                        $messages = $this->linkedinProfileValidator->getMessages();
                        throw new InputException(
                            __("Linkedin Profile Validation failed: %1", implode(', ', $messages))
                        );
                    }
                }
            }
        }

        return [];
    }
}
