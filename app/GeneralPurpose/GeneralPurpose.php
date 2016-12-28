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

    public function getSql($builder)
    {
        ////////////////////////////////////////////////////////////////////////////////////////////////
        // How to use
        // In the controller, make new instance and use this function with query before using "->get()";
        //$sql = new GeneralPurpose;
        //$sql = $sql->getSql($memberList);
        //dd($sql);
        /////////////////////////////////////////////////////////////////////////////////////////////////

        //$builder = $this->getBuilder();
        $sql = $builder->toSql();
        foreach ($builder->getBindings() as $binding) {
            $value = is_numeric($binding) ? $binding : "'" . $binding . "'";
            $sql = preg_replace('/\?/', $value, $sql, 1);
            $sql = preg_replace('/\r/', '', $sql);
            $sql = preg_replace('/\n/', '', $sql);
        }
        return $sql;
    }

    public function searchByMemberName($memberList, $getMemberName)
    {

        ////////////////////////////////////////////////////////
        // $memberList query using tbMbrMember ( aliased as 'member')
        // $memberName is the member name ('first Name', 'last name', 'first and last Name', alias or chunmyung Name or etc)

        // remove white space from the starting and ending of $getMemberName
        $getMemberName = trim($getMemberName);

        // the input Member name into two splits ( first Name and last Name )
        $getMemberNameSplit = explode(' ', $getMemberName);
        $getMemberNameSplit01 = trim($getMemberNameSplit[0]);

        if ($getMemberNameSplit[0] == '') {

        } elseif (isset($getMemberNameSplit[1])) {
            // trim last name and store it.
            $getMemberNameSplit02 = trim($getMemberNameSplit[1]);
            $memberList = $memberList
                ->where('firstNm', 'LIKE', '%' . $getMemberNameSplit01 . '%')
                ->Where('lastNm', 'LIKE', '%' . $getMemberNameSplit02 . '%')
            ;
        } else {
            // single word in the search box
            $memberList = $memberList
                ->where(function ($query) use ($getMemberNameSplit01) {
                    $query->where('firstNm', 'LIKE', '%' . $getMemberNameSplit01 . '%')
                        ->orWhere('lastNm', 'LIKE', '%' . $getMemberNameSplit01 . '%');
                });

        } // if there are two words

        return $memberList;
    }


}