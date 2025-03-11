<?php
namespace Thao\Rewards\Helper;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Sales\Model\Order;
use Magento\Store\Model\ScopeInterface;
use Thao\Rewards\Model\ResourceModel\Reward\CollectionFactory;
use Thao\Rewards\Model\Reward;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Config\ScopeConfigInterface;

Class Data extends AbstractHelper
{
    protected $rewardCollectionFactory;

    protected $checkoutSession;

    protected $scopeConfig;


    public function __construct(

        CollectionFactory $rewardCollectionFactory,
        CheckoutSession $checkoutSession,
        ScopeConfigInterface $scopeConfig,
    ){
        $this->rewardCollectionFactory = $rewardCollectionFactory;
        $this->checkoutSession = $checkoutSession;
        $this->scopeConfig = $scopeConfig;
    }

    public function getOrderPointLeft($order = null, $quote = null)
    {
        if (!$order && !$quote) {
            return 0;
        }
        if (!$order && $quote) {
            $customerId = $quote->getCustomerId();
        } else {
            $customerId = $order->getCustomerId();
        }

        $rewardsCollection = $this->rewardCollectionFactory->create()
            ->addFieldToFilter('customer_id', $customerId)
            ->setOrder('action_date','DESC');
        if(!$rewardsCollection->getSize()){
            return  0;
        }
            $rewardPointFist = $rewardsCollection->getFirstItem();
        return $rewardPointFist->getPointLeft();

    }

    public function enabledRewardPoint() {
        $ValueConfig = $this->scopeConfig->getValue('rewards_point/general/enable',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    public function getRatePoint() {
     $ratePoint = $this->scopeConfig->getValue('rewards_point/general/conversion_rate',
         \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }
}
