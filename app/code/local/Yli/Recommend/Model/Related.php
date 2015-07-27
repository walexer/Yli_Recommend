<?php 
class Yli_Recommend_Model_Related
{
	public function init()
	{
		//Who Bought This Also Bought 
		$this->initBab();
		//Who Viewed This Also Viewed
		$this->initVav();
	}
	
	private function initBab()
	{
		$redis = Mage::helper('redis')->init(3);
		$products = Mage::getModel('catalog/product')->getCollection()
					->addAttributeToFilter('status',1)
					->getAllIds();
		
		foreach ($products as $product_id)
		{
			$customers = $redis->sMembers('product_order'.$product_id);
			foreach ($customers as $customer_id){
				$product_ids = $redis->sMembers('customer_order'.$customer_id);
				foreach ($product_ids as $pid){
				    $redis->zIncrby('product_bab'.$product_id,1,$pid);
				}
			}
		}
		
	}
	
	private function initVav()
	{
	    $redis = Mage::helper('redis')->init(3);
	    $products = Mage::getModel('catalog/product')->getCollection()
        	    ->addAttributeToFilter('status',1)
        	    ->getAllIds();
	    
	    foreach ($products as $product_id)
	    {
	        $customers = $redis->sMembers('product_view'.$product_id);
	        foreach ($customers as $customer_id){
	            $product_ids = $redis->sMembers('customer_view'.$customer_id);
	            foreach ($product_ids as $pid){
	                $redis->zIncrby('product_vav'.$product_id,1,$pid);
	            }
	        }
	    }
	}
	
	
}