<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Controller extends BaseController
{
	use AuthorizesRequests, ValidatesRequests;

	protected $data = [];
	protected $uploadsFolder = 'uploads/';

	protected $rajaOngkirApiKey = null;
	protected $rajaOngkirBaseUrl = null;
	protected $rajaOngkirOrigin = null;
	protected $couriers = [
		'jne' => 'JNE',
		'pos' => 'POS Indonesia',
		'tiki' => 'Titipan Kilat'
	];

	protected $provinces = [];

	public function __construct()
	{
		$this->rajaOngkirApiKey = '2472843d6a402ff2319489c07cc5cf73';
		$this->rajaOngkirBaseUrl = config('ongkir.base_url');
		$this->rajaOngkirOrigin = config('ongkir.origin');
		// dd($this->rajaOngkirApiKey, $this->rajaOngkirBaseUrl, $this->rajaOngkirOrigin);
		if (!$this->rajaOngkirApiKey || !$this->rajaOngkirBaseUrl || !$this->rajaOngkirOrigin) {
			throw new \Exception('RajaOngkir API key, base URL, or origin is not set.');
		}
	}

	protected function rajaOngkirRequest($resource, $params = [], $method = 'GET')
	{
		$client = new Client();

		$headers = ['key' => $this->rajaOngkirApiKey];
		$requestParams = [
			'headers' => $headers,
		];

		$url = $this->rajaOngkirBaseUrl . $resource;
		if ($params && $method == 'POST') {
			$requestParams['form_params'] = $params;
		} elseif ($params && $method == 'GET') {
			$query = is_array($params) ? '?' . http_build_query($params) : '';
			$url = $this->rajaOngkirBaseUrl . $resource . $query;
		}

		try {
			$response = $client->request($method, $url, $requestParams);
			return json_decode($response->getBody(), true);
		} catch (RequestException $e) {
			\Log::error('RajaOngkir API request error: ' . $e->getMessage());
			return null;
		}
	}

	protected function getProvinces()
	{
		$provinceFile = 'provinces.txt';
		$provinceFilePath = $this->uploadsFolder . 'files/' . $provinceFile;

		$isExistProvinceJson = \Storage::disk('local')->exists($provinceFilePath);

		if (!$isExistProvinceJson) {
			$response = $this->rajaOngkirRequest('province');
			if ($response && isset($response['rajaongkir']['results'])) {
				\Storage::disk('local')->put($provinceFilePath, serialize($response['rajaongkir']['results']));
			} else {
				\Log::error('Failed to fetch provinces from RajaOngkir');
				return [];
			}
		}

		$province = unserialize(\Storage::get($provinceFilePath));

		$provinces = [];
		if (!empty($province)) {
			foreach ($province as $prov) {
				$provinces[$prov['province_id']] = strtoupper($prov['province']);
			}
		}

		return $provinces;
	}

	protected function getCities($provinceId)
	{
		$cityFile = 'cities_at_' . $provinceId . '.txt';
		$cityFilePath = $this->uploadsFolder . 'files/' . $cityFile;

		$isExistCitiesJson = \Storage::disk('local')->exists($cityFilePath);

		if (!$isExistCitiesJson) {
			$response = $this->rajaOngkirRequest('city', ['province' => $provinceId]);
			if ($response && isset($response['rajaongkir']['results'])) {
				\Storage::disk('local')->put($cityFilePath, serialize($response['rajaongkir']['results']));
			} else {
				\Log::error('Failed to fetch cities from RajaOngkir');
				return [];
			}
		}

		$cityList = unserialize(\Storage::get($cityFilePath));

		$cities = [];
		if (!empty($cityList)) {
			foreach ($cityList as $city) {
				$cities[$city['city_id']] = strtoupper($city['type'] . ' ' . $city['city_name']);
			}
		}

		return $cities;
	}

	protected function getShippingCost($destination, $weight)
	{
		$params = [
			'origin' => $this->rajaOngkirOrigin,
			'destination' => $destination,
			'weight' => $weight,
		];

		$results = [];
		foreach ($this->couriers as $code => $courier) {
			$params['courier'] = $code;

			$response = $this->rajaOngkirRequest('cost', $params, 'POST');
			if ($response && isset($response['rajaongkir']['results'])) {
				foreach ($response['rajaongkir']['results'] as $cost) {
					if (!empty($cost['costs'])) {
						foreach ($cost['costs'] as $costDetail) {
							$serviceName = strtoupper($cost['code']) . ' - ' . $costDetail['service'];
							$costAmount = $costDetail['cost'][0]['value'];
							$etd = $costDetail['cost'][0]['etd'];

							$result = [
								'service' => $serviceName,
								'cost' => $costAmount,
								'etd' => $etd,
								'courier' => $code,
							];

							$results[] = $result;
						}
					}
				}
			} else {
				\Log::error('Failed to fetch shipping cost from RajaOngkir for courier ' . $courier);
			}
		}

		$response = [
			'origin' => $params['origin'],
			'destination' => $destination,
			'weight' => $weight,
			'results' => $results,
		];

		return $response;
	}

	protected function initPaymentGateway()
	{
		// Set your Merchant Server Key
		\Midtrans\Config::$serverKey = 'SB-Mid-server-BIK2lfIQpGfaMi3GxG5K9VLX';
		// Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
		\Midtrans\Config::$isProduction = false;
		// Set sanitization on (default)
		\Midtrans\Config::$isSanitized = true;
		// Set 3DS transaction for credit card to true
		\Midtrans\Config::$is3ds = true;
	}
}
