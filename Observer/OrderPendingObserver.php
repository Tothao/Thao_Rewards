<?php
namespace Thao\Rewards\Observer;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\OrderFactory;
use Magento\Sales\Model\Order;
use Thao\Rewards\Model\RewardFactory;
use Thao\Rewards\Model\Reward;
use Thao\Rewards\Model\ResourceModel\Reward\CollectionFactory;


class  OrderPendingObserver implements ObserverInterface
{
    protected $oder;

    protected $rewardFactory;

    public function __construct(
        RewardFactory $rewardFactory
    )
    {
        $this->rewardFactory = $rewardFactory;
    }

    public function execute(EventObserver $observer)
    {
        $order = $observer->getEvent()->getDataObject();
        $state = $order->getState();
        if($state=='pending'){
            $this->getRewardPoint($order);
        }
    }

    public function getRewardPoint(){

    }

}
