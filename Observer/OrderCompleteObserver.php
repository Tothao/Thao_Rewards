<?php
namespace Thao\Rewards\Observer;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\OrderFactory;
use Magento\Sales\Model\Order;
use Thao\Rewards\Model\RewardFactory;
use Thao\Rewards\Model\Reward;
use Thao\Rewards\Helper\Data;


class  OrderCompleteObserver implements ObserverInterface
{
    /**
     * @var RewardFactory
     */
    protected $rewardFactory;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @param RewardFactory $rewardFactory
     * @param Data $helper
     */
    public function __construct(
        RewardFactory $rewardFactory,
        Data $helper
    ) {
        $this->rewardFactory= $rewardFactory;
        $this->helper = $helper;
    }

    /**
     * @param EventObserver $observer
     * @return void
     */

    public function execute(EventObserver $observer)
    {
       $isEnabled = $this->helper->enabledRewardPoint();
       if(!$isEnabled){
           return;
       }
        $order = $observer->getEvent()->getDataObject();
        $state = $order ->getState();
        if($state=='complete'){
            $this->setRewardPoint($order);
        }

        $isRewardPointUsed = $order->getRewardPointUsed();

        if ($state ==  'canceled' && $isRewardPointUsed){
            $this->setRefundReward($order);
        }
    }

    /**
     * @param $subtotal
     * @return void
     */
    public function setRewardPoint($order)
    {
        $reWardAmount = ($order->getGrandTotal());
        $reWards = $this->rewardFactory->create();
        $reWards
            ->setAmount($reWardAmount)
            ->setComment("Order complete #" . $order->getIncrementId())
            ->setAction(Reward::ORDER_COMPLETED_ACTION)
            ->setPointLeft($this->helper->getOrderPointLeft($order,null) + $reWardAmount)
            ->setCustomerId($order->getCustomerId())
            ->save();
    }
    public function setRefundReward($order)
    {
        $isRewardPointUsed = $order->getRewardPointUsed();
        $reWardAmount = ($isRewardPointUsed);
        $reWards = $this->rewardFactory->create();
        $reWards
            ->setAmount($reWardAmount)
            ->setComment("cancel order #" . $order->getIncrementId())
            ->setAction(Reward::ORDER_CANCEL_ACTION)
            ->setPointLeft($this->helper->getOrderPointLeft($order,null) + $reWardAmount)
            ->setCustomerId($order->getCustomerId())
            ->save();
    }
}
