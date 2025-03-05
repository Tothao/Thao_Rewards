<?php

namespace Thao\Rewards\Model\ResourceModel\Reward;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Thao\Rewards\Model\Reward as RewardModel;
use Thao\Rewards\Model\ResourceModel\Reward as RewardResourceModel;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        // Liên kết với Model và ResourceModel
        $this->_init(RewardModel::class, RewardResourceModel::class);
    }
}
