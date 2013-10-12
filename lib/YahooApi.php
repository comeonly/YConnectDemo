<?php
/**
 * YConnect YahooApi class.
 *
 * PHP 5
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Atunori Kamori (https://github.com/comeonly/)
 * @link          https://github.com/comeonly/
 * @package       YConnect.Lib
 * @since         YConnect 0.1
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 **/

include_once 'lib/CurlUtility.php';

/**
 * Class YahooCurl
 *
 * @package       YConnect.Lib
 * @since         YConnect 0.1
 */
class YahooApi extends CurlUtility {

/**
 * itemList method
 *
 * @param array  $settings curl and yahoo settings
 * @param array  $type     list type 'sold', 'not_sold' or 'selling'
 * @param string $token    oauth token
 * @param int    $page     page number
 * @return array
 * @author Atunori Kamori <atunori.kamori@gmail.com>
 */
	public function itemList($settings, $type, $token, $page = 1) {
		self::initialize($settings);
		curl_setopt($this->curlHandler, CURLOPT_HTTPHEADER, array(
			'Authorization: Bearer ' . $token . "\n"
		));
		if (($type === 'sold') || ($type === 'not_sold')) {
			$url = 'https://auctions.yahooapis.jp/AuctionWebService/V2/myCloseList?';
			$query = array(
				'output' => 'php',
				'start' => $page,
				'list' => $type === 'sold' ? 'sold' : 'not_sold'
			);
		} else {
			$url = 'https://auctions.yahooapis.jp/AuctionWebService/V2/mySellingList?';
			$query = array(
				'output' => 'php',
				'start' => $page,
			);
		}

		$httpQuery = http_build_query($query);
		$result = unserialize(self::getBody($url . $httpQuery));
		self::finalize($settings);
		return $result;
	}
}
