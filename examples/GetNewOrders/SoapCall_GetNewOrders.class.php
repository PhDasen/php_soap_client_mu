<?php

require_once ROOT.'lib/soap/call/PlentySoapCall.abstract.php';

/**
 * 
 * It might be a better idea to run this call via 
 * PlentySoap.daemon.php
 * So you can keep you local db/system up2date in an easy way
 * 
 */
class SoapCall_GetNewOrders extends PlentySoapCall 
{
	public function __construct()
	{
		parent::__construct(__CLASS__);
	}
	
	public function execute() 
	{
		try
		{
			$this->getLogger()->debug(__FUNCTION__.' start');
			
			/*
			 * do soap call
			 */
			$PlentySoapRequest_SearchOrders = new PlentySoapRequest_SearchOrders;
			$PlentySoapRequest_SearchOrders->OrderCreatedFrom = time() - 3600;
			$response	=	$this->getPlentySoap()->SearchOrders($PlentySoapRequest_SearchOrders);
			
			/*
			 * check soap response
			 */
			if( $response->Success == true )
			{
				$this->getLogger()->debug(__FUNCTION__.' Request Success - : SearchOrders');
				
				/*
				 * parse and save the data
				 */
				$this->parseResponse($response);
			}
			else
			{
				$this->getLogger()->debug(__FUNCTION__.' Request Error');
			}
		}
		catch(Exception $e)
		{
			$this->onExceptionAction($e);
		}
	}
	
	/**
	 * Parse the response
	 * 
	 * @param PlentySoapResponse_SearchOrders $response
	 */
	private function parseResponse($response)
	{
		if(is_array($response->Orders->item))
		{
			/*
			 * If more than one country of delivery
			 */
			foreach ($response->Orders->item as $Item)
			{
				$this->getLogger()->debug('Ordertimestamp: '.$Item->OrderHead->OrderTimestamp);
			
			}
		}
		/*
		 * only one country of delivery 
		 */
		elseif (is_object($response->Orders->item->OrderHead))
		{
			$this->getLogger()->debug('Ordertimestamp: '.$OrderHead->OrderTimestamp);
		}
	}
	
	/**
	 * Save the data in the database
	 * 
	 * @param PlentySoapObject_GetCountriesOfDelivery $countryOfDelivery
	 */
	private function saveInDatabase($countryOfDelivery)
	{
		$query = 'REPLACE INTO `plenty_countries_of_delivery` '.DBUtils::buildInsert(	array(	'country_id'	=>	$countryOfDelivery->CountryID,
																								'active'		=>	$countryOfDelivery->CountryActive,
																								'country_name'	=>	$countryOfDelivery->CountryName,
																								'iso_code_2'	=>	$countryOfDelivery->CountryISO2));
		
		$this->getLogger()->debug(__FUNCTION__.' '.$query);
		
		DBQuery::getInstance()->replace($query);
	}
}

?>