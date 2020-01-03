<?php 
namespace App\Contracts;

interface RoutingInterface {

    /**
     * Get the distance between 2 cooridnates
     *
     * @param  float  $lat1
     * @param  float  $lang1
     * @param  float $lat2
     * @param  float $lang2
     * @return distance in meter
     */
    public function getDistance($lat1, $lang1, $lat2, $lang2);

}