<?php

namespace Thao\Rewards\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Reward extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('thao_rewards_rewards', 'reward_id');
    }
}
