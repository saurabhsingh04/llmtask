<?php

namespace App\Http\Services;
use App\Http\Exceptions\OrderException;
use App\Contracts\RoutingInterface;
use Log;
/**
 * Routing services functions
 */
class GoogleDistanceService implements RoutingInterface
{
    public function getDistance($lat1, $lang1, $lat2, $lang2)
    {
        try {
            $endpoint = config('services.distance.endpoint');
            $key = config('services.distance.key');
            $url = $endpoint.'?origins=12.dsas,'.$lang1.'&destinations='.$lat2.','.$lang2.'&key='.$key;
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', $url);
            $response = json_decode($response->getBody(), true);
            if(is_array($response) && isset($response['status']) && strtolower($response['status'])=='ok')
            {
                return $response['rows'][0]['elements'][0]['distance']['value'];
            } else {
                $msg = is_array($response) && $response['error_message'] ? $response['error_message'] : "Error Processing Request"; 
                throw new OrderException('DISTANCE_API_ERROR', 503, $msg);
            }
        } catch (OrderException $exc) {
            throw $exc;
        } catch (\Throwable $th) {
            $code = $th->getCode() ? $th->getCode() : 500;
            Log::error($th->getMessage());
            throw new OrderException('DISTANCE_API_ERROR', $code, "Something went wrong");   
        }
    }
}