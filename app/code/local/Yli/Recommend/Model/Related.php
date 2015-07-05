<?php 
class Yli_Recommend_Model_Related
{
	public function init()
	{
		//Who Bought This Also Bought 
		$this->initBab();
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
				$product_ids = $redis->sMembers('customer_order'.$product_id);
				
			}
		}
		
	}
}