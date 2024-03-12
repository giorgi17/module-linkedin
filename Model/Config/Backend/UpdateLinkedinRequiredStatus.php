<?php declare(strict_types=1);

namespace Devall\Linkedin\Model\Config\Backend;

use Magento\Framework\App\Config\Value;
use Magento\Framework\Event\ManagerInterface;
use \Magento\Framework\Model\Context;
use \Magento\Framework\Registry;
use \Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Cache\TypeListInterface;

class UpdateLinkedinRequiredStatus extends Value
{
    public function __construct(
        private Context $context,
        private Registry $registry,
        private ScopeConfigInterface $config,
        TypeListInterface $cacheTypeList,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        private ManagerInterface $eventManager,
        array $data = [],
    )
    {
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    public function beforeSave()
    {
        $this->eventManager
            ->dispatch('devall_linkedin_update_linkedin_profile_required_status', ['value' => $this->getValue()]);
        return parent::beforeSave();
    }
}
