<?php
namespace Thao\Rewards\Model\Total\Quote;
use Thao\Rewards\Helper\Data;

class RewardPoint extends \Magento\Quote\Model\Quote\Address\Total\AbstractTotal
{
    protected $eventManager;

    protected $calculator;

    protected $storeManager;

    protected $priceCurrency;

    protected $helper;
    public function __construct
    (
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\SalesRule\Model\Validator $validator,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Thao\Rewards\Helper\Data $helper

    )
    {
        $this->setCode('testdiscount');
        $this->eventManager = $eventManager;
        $this->calculator = $validator;
        $this->storeManager = $storeManager;
        $this->priceCurrency = $priceCurrency;
        $this->helper = $helper;
    }

    public function collect(
        \Magento\Quote\Model\Quote $quote,
        \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment,
        \Magento\Quote\Model\Quote\Address\Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);
        $rewardPointUsed = $quote->getData('reward_point_used');
        $rate = $this->helper->getRatePoint();
        $totalDiscountByRewardPoint = $rewardPointUsed/$rate;

        if (!$totalDiscountByRewardPoint) {
            return $this;
        }
        $discountAmount =-$totalDiscountByRewardPoint;


            $total->addTotalAmount('discount', $discountAmount);
            $total->addBaseTotalAmount('discount', $discountAmount);
            $total->setSubtotalWithDiscount($total->getSubtotal() + $total->getDiscountAmount());
            $total->setBaseSubtotalWithDiscount($total->getBaseSubtotal() + $total->getBaseDiscountAmount());
        return $this;

//        // Số tiền giảm giá từ điểm thưởng
//        $discountAmountFromPoints = -$totalDiscountByRewardPoint;
//
//        // Lấy số tiền giảm giá hiện tại (từ các giảm giá khác)
//        $currentDiscountAmount = $total->getDiscountAmount();
//
//        // Cập nhật lại tổng số tiền giảm giá
//        $total->setDiscountAmount($currentDiscountAmount + $discountAmountFromPoints);
//        $total->setBaseDiscountAmount($total->getBaseDiscountAmount() + $discountAmountFromPoints);
//
//        // Cập nhật subtotal sau khi giảm giá
//        $total->setSubtotalWithDiscount($total->getSubtotal() + $total->getDiscountAmount());
//        $total->setBaseSubtotalWithDiscount($total->getBaseSubtotal() + $total->getBaseDiscountAmount());
//
//        // Thêm số tiền giảm giá từ điểm thưởng vào tổng số tiền giảm giá
//        $total->addTotalAmount('discount', $discountAmountFromPoints);
//        $total->addBaseTotalAmount('discount', $discountAmountFromPoints);

        return $this;

    }

    /**
     * Text data
     *
     * @param  \Magento\Quote\Model\Quote $quote
     * @param  Address\Total              $total
     * @return array|null
     */
    public function fetch(\Magento\Quote\Model\Quote  $quote, \Magento\Quote\Model\Quote\Address\Total $total)
    {
        $rate = $this->helper->getRatePoint();
        $rewardPointUsed = $quote->getData('reward_point_used');
        $totalDiscountByRewardPoint = $rewardPointUsed/$rate;
        $value = $totalDiscountByRewardPoint;
        $result = null;
        if ($value != 0) {
            $result = [
                'code' => $this->getCode(),
                'title' => __('Rewards Point'),
                'value' => -$value
            ];
        }

        return $result;
    }

}
