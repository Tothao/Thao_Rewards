<?php
namespace Thao\Rewards\Observer;
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Session;
use Thao\Rewards\Model\RewardFactory;
use Thao\Rewards\Model\Reward;
use Thao\Rewards\Helper\Data;


class  SaveRewardPointsObserver implements ObserverInterface
{
    /**
     * @var Session
     */
    protected $checkoutSession;

    /**
     * @var RewardFactory
     */
    protected $rewardFactory;

    protected $helper;

    /**
     * @param RewardFactory $rewardFactory
     * @param Session $checkoutSession
     */
    public function __construct(
        RewardFactory $rewardFactory,
        Session $checkoutSession,
        Data $helper
    )
    {
        $this->rewardFactory = $rewardFactory;
        $this->checkoutSession = $checkoutSession;
        $this->helper = $helper;
    }

    /**
     * @param EventObserver $observer
     * @return void
     */
    public function execute(EventObserver $observer)
    {
        $quote = $this->checkoutSession->getQuote();
        $rewardPointsUsed = $quote->getRewardPointUsed();
        $order = $observer->getEvent()->getOrder();
        $order->setRewardPointUsed($rewardPointsUsed);
        if($rewardPointsUsed){
            $reWards = $this->rewardFactory->create();
            $reWards
                ->setAmount(-$rewardPointsUsed)
                ->setComment("Used point: ".$rewardPointsUsed)
                ->setAction(Reward::REWARDS_SPEND_ACTION)
                ->setPointLeft($this->helper->getOrderPointLeft($order, null) - $rewardPointsUsed)
                ->setCustomerId($order->getCustomerId())
                ->save();
        }

    }

}
