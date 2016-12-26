<?php

namespace App\GeneralPurpose;
/**
 * Created by PhpStorm.
 * User: luke
 * Date: 12/26/16
 * Time: 10:55 AM
 */
use Carbon\Carbon;
use DateTime;
use DateInterval;
use DatePeriod;

class GeneralPurpose
{

    public function getDatesFromRange($dateFrom, $dateTo)
    {
        $dateFrom = Carbon::parse($dateFrom);
        $dateTo = Carbon::parse($dateTo);

        $begin = new DateTime( $dateFrom );
        $end = new DateTime( $dateTo );

        $interval = new DateInterval('P1D');
        $dateRange = new DatePeriod($begin, $interval ,$end);

        $dateArray = [];

        foreach($dateRange as $date)
        {
            $dateArray[] = $date->format('Ymd');
        }

        // adding last day ( $dateTo ) to the array
        $dateArray[] = Carbon::parse($dateTo)->format('Ymd');

        return $dateArray;
    }


}