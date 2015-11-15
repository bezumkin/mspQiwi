<?php


class qiwiSOAPServer {
	/** @var modX $modx */
	public $modx;
	public $login = '';
	public $password = '';


	public function __construct($login, $password) {
		global $modx;
		$this->modx = $modx;

		$this->login = $login;
		$this->password = $password;
	}


	/**
	 * @param $response
	 *
	 * @return qiwiResponse
	 */
	function updateBill($response) {
		/** @var Qiwi $handler */
		$handler = new Qiwi(new msOrder($this->modx));

		if (!$this->checkSignature($response->txn, $response->password)) {
			$code = 330;
		}
		else {
			$txn = str_replace('_TEST_', '', $response->txn);
			/** @var msOrder $order */
			if ($order = $this->modx->getObject('msOrder', $txn)) {
				$bill = $this->checkBill($txn);
				// Wrong amount in bill
				if ($bill->amount != $order->get('cost')) {
					$code = 300;
				}
				// Wrong status
				elseif ($response->status != $bill->status) {
					$code = 300;
				}
				// Processing
				elseif ($response->status >= 50 && $response->status < 60) {
					$code = 13;
				}
				// Success
				elseif ($response->status == 60) {
					$handler->receive($order, 2);
					$code = 0;
				}
				// Cancel
				elseif ($response->status >= 100) {
					$handler->receive($order, 4);
					$code = 0;
				}
				// Unknown
				else {
					$code = 300;
				}
			}
			// Could not find an order
			else {
				$code = 210;
			}
		}

		if ($code != 0) {
			$message = $handler->responseCode($code);
			$this->modx->log(modX::LOG_LEVEL_ERROR, "[mspQiwi] Could not process payment \"{$response->txn}\": code \"{$code}\", message: \"{$message}\".");
			$this->modx->log(modX::LOG_LEVEL_INFO, "[mspQiwi] Response: " . json_encode($response));
			if (!empty($bill)) {
				$this->modx->log(modX::LOG_LEVEL_INFO, "[mspQiwi] Bill: " . json_encode($bill));
			}
		}

		/** @var qiwiResponse $result */
		$result = new qiwiResponse();
		$result->updateBillResult = $code;

		return $result;
	}


	/**
	 * @param $txn
	 * @param $signed
	 *
	 * @return bool
	 */
	function checkSignature($txn, $signed) {
		$signature = strtoupper(md5($txn . strtoupper(md5($this->password))));

		return $signed == $signature;
	}


	/**
	 * @param $txn
	 *
	 * @return checkBillResponse
	 */
	function checkBill($txn) {
		if (!class_exists('IShopServerWSService')) {
			require dirname(__FILE__) . '/IShopServerWSService.php';
		}
		$service = new IShopServerWSService(dirname(__FILE__) . '/IShopServerWS.wsdl', array(
			'location' => 'http://ishop.qiwi.ru/services/ishop',
		));
		$params = new checkBill();
		$params->txn = $txn;
		$params->login = $this->login;
		$params->password = $this->password;

		return $service->checkBill($params);
	}

}

class qiwiResponse {
	public $updateBillResult;
}

class qiwiParam {
	public $login;
	public $password;
	public $txn;
	public $status;
}