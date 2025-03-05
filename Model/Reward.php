<?php

namespace Thao\Rewards\Model;

use Magento\Framework\Model\AbstractModel;

class Reward extends AbstractModel
{
    public const   ORDER_COMPLETED_ACTION = "order-completed";
    public const   REWARDS_SPEND_ACTION = "rewards_spend…";
    public const   ORDER_CANCEL_ACTION = "order_cancel";


    /**
     * Khởi tạo ResourceModel
     */
    protected function _construct()
    {
        $this->_init(\Thao\Rewards\Model\ResourceModel\Reward::class);
    }

    // 🟢 Getters và Setters cho từng cột trong bảng

    // reward_id
    public function getRewardId()
    {
        return $this->getData('reward_id');
    }

    public function setRewardId($rewardId)
    {
        return $this->setData('reward_id', $rewardId);
    }

    // action_date
    public function getActionDate()
    {
        return $this->getData('action_date');
    }

    public function setActionDate($actionDate)
    {
        return $this->setData('action_date', $actionDate);
    }

    // amount
    public function getAmount()
    {
        return $this->getData('amount');
    }

    public function setAmount($amount)
    {
        return $this->setData('amount', $amount);
    }

    // comment
    public function getComment()
    {
        return $this->getData('comment');
    }

    public function setComment($comment)
    {
        return $this->setData('comment', $comment);
    }

    // action
    public function getAction()
    {
        return $this->getData('action');
    }

    public function setAction($action)
    {
        return $this->setData('action', $action);
    }

    // point_left
    public function getPointLeft()
    {
        return $this->getData('point_left');
    }

    public function setPointLeft($pointLeft)
    {
        return $this->setData('point_left', $pointLeft);
    }

    // customer_id
    public function getCustomerId()
    {
        return $this->getData('customer_id');
    }

    public function setCustomerId($customerId)
    {
        return $this->setData('customer_id', $customerId);
    }
}
