<?php 
class Yli_Recommend_Model_Observer
{
    public function orderCustomerProduct(Varien_Event_Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        $customer_id = $order->getCustomerId();
        if($customer_id){
            $items = $order->getAllItems();
            
            $redis = Mage::helper('redis')->init(3);
            
            foreach ($items as $item){
                $product_id = $item->getProductId();
                
                //商品集合
                $redis->sAdd('product_order'.$product_id,$customer_id);
                //用户集合
                $redis->sAdd('customer_order'.$customer_id,$product_id);
            }
        }
    }
    
    
    public function viewCustomerProduct(Varien_Event_Observer $observer)
    {
    	$product_id = $observer->getEvent()->getProduct()->getId();
    	$customer_id = Mage::getSingleton('customer/session')->getCustomerId();
    	
    	if($customer_id){
    		$redis = Mage::helper('redis')->init(4);
    		
    		//商品集合
    		$redis->sAdd('product_view'.$product_id,$customer_id);
    		//用户集合
    		$redis->sAdd('customer_view'.$customer_id,$product_id);
    	}
    }
}