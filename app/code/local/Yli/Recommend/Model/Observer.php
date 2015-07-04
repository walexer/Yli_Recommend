<?php 
class Yli_Recommend_Model_Observer
{
    public function initCustomerProduct(Varien_Event_Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $customer_id = $order->getCustomerId();
        if($customer_id){
            $items = $order->getAllItems();
            
            $redis = Mage::helper('redis')->init(3);
            
            foreach ($items as $item){
                $product_id = $item->getProductId();
                
                //商品集合
                $redis->sAdd('product_'.$product_id,$customer_id);
                //用户集合
                $redis->sAdd('customer_'.$customer_id,$product_id);
            }
        }
    }
}