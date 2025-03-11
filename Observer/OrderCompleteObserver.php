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
     * @var Order
     */
    protected $order;


    /**
     * @var RewardFactory
     */
    protected $rewardFactory;

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @param Order $order
     * @param RewardFactory $rewardFactory
     * @param Data $helper
     */
    public function __construct(
        Order $order,
        RewardFactory $rewardFactory,
        Data $helper
    )
    {
        $this->order=$order;
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
            $this->getRewardPoint($order);
        }
    }

    /**
     * @param $subtotal
     * @return void
     */
    public function getRewardPoint($order)
    {
        $reWardAmount = ($order->getGrandTotal());
        $reWards = $this->rewardFactory->create();
        $reWards
            ->setAmount($reWardAmount)
            ->setComment("so diem duoc cong".$reWardAmount)
            ->setAction(Reward::ORDER_COMPLETED_ACTION)
            ->setPointLeft($this->helper->getOrderPointLeft($order,null) + $reWardAmount)
            ->setCustomerId($order->getCustomerId())
            ->save();
    }

    /**
     * @return void
     */
//    public function  getOrderPointLeft($order){
//
//        $rewardsCollection = $this->rewardCollectionFactory->create()
//            ->addFieldToFilter('customer_id',$order->getCustomerId());
//        $totalAmount = $rewardsCollection->getColumnValues('amount');
//        return array_sum($totalAmount);
//    }


}
