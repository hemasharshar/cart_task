<?php

namespace App\Http\Controllers;

use Response;


/**
 * This class should be parent class for other API controllers
 * Class AppBaseController
 */
class AppBaseController extends Controller
{

    public function sendApiResponse($result, $message)
    {
        $result["message"] = $message;
        $result["success"] = true;
        return Response::json($result);
    }

    public function sendApiError($error, $code = 404, $errorCode = 0)
    {
        if($errorCode == 1){
            return Response::json(array("error" => $error, "success" => false, 'error_code' => 1), $code);
        }else{
            return Response::json(array("error" => $error, "success" => false), $code);
        }
    }

    /**
     * Get currency rate.
     * @param $url
     * @return mixed
     */
    public function getUserCurrency($url)
    {
        $result = $this->curl_api($url);

        return $result['result'];
    }

    /**
     * Get required data from outSide using curl
     * @param $url
     * @return array of required data
     */
    private function curl_api($url)
    {
        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        $output = curl_exec($handle);
        curl_close($handle);
        return json_decode($output, TRUE);
    }
}
