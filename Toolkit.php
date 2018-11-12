<?php
class Toolkit {
	public static function getCoords($address)
	{
	    if (!$address) {
	    	return false; 
		}
		$address = preg_replace("/ /i", "%20", "Санкт-Петербург" . ',' . $address);
		$json = file_get_contents("https://geocode-maps.yandex.ru/1.x/?format=json&geocode=" . $address);
		$data = json_decode($json);

		$presicion = $data->response->GeoObjectCollection->featureMember[0]->GeoObject->metaDataProperty->GeocoderMetaData->precision;
		$pos = explode(" ", $data->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos);

		$result['lat'] = $pos[1];
		$result['lng'] = $pos[0];
		return $result;
	}
	
	public static function getFormattedPhone($phone)
	{
		$phone = preg_replace("[^0-9]", '', $phone); 
	    if (strlen($phone) != 10) {
	    	return false; 
		}
	    $sArea = substr($phone, 0, 3); 
	    $sPrefix = substr($phone, 3, 3); 
	    $sNumber = substr($phone, 6, 4); 
	    $phone = "+7" . "(" . $sArea . ")" . $sPrefix . "-" . $sNumber; 
	    return $phone;
	}
}
	