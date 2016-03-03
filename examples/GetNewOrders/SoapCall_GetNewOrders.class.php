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
			$PlentySoapRequest_SearchOrders->OrderCreatedFrom = time() - 360000000;
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
			 * Step through each order item
			 */
			foreach ($response->Orders->item as $Item)
			{
				
				$this->saveInDatabase($Item);
			}
		}
		/*
		 * Orders not Returned as Array
		 */
		else
		{
			$this->getLogger()->debug('Order Items not in Array');
		}
	}
	
	/**
	 * Save the data in the database
	 * 
	 * @param PlentySoapObject_SearchOrders $Item
	 */
	private function saveInDatabase($Item)
	{
		$query = 'REPLACE INTO `plenty_OrderHead` '.DBUtils::buildInsert(	array(	'OrderID'					=>	$Item->OrderHead->OrderID,
																					'Currency'					=>	$Item->OrderHead->Currency,
																					'CustomerID'				=>	$Item->OrderHead->CustomerID,
																					'CustomerReference'			=>	$Item->OrderHead->CustomerReference,
																					'DeliveryAddressID'			=>	$Item->OrderHead->DeliveryAddressID,
																					'DoneTimestamp'				=>	$Item->OrderHead->DoneTimestamp,
																					'DunningLevel'				=>	$Item->OrderHead->DunningLevel,
																					'EbaySellerAccount'			=>	$Item->OrderHead->EbaySellerAccount,
																					'EstimatedTimeOfShipment'	=>	$Item->OrderHead->EstimatedTimeOfShipment,
																					'ExchangeRatio'				=>	$Item->OrderHead->ExchangeRatio,
																					'ExternalOrderID'			=>	$Item->OrderHead->ExternalOrderID,
																					'Invoice'					=>	$Item->OrderHead->Invoice,
																					'IsNetto'					=>	$Item->OrderHead->IsNetto,
																					'LastUpdate'				=>	$Item->OrderHead->LastUpdate,
																					'MethodOfPaymentID'			=>	$Item->OrderHead->MethodOfPaymentID,
																					'OrderStatus'				=>	$Item->OrderHead->OrderStatus,
																					'OrderTimestamp'			=>	$Item->OrderHead->OrderTimestamp,
																					'OrderType'					=>	$Item->OrderHead->OrderType,
																					'PackageNumber'				=>	$Item->OrderHead->PackageNumber,
																					'PaidTimestamp'				=>	$Item->OrderHead->PaidTimestamp,
																					'ParentOrderID'				=>	$Item->OrderHead->ParentOrderID,
																					'PaymentStatus'				=>	$Item->OrderHead->PaymentStatus,
																					'ReferrerID'				=>	$Item->OrderHead->ReferrerID,
																					'RemoteIP'					=>	$Item->OrderHead->RemoteIP,
																					'ResponsibleID'				=>	$Item->OrderHead->ResponsibleID,
																					'SalesAgentID'				=>	$Item->OrderHead->SalesAgentID,
																					'SellerAccount'				=>	$Item->OrderHead->SellerAccount,
																					'ShippingCosts'				=>	$Item->OrderHead->ShippingCosts,
																					'ShippingID'				=>	$Item->OrderHead->ShippingID,
																					'ShippingMethodID'			=>	$Item->OrderHead->ShippingMethodID,
																					'ShippingProfileID'			=>	$Item->OrderHead->ShippingProfileID,
																					'StoreID'					=>	$Item->OrderHead->StoreID,
																					'TotalBrutto'				=>	$Item->OrderHead->TotalBrutto,
																					'TotalInvoice'				=>	$Item->OrderHead->TotalInvoice,
																					'TotalNetto'				=>	$Item->OrderHead->TotalNetto,
																					'TotalVAT'					=>	$Item->OrderHead->TotalVAT,
																					'WarehouseID'				=>	$Item->OrderHead->WarehouseID));
		
		$this->getLogger()->debug(__FUNCTION__.' '.$query);
		
		DBQuery::getInstance()->replace($query);
		
		if (isset($Item->OrderHead->IncomingPayments))
		{
			foreach ($Item->OrderHead->IncomingPayments->item as $ItemIncomingPayments)
			{
				$query = 'REPLACE INTO `plenty_OrderHead_IncomingPayments` '.DBUtils::buildInsert(	array(	'ID'			=>	$ItemIncomingPayments->ID,
																											'ReferenceID'	=>	$ItemIncomingPayments->ReferenceID,
																											'OrderID'		=>	$Item->OrderHead->OrderID));
				
				$this->getLogger()->debug(__FUNCTION__.' '.$query);
				
				DBQuery::getInstance()->replace($query);
			}
		}
		
		if (isset($Item->OrderHead->IncomingPayments))
		{
			foreach ($Item->OrderHead->IncomingPayments->item as $ItemIncomingPayments)
			{
				$query = 'REPLACE INTO `plenty_OrderHead_IncomingPayments` '.DBUtils::buildInsert(	array(	'ID'			=>	$ItemIncomingPayments->ID,
						'ReferenceID'	=>	$ItemIncomingPayments->ReferenceID,
						'OrderID'		=>	$Item->OrderHead->OrderID));
		
				$this->getLogger()->debug(__FUNCTION__.' '.$query);
		
				DBQuery::getInstance()->replace($query);
			}
		}
	}
}

?>