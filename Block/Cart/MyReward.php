<?php

namespace Thao\Rewards\Block\Cart;

use Magento\Framework\View\Element\Template;
use Magento\Customer\Model\Session;
use Thao\Rewards\Model\ResourceModel\Reward\CollectionFactory;

class MyReward extends Template
{
    protected $customerSession;
    protected $rewardCollectionFactory;

    public function __construct(
        Template\Context $context,
        Session $customerSession,
        CollectionFactory $rewardCollectionFactory,
        array $data = []
    ) {
        $this->customerSession = $customerSession;
        $this->rewardCollectionFactory = $rewardCollectionFactory;
        parent::__construct($context, $data);
    }

    public function getRewardHistory()
    {
        if (!$this->customerSession->isLoggedIn()) {
            return [];
        }

        $customerId = $this->customerSession->getCustomerId();
        $collection = $this->rewardCollectionFactory->create()
            ->addFieldToFilter('customer_id', $customerId)
            ->setOrder('action_date', 'DESC');

        return $collection->getItems();
    }

    public function getLastPointLeft()
    {
        $customerId = $this->customerSession->getCustomerId();
        $collection = $this->rewardCollectionFactory->create()
            ->addFieldToFilter('customer_id', $customerId)
            ->setOrder('action_date', 'DESC')
            ->setPageSize(1);

        return (int) $collection->getFirstItem()->getPointLeft();
    }
}
