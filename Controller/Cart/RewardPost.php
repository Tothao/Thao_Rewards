<?php
namespace Thao\Rewards\Controller\Cart;

use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Checkout\Model\Cart;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\SalesRule\Model\CouponFactory;
use Thao\Rewards\Helper\Data;

class RewardPost extends \Magento\Checkout\Controller\Cart
{
    protected $quoteRepository;
    protected $couponFactory;

    protected $checkoutSession;
    protected $helper;

    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        CheckoutSession $checkoutSession,
        StoreManagerInterface $storeManager,
        Validator $formKeyValidator,
        Cart $cart,
        CouponFactory $couponFactory,
        CartRepositoryInterface $quoteRepository,
        Data $helper
    ) {
        parent::__construct($context, $scopeConfig, $checkoutSession, $storeManager, $formKeyValidator, $cart);
        $this->couponFactory = $couponFactory;
        $this->quoteRepository = $quoteRepository;
        $this->checkoutSession = $checkoutSession;
        $this->helper = $helper;
    }

    public function execute()
    {
        $quote = $this->checkoutSession->getQuote();
        $remove = (int) $this->getRequest()->getParam('remove');

        if ($remove === 1) {
            $quote->setRewardPointUsed(0);
            $this->messageManager->addSuccessMessage(__('Reward points removed.'));
        } else {
            $rewardPoints = (int) $this->getRequest()->getParam('reward_point');
            $pointLeft = $this->helper->getOrderPointLeft(null, $quote);
            if($rewardPoints>$pointLeft){
                $this->messageManager->addErrorMessage(__('You only have %1 reward points left.', $pointLeft));
                return $this->_redirect('checkout/cart');
            }
//
//            $total = $quote->getGrandTotal();
//            if($rewardPoints > $total*100){
//                $rewardPoints = $total*100;
//            }

            if ($rewardPoints > 0) {
                $quote->setRewardPointUsed($rewardPoints);
                $this->messageManager->addSuccessMessage(__('Reward points applied: %1', $rewardPoints));
            } else {
                $this->messageManager->addErrorMessage(__('Please enter a valid reward point amount.'));
            }
        }

        $quote->save();
        return $this->_redirect('checkout/cart');
    }


}
