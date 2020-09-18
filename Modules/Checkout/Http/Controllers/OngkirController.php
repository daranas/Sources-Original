<?php

namespace Modules\Checkout\Http\Controllers;

use Illuminate\Http\Request;

class OngkirController
{

    public function __construct()
    {
        $this->API_ONGKIR = env('RAJAONGKIR_URL');
        $this->ORIGIN_ONGKIR = env('RAJAONGKIR_ORIGIN');
    }

    public function setAPI($method, $url, $data)
    {
        $curl = curl_init();
        switch ($method){
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_ENCODING, "");
        curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'key:' . env('RAJAONGKIR_KEY'),
            'Content-Type: application/json',
        ));

        $result = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        if ($err) {
            die("Connection Failure");
        } else {
            $response = json_decode($result, true);
            return response()->json($response['rajaongkir']['results']);
        }
    }

    // get cost
    public function getCost(Request $request)
    {
        $costData = array();
        $courier = array('jne');
        foreach ($courier as $getCourier) {
            $data =  array(
                'origin' => $this->ORIGIN_ONGKIR,
                'destination' => $request->get('destination'),
                'weight' => $request->get('weight'),
                'courier' => $getCourier,
            );
            $result = $this->setAPI('POST', $this->API_ONGKIR . 'cost', json_encode($data));
            $costData[] = $result->original[0];
        }
        return $costData;
    }

    // get province
    public function getProvince()
    {
        return $this->setAPI('GET', $this->API_ONGKIR . 'province', false);
    }

    // get city
    public function getCity(Request $request)
    {
        $provinceId = '';
        if ($request->has('province')) {
            $provinceId = '?province=' . $request->get('province');
        }

        return $this->setAPI('GET', $this->API_ONGKIR . 'city' . $provinceId, false);
    }

}
