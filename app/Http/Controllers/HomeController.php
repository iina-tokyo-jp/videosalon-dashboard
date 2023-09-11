<?php
namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index() {
        // 認証情報取得
        $auth = Auth::user();

        // 日付 - 一週間分
        $startDate = date('Y-m-d');
        for ($i = 0; $i < 7; $i++) {
            $days[] = date('n/j', strtotime($startDate . '-' . ($i + 1) . 'days'));
        }
/*
        // 決済額(、決済額累計)
        $sql  = "SELECT ";
        $sql .= "  SUM(CASE WHEN date_trunc('day', add_date) < current_date + integer '-7' AND date_trunc('month', add_date) = date_trunc('month', (current_date + integer '-7')) THEN amount ELSE 0 END) AS ruikei, ";
        $sql .= "  SUM(CASE WHEN date_trunc('day', add_date) = current_date + integer '-1' THEN amount ELSE 0 END) AS pay_0, ";
        $sql .= "  SUM(CASE WHEN date_trunc('day', add_date) = current_date + integer '-2' THEN amount ELSE 0 END) AS pay_1, ";
        $sql .= "  SUM(CASE WHEN date_trunc('day', add_date) = current_date + integer '-3' THEN amount ELSE 0 END) AS pay_2, ";
        $sql .= "  SUM(CASE WHEN date_trunc('day', add_date) = current_date + integer '-4' THEN amount ELSE 0 END) AS pay_3, ";
        $sql .= "  SUM(CASE WHEN date_trunc('day', add_date) = current_date + integer '-5' THEN amount ELSE 0 END) AS pay_4, ";
        $sql .= "  SUM(CASE WHEN date_trunc('day', add_date) = current_date + integer '-6' THEN amount ELSE 0 END) AS pay_5, ";
        $sql .= "  SUM(CASE WHEN date_trunc('day', add_date) = current_date + integer '-7' THEN amount ELSE 0 END) AS pay_6 ";
        $sql .= "FROM ";
        $sql .= "  point_orders ";
        $sql .= "WHERE ";
        $sql .= "  status = 1 ";
        $sql .= "AND ";
        $sql .= "  add_date >= date_trunc('month', current_date + interval '-1 month')::timestamp ";
        $sql .= "AND ";
        $sql .= "  site_id = 1 ";
error_log($sql);
        $pay_total = DB::select($sql)[0];

        // 入会者数(、入会者数累計)
        $sql  = "SELECT ";
        $sql .= "  SUM(CASE WHEN date_trunc('day', add_date) < current_date + integer '-7' THEN 1 ELSE 0 END) AS ruikei, ";
        $sql .= "  SUM(CASE WHEN date_trunc('day', add_date) = current_date + integer '-1' THEN 1 ELSE 0 END) AS cnt_0, ";
        $sql .= "  SUM(CASE WHEN date_trunc('day', add_date) = current_date + integer '-2' THEN 1 ELSE 0 END) AS cnt_1, ";
        $sql .= "  SUM(CASE WHEN date_trunc('day', add_date) = current_date + integer '-3' THEN 1 ELSE 0 END) AS cnt_2, ";
        $sql .= "  SUM(CASE WHEN date_trunc('day', add_date) = current_date + integer '-4' THEN 1 ELSE 0 END) AS cnt_3, ";
        $sql .= "  SUM(CASE WHEN date_trunc('day', add_date) = current_date + integer '-5' THEN 1 ELSE 0 END) AS cnt_4, ";
        $sql .= "  SUM(CASE WHEN date_trunc('day', add_date) = current_date + integer '-6' THEN 1 ELSE 0 END) AS cnt_5, ";
        $sql .= "  SUM(CASE WHEN date_trunc('day', add_date) = current_date + integer '-7' THEN 1 ELSE 0 END) AS cnt_6 ";
        $sql .= "FROM ";
        $sql .= "  users ";
        $sql .= "WHERE ";
        $sql .= "  site_id = 1 ";


//$sql = "SELECT * FROM users WHERE add_date = "
error_log($sql);
        $user_total = DB::select($sql)[0];

        // 売上額(、売上額累計)
        $sql  = "SELECT ";
        $sql .= "  SUM(CASE WHEN date_trunc('day', created_at) < current_date + integer '-7' AND date_trunc('month', created_at) = date_trunc('month', (current_date + integer '-7')) THEN sales_wtax ELSE 0 END) AS ruikei, ";
        $sql .= "  SUM(CASE WHEN date_trunc('day', created_at) = current_date + integer '-1' THEN sales_wtax ELSE 0 END) AS sales_0, ";
        $sql .= "  SUM(CASE WHEN date_trunc('day', created_at) = current_date + integer '-2' THEN sales_wtax ELSE 0 END) AS sales_1, ";
        $sql .= "  SUM(CASE WHEN date_trunc('day', created_at) = current_date + integer '-3' THEN sales_wtax ELSE 0 END) AS sales_2, ";
        $sql .= "  SUM(CASE WHEN date_trunc('day', created_at) = current_date + integer '-4' THEN sales_wtax ELSE 0 END) AS sales_3, ";
        $sql .= "  SUM(CASE WHEN date_trunc('day', created_at) = current_date + integer '-5' THEN sales_wtax ELSE 0 END) AS sales_4, ";
        $sql .= "  SUM(CASE WHEN date_trunc('day', created_at) = current_date + integer '-6' THEN sales_wtax ELSE 0 END) AS sales_5, ";
        $sql .= "  SUM(CASE WHEN date_trunc('day', created_at) = current_date + integer '-7' THEN sales_wtax ELSE 0 END) AS sales_6 ";
        $sql .= "FROM ";
        $sql .= "  appraiser_aggregates ";
        $sql .= "WHERE ";
        $sql .= "  created_at >= date_trunc('month', current_date + interval '-1 month')::timestamp ";
        $sql .= "AND ";
        $sql .= "  site_id = 1 ";
error_log($sql);
        $sales_total = DB::select($sql)[0];

        // 広告費(、広告費累計)
        $sql  = "SELECT ";
        $sql .= "  SUM(CASE WHEN date_trunc('day', created_at) < current_date + integer '-7' AND date_trunc('month', created_at) = date_trunc('month', (current_date + integer '-7')) THEN total_adcosts ELSE 0 END) AS ruikei, ";
        $sql .= "  SUM(CASE WHEN date_trunc('day', created_at) = current_date + integer '-1' THEN total_adcosts ELSE 0 END) AS adcosts_0, ";
        $sql .= "  SUM(CASE WHEN date_trunc('day', created_at) = current_date + integer '-2' THEN total_adcosts ELSE 0 END) AS adcosts_1, ";
        $sql .= "  SUM(CASE WHEN date_trunc('day', created_at) = current_date + integer '-3' THEN total_adcosts ELSE 0 END) AS adcosts_2, ";
        $sql .= "  SUM(CASE WHEN date_trunc('day', created_at) = current_date + integer '-4' THEN total_adcosts ELSE 0 END) AS adcosts_3, ";
        $sql .= "  SUM(CASE WHEN date_trunc('day', created_at) = current_date + integer '-5' THEN total_adcosts ELSE 0 END) AS adcosts_4, ";
        $sql .= "  SUM(CASE WHEN date_trunc('day', created_at) = current_date + integer '-6' THEN total_adcosts ELSE 0 END) AS adcosts_5, ";
        $sql .= "  SUM(CASE WHEN date_trunc('day', created_at) = current_date + integer '-7' THEN total_adcosts ELSE 0 END) AS adcosts_6 ";
        $sql .= "FROM ";
        $sql .= "  ad_aggregates ";
        $sql .= "WHERE ";
        $sql .= "  created_at >= date_trunc('month', current_date + interval '-1 month')::timestamp ";
        $sql .= "AND ";
        $sql .= "  site_id = 1 ";
error_log($sql);
        $adcost_total = DB::select($sql)[0];

//error_log(print_r($pay_total, true));
//error_log(print_r($user_total, true));
//error_log(print_r($sales_total, true));
//error_log(print_r($adcost_total, true));
*/








// // 入会者数
// 	$sql = "SELECT ";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-1 day")) . "' AND code = '1001') AS cnt_0,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-2 day")) . "' AND code = '1001') AS cnt_1,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-3 day")) . "' AND code = '1001') AS cnt_2,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-4 day")) . "' AND code = '1001') AS cnt_3,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-5 day")) . "' AND code = '1001') AS cnt_4,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-6 day")) . "' AND code = '1011') AS cnt_5,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-7 day")) . "' AND code = '1001') AS cnt_6";
//         $usersValues = DB::select($sql)[0];
//         $usersValues->sum =     $usersValues->cnt_0 +
//                                 $usersValues->cnt_1 +
//                                 $usersValues->cnt_2 +
//                                 $usersValues->cnt_3 +
//                                 $usersValues->cnt_4 +
//                                 $usersValues->cnt_5 +
//                                 $usersValues->cnt_6     ;

// // 入会者数累計
// 	$sql = "SELECT ";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-1 day")) . "' AND code = '1002') AS cnt_0,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-2 day")) . "' AND code = '1002') AS cnt_1,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-3 day")) . "' AND code = '1002') AS cnt_2,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-4 day")) . "' AND code = '1002') AS cnt_3,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-5 day")) . "' AND code = '1002') AS cnt_4,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-6 day")) . "' AND code = '1002') AS cnt_5,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-7 day")) . "' AND code = '1002') AS cnt_6";
//         $usersTotalValues = DB::select($sql)[0];
//         $usersTotalValues->sum =        $usersTotalValues->cnt_0 +
//                                         $usersTotalValues->cnt_1 +
//                                         $usersTotalValues->cnt_2 +
//                                         $usersTotalValues->cnt_3 +
//                                         $usersTotalValues->cnt_4 +
//                                         $usersTotalValues->cnt_5 +
//                                         $usersTotalValues->cnt_6        ;


// // 決済額
// 	$sql = "SELECT ";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-1 day")) . "' AND code = '1011') AS pay_0,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-2 day")) . "' AND code = '1011') AS pay_1,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-3 day")) . "' AND code = '1011') AS pay_2,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-4 day")) . "' AND code = '1011') AS pay_3,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-5 day")) . "' AND code = '1011') AS pay_4,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-6 day")) . "' AND code = '1011') AS pay_5,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-7 day")) . "' AND code = '1011') AS pay_6";
//         $payValues = DB::select($sql)[0];
//         $payValues->sum =       $payValues->pay_0 +
//                                 $payValues->pay_1 +
//                                 $payValues->pay_2 +
//                                 $payValues->pay_3 +
//                                 $payValues->pay_4 +
//                                 $payValues->pay_5 +
//                                 $payValues->pay_6       ;

// // 決済額累計
// 	$sql = "SELECT ";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-1 day")) . "' AND code = '1012') AS pay_0,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-2 day")) . "' AND code = '1012') AS pay_1,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-3 day")) . "' AND code = '1012') AS pay_2,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-4 day")) . "' AND code = '1012') AS pay_3,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-5 day")) . "' AND code = '1012') AS pay_4,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-6 day")) . "' AND code = '1012') AS pay_5,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-7 day")) . "' AND code = '1012') AS pay_6";
//         $payTotalValues = DB::select($sql)[0];
//         $payTotalValues->sum =  $payTotalValues->pay_0 +
//                                 $payTotalValues->pay_1 +
//                                 $payTotalValues->pay_2 +
//                                 $payTotalValues->pay_3 +
//                                 $payTotalValues->pay_4 +
//                                 $payTotalValues->pay_5 +
//                                 $payTotalValues->pay_6  ;

// // 売上額
// 	$sql = "SELECT ";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-1 day")) . "' AND code = '1013') AS sales_0,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-2 day")) . "' AND code = '1013') AS sales_1,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-3 day")) . "' AND code = '1013') AS sales_2,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-4 day")) . "' AND code = '1013') AS sales_3,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-5 day")) . "' AND code = '1013') AS sales_4,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-6 day")) . "' AND code = '1013') AS sales_5,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-7 day")) . "' AND code = '1013') AS sales_6";
//         $salesValues = DB::select($sql)[0];
//         $salesValues->sum =     $salesValues->sales_0 +
//                                 $salesValues->sales_1 +
//                                 $salesValues->sales_2 +
//                                 $salesValues->sales_3 +
//                                 $salesValues->sales_4 +
//                                 $salesValues->sales_5 +
//                                 $salesValues->sales_6   ;

// // 売上額累計
// 	$sql = "SELECT ";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-1 day")) . "' AND code = '1014') AS sales_0,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-2 day")) . "' AND code = '1014') AS sales_1,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-3 day")) . "' AND code = '1014') AS sales_2,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-4 day")) . "' AND code = '1014') AS sales_3,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-5 day")) . "' AND code = '1014') AS sales_4,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-6 day")) . "' AND code = '1014') AS sales_5,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-7 day")) . "' AND code = '1014') AS sales_6";
//         $salesTotalValues = DB::select($sql)[0];
//         $salesTotalValues->sum =        $salesTotalValues->sales_0 +
//                                         $salesTotalValues->sales_1 +
//                                         $salesTotalValues->sales_2 +
//                                         $salesTotalValues->sales_3 +
//                                         $salesTotalValues->sales_4 +
//                                         $salesTotalValues->sales_5 +
//                                         $salesTotalValues->sales_6      ;

// // 広告費
// 	$sql = "SELECT ";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-1 day")) . "' AND code = '1061') AS adcost_0,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-2 day")) . "' AND code = '1061') AS adcost_1,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-3 day")) . "' AND code = '1061') AS adcost_2,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-4 day")) . "' AND code = '1061') AS adcost_3,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-5 day")) . "' AND code = '1061') AS adcost_4,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-6 day")) . "' AND code = '1061') AS adcost_5,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-7 day")) . "' AND code = '1061') AS adcost_6";
//         $adcosValues = DB::select($sql)[0];
//         $adcosValues->sum =     $adcosValues->adcost_0 +
//                                 $adcosValues->adcost_1 +
//                                 $adcosValues->adcost_2 +
//                                 $adcosValues->adcost_3 +
//                                 $adcosValues->adcost_4 +
//                                 $adcosValues->adcost_5 +
//                                 $adcosValues->adcost_6  ;

// // 広告費累計
// 	$sql = "SELECT ";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-1 day")) . "' AND code = '1062') AS adcost_0,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-2 day")) . "' AND code = '1062') AS adcost_1,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-3 day")) . "' AND code = '1062') AS adcost_2,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-4 day")) . "' AND code = '1062') AS adcost_3,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-5 day")) . "' AND code = '1062') AS adcost_4,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-6 day")) . "' AND code = '1062') AS adcost_5,";
// 	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-7 day")) . "' AND code = '1062') AS adcost_6";
//         $adcosTotalValues = DB::select($sql)[0];
//         $adcosTotalValues->sum =        $adcosTotalValues->adcost_0 +
//                                         $adcosTotalValues->adcost_1 +
//                                         $adcosTotalValues->adcost_2 +
//                                         $adcosTotalValues->adcost_3 +
//                                         $adcosTotalValues->adcost_4 +
//                                         $adcosTotalValues->adcost_5 +
//                                         $adcosTotalValues->adcost_6  ;




/*
//-------
// 入会者数
	$sql = "SELECT ";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-1 day")) . "' AND code = '1001' AND site_id = {$auth->site_id}) AS cnt_0,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-2 day")) . "' AND code = '1001' AND site_id = {$auth->site_id}) AS cnt_1,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-3 day")) . "' AND code = '1001' AND site_id = {$auth->site_id}) AS cnt_2,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-4 day")) . "' AND code = '1001' AND site_id = {$auth->site_id}) AS cnt_3,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-5 day")) . "' AND code = '1001' AND site_id = {$auth->site_id}) AS cnt_4,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-6 day")) . "' AND code = '1011' AND site_id = {$auth->site_id}) AS cnt_5,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-7 day")) . "' AND code = '1001' AND site_id = {$auth->site_id}) AS cnt_6";
        $usersValues = DB::select($sql)[0];
        $usersValues->sum =     $usersValues->cnt_0 +
                                $usersValues->cnt_1 +
                                $usersValues->cnt_2 +
                                $usersValues->cnt_3 +
                                $usersValues->cnt_4 +
                                $usersValues->cnt_5 +
                                $usersValues->cnt_6     ;

// 入会者数累計
	$sql = "SELECT ";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-1 day")) . "' AND code = '1002' AND site_id = {$auth->site_id}) AS cnt_0,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-2 day")) . "' AND code = '1002' AND site_id = {$auth->site_id}) AS cnt_1,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-3 day")) . "' AND code = '1002' AND site_id = {$auth->site_id}) AS cnt_2,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-4 day")) . "' AND code = '1002' AND site_id = {$auth->site_id}) AS cnt_3,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-5 day")) . "' AND code = '1002' AND site_id = {$auth->site_id}) AS cnt_4,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-6 day")) . "' AND code = '1002' AND site_id = {$auth->site_id}) AS cnt_5,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-7 day")) . "' AND code = '1002' AND site_id = {$auth->site_id}) AS cnt_6";
        $usersTotalValues = DB::select($sql)[0];
        $usersTotalValues->sum =        $usersTotalValues->cnt_0 +
                                        $usersTotalValues->cnt_1 +
                                        $usersTotalValues->cnt_2 +
                                        $usersTotalValues->cnt_3 +
                                        $usersTotalValues->cnt_4 +
                                        $usersTotalValues->cnt_5 +
                                        $usersTotalValues->cnt_6        ;


// 決済額
	$sql = "SELECT ";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-1 day")) . "' AND code = '1011' AND site_id = {$auth->site_id}) AS pay_0,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-2 day")) . "' AND code = '1011' AND site_id = {$auth->site_id}) AS pay_1,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-3 day")) . "' AND code = '1011' AND site_id = {$auth->site_id}) AS pay_2,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-4 day")) . "' AND code = '1011' AND site_id = {$auth->site_id}) AS pay_3,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-5 day")) . "' AND code = '1011' AND site_id = {$auth->site_id}) AS pay_4,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-6 day")) . "' AND code = '1011' AND site_id = {$auth->site_id}) AS pay_5,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-7 day")) . "' AND code = '1011' AND site_id = {$auth->site_id}) AS pay_6";
        $payValues = DB::select($sql)[0];
        $payValues->sum =       $payValues->pay_0 +
                                $payValues->pay_1 +
                                $payValues->pay_2 +
                                $payValues->pay_3 +
                                $payValues->pay_4 +
                                $payValues->pay_5 +
                                $payValues->pay_6       ;

// 決済額累計
	$sql = "SELECT ";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-1 day")) . "' AND code = '1012' AND site_id = {$auth->site_id}) AS pay_0,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-2 day")) . "' AND code = '1012' AND site_id = {$auth->site_id}) AS pay_1,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-3 day")) . "' AND code = '1012' AND site_id = {$auth->site_id}) AS pay_2,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-4 day")) . "' AND code = '1012' AND site_id = {$auth->site_id}) AS pay_3,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-5 day")) . "' AND code = '1012' AND site_id = {$auth->site_id}) AS pay_4,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-6 day")) . "' AND code = '1012' AND site_id = {$auth->site_id}) AS pay_5,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-7 day")) . "' AND code = '1012' AND site_id = {$auth->site_id}) AS pay_6";
        $payTotalValues = DB::select($sql)[0];
        $payTotalValues->sum =  $payTotalValues->pay_0 +
                                $payTotalValues->pay_1 +
                                $payTotalValues->pay_2 +
                                $payTotalValues->pay_3 +
                                $payTotalValues->pay_4 +
                                $payTotalValues->pay_5 +
                                $payTotalValues->pay_6  ;

// 売上額
	$sql = "SELECT ";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-1 day")) . "' AND code = '1013' AND site_id = {$auth->site_id}) AS sales_0,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-2 day")) . "' AND code = '1013' AND site_id = {$auth->site_id}) AS sales_1,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-3 day")) . "' AND code = '1013' AND site_id = {$auth->site_id}) AS sales_2,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-4 day")) . "' AND code = '1013' AND site_id = {$auth->site_id}) AS sales_3,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-5 day")) . "' AND code = '1013' AND site_id = {$auth->site_id}) AS sales_4,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-6 day")) . "' AND code = '1013' AND site_id = {$auth->site_id}) AS sales_5,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-7 day")) . "' AND code = '1013' AND site_id = {$auth->site_id}) AS sales_6";
        $salesValues = DB::select($sql)[0];
        $salesValues->sum =     $salesValues->sales_0 +
                                $salesValues->sales_1 +
                                $salesValues->sales_2 +
                                $salesValues->sales_3 +
                                $salesValues->sales_4 +
                                $salesValues->sales_5 +
                                $salesValues->sales_6   ;

// 売上額累計
	$sql = "SELECT ";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-1 day")) . "' AND code = '1014' AND site_id = {$auth->site_id}) AS sales_0,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-2 day")) . "' AND code = '1014' AND site_id = {$auth->site_id}) AS sales_1,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-3 day")) . "' AND code = '1014' AND site_id = {$auth->site_id}) AS sales_2,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-4 day")) . "' AND code = '1014' AND site_id = {$auth->site_id}) AS sales_3,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-5 day")) . "' AND code = '1014' AND site_id = {$auth->site_id}) AS sales_4,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-6 day")) . "' AND code = '1014' AND site_id = {$auth->site_id}) AS sales_5,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-7 day")) . "' AND code = '1014' AND site_id = {$auth->site_id}) AS sales_6";
        $salesTotalValues = DB::select($sql)[0];
        $salesTotalValues->sum =        $salesTotalValues->sales_0 +
                                        $salesTotalValues->sales_1 +
                                        $salesTotalValues->sales_2 +
                                        $salesTotalValues->sales_3 +
                                        $salesTotalValues->sales_4 +
                                        $salesTotalValues->sales_5 +
                                        $salesTotalValues->sales_6      ;

// 広告費
	$sql = "SELECT ";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-1 day")) . "' AND code = '1061' AND site_id = {$auth->site_id}) AS adcost_0,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-2 day")) . "' AND code = '1061' AND site_id = {$auth->site_id}) AS adcost_1,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-3 day")) . "' AND code = '1061' AND site_id = {$auth->site_id}) AS adcost_2,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-4 day")) . "' AND code = '1061' AND site_id = {$auth->site_id}) AS adcost_3,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-5 day")) . "' AND code = '1061' AND site_id = {$auth->site_id}) AS adcost_4,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-6 day")) . "' AND code = '1061' AND site_id = {$auth->site_id}) AS adcost_5,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-7 day")) . "' AND code = '1061' AND site_id = {$auth->site_id}) AS adcost_6";
        $adcosValues = DB::select($sql)[0];
        $adcosValues->sum =     $adcosValues->adcost_0 +
                                $adcosValues->adcost_1 +
                                $adcosValues->adcost_2 +
                                $adcosValues->adcost_3 +
                                $adcosValues->adcost_4 +
                                $adcosValues->adcost_5 +
                                $adcosValues->adcost_6  ;

// 広告費累計
	$sql = "SELECT ";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-1 day")) . "' AND code = '1062' AND site_id = {$auth->site_id}) AS adcost_0,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-2 day")) . "' AND code = '1062' AND site_id = {$auth->site_id}) AS adcost_1,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-3 day")) . "' AND code = '1062' AND site_id = {$auth->site_id}) AS adcost_2,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-4 day")) . "' AND code = '1062' AND site_id = {$auth->site_id}) AS adcost_3,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-5 day")) . "' AND code = '1062' AND site_id = {$auth->site_id}) AS adcost_4,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-6 day")) . "' AND code = '1062' AND site_id = {$auth->site_id}) AS adcost_5,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-7 day")) . "' AND code = '1062' AND site_id = {$auth->site_id}) AS adcost_6";
        $adcosTotalValues = DB::select($sql)[0];
        $adcosTotalValues->sum =        $adcosTotalValues->adcost_0 +
                                        $adcosTotalValues->adcost_1 +
                                        $adcosTotalValues->adcost_2 +
                                        $adcosTotalValues->adcost_3 +
                                        $adcosTotalValues->adcost_4 +
                                        $adcosTotalValues->adcost_5 +
                                        $adcosTotalValues->adcost_6  ;
*/


//-------
// 入会者数
	$sql = "SELECT ";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-1 day")) . "' AND code = '1001' AND site_id = {$auth->site_id}) AS cnt_0,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-2 day")) . "' AND code = '1001' AND site_id = {$auth->site_id}) AS cnt_1,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-3 day")) . "' AND code = '1001' AND site_id = {$auth->site_id}) AS cnt_2,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-4 day")) . "' AND code = '1001' AND site_id = {$auth->site_id}) AS cnt_3,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-5 day")) . "' AND code = '1001' AND site_id = {$auth->site_id}) AS cnt_4,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-6 day")) . "' AND code = '1011' AND site_id = {$auth->site_id}) AS cnt_5,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-7 day")) . "' AND code = '1001' AND site_id = {$auth->site_id}) AS cnt_6";
        $usersValues = DB::select($sql)[0];
        $usersValues->sum =     $usersValues->cnt_0 +
                                $usersValues->cnt_1 +
                                $usersValues->cnt_2 +
                                $usersValues->cnt_3 +
                                $usersValues->cnt_4 +
                                $usersValues->cnt_5 +
                                $usersValues->cnt_6     ;

// 入会者数累計
	$sql = "SELECT ";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-1 day")) . "' AND code = '1002' AND site_id = {$auth->site_id}) AS cnt_0,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-2 day")) . "' AND code = '1002' AND site_id = {$auth->site_id}) AS cnt_1,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-3 day")) . "' AND code = '1002' AND site_id = {$auth->site_id}) AS cnt_2,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-4 day")) . "' AND code = '1002' AND site_id = {$auth->site_id}) AS cnt_3,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-5 day")) . "' AND code = '1002' AND site_id = {$auth->site_id}) AS cnt_4,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-6 day")) . "' AND code = '1002' AND site_id = {$auth->site_id}) AS cnt_5,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-7 day")) . "' AND code = '1002' AND site_id = {$auth->site_id}) AS cnt_6";
        $usersTotalValues = DB::select($sql)[0];
        $usersTotalValues->sum =        $usersTotalValues->cnt_0 +
                                        $usersTotalValues->cnt_1 +
                                        $usersTotalValues->cnt_2 +
                                        $usersTotalValues->cnt_3 +
                                        $usersTotalValues->cnt_4 +
                                        $usersTotalValues->cnt_5 +
                                        $usersTotalValues->cnt_6        ;


// 決済額
	$sql = "SELECT ";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-1 day")) . "' AND code = '1011' AND site_id = {$auth->site_id}) AS pay_0,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-2 day")) . "' AND code = '1011' AND site_id = {$auth->site_id}) AS pay_1,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-3 day")) . "' AND code = '1011' AND site_id = {$auth->site_id}) AS pay_2,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-4 day")) . "' AND code = '1011' AND site_id = {$auth->site_id}) AS pay_3,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-5 day")) . "' AND code = '1011' AND site_id = {$auth->site_id}) AS pay_4,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-6 day")) . "' AND code = '1011' AND site_id = {$auth->site_id}) AS pay_5,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-7 day")) . "' AND code = '1011' AND site_id = {$auth->site_id}) AS pay_6";
        $payValues = DB::select($sql)[0];
        $payValues->sum =       $payValues->pay_0 +
                                $payValues->pay_1 +
                                $payValues->pay_2 +
                                $payValues->pay_3 +
                                $payValues->pay_4 +
                                $payValues->pay_5 +
                                $payValues->pay_6       ;

// 決済額累計
	$sql = "SELECT ";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-1 day")) . "' AND code = '1012' AND site_id = {$auth->site_id}) AS pay_0,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-2 day")) . "' AND code = '1012' AND site_id = {$auth->site_id}) AS pay_1,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-3 day")) . "' AND code = '1012' AND site_id = {$auth->site_id}) AS pay_2,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-4 day")) . "' AND code = '1012' AND site_id = {$auth->site_id}) AS pay_3,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-5 day")) . "' AND code = '1012' AND site_id = {$auth->site_id}) AS pay_4,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-6 day")) . "' AND code = '1012' AND site_id = {$auth->site_id}) AS pay_5,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-7 day")) . "' AND code = '1012' AND site_id = {$auth->site_id}) AS pay_6";
        $payTotalValues = DB::select($sql)[0];
        $payTotalValues->sum =  $payTotalValues->pay_0 +
                                $payTotalValues->pay_1 +
                                $payTotalValues->pay_2 +
                                $payTotalValues->pay_3 +
                                $payTotalValues->pay_4 +
                                $payTotalValues->pay_5 +
                                $payTotalValues->pay_6  ;

// 売上額
	$sql = "SELECT ";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-1 day")) . "' AND code = '1013' AND site_id = {$auth->site_id}) AS sales_0,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-2 day")) . "' AND code = '1013' AND site_id = {$auth->site_id}) AS sales_1,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-3 day")) . "' AND code = '1013' AND site_id = {$auth->site_id}) AS sales_2,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-4 day")) . "' AND code = '1013' AND site_id = {$auth->site_id}) AS sales_3,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-5 day")) . "' AND code = '1013' AND site_id = {$auth->site_id}) AS sales_4,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-6 day")) . "' AND code = '1013' AND site_id = {$auth->site_id}) AS sales_5,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-7 day")) . "' AND code = '1013' AND site_id = {$auth->site_id}) AS sales_6";
        $salesValues = DB::select($sql)[0];
        $salesValues->sum =     $salesValues->sales_0 +
                                $salesValues->sales_1 +
                                $salesValues->sales_2 +
                                $salesValues->sales_3 +
                                $salesValues->sales_4 +
                                $salesValues->sales_5 +
                                $salesValues->sales_6   ;

// 売上額累計
	$sql = "SELECT ";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-1 day")) . "' AND code = '1014' AND site_id = {$auth->site_id}) AS sales_0,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-2 day")) . "' AND code = '1014' AND site_id = {$auth->site_id}) AS sales_1,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-3 day")) . "' AND code = '1014' AND site_id = {$auth->site_id}) AS sales_2,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-4 day")) . "' AND code = '1014' AND site_id = {$auth->site_id}) AS sales_3,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-5 day")) . "' AND code = '1014' AND site_id = {$auth->site_id}) AS sales_4,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-6 day")) . "' AND code = '1014' AND site_id = {$auth->site_id}) AS sales_5,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-7 day")) . "' AND code = '1014' AND site_id = {$auth->site_id}) AS sales_6";
        $salesTotalValues = DB::select($sql)[0];
        $salesTotalValues->sum =        $salesTotalValues->sales_0 +
                                        $salesTotalValues->sales_1 +
                                        $salesTotalValues->sales_2 +
                                        $salesTotalValues->sales_3 +
                                        $salesTotalValues->sales_4 +
                                        $salesTotalValues->sales_5 +
                                        $salesTotalValues->sales_6      ;

// 広告費
	$sql = "SELECT ";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-1 day")) . "' AND code = '1061' AND site_id = {$auth->site_id}) AS adcost_0,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-2 day")) . "' AND code = '1061' AND site_id = {$auth->site_id}) AS adcost_1,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-3 day")) . "' AND code = '1061' AND site_id = {$auth->site_id}) AS adcost_2,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-4 day")) . "' AND code = '1061' AND site_id = {$auth->site_id}) AS adcost_3,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-5 day")) . "' AND code = '1061' AND site_id = {$auth->site_id}) AS adcost_4,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-6 day")) . "' AND code = '1061' AND site_id = {$auth->site_id}) AS adcost_5,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-7 day")) . "' AND code = '1061' AND site_id = {$auth->site_id}) AS adcost_6";
        $adcosValues = DB::select($sql)[0];
        $adcosValues->sum =     $adcosValues->adcost_0 +
                                $adcosValues->adcost_1 +
                                $adcosValues->adcost_2 +
                                $adcosValues->adcost_3 +
                                $adcosValues->adcost_4 +
                                $adcosValues->adcost_5 +
                                $adcosValues->adcost_6  ;

// 広告費累計
	$sql = "SELECT ";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-1 day")) . "' AND code = '1062' AND site_id = {$auth->site_id}) AS adcost_0,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-2 day")) . "' AND code = '1062' AND site_id = {$auth->site_id}) AS adcost_1,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-3 day")) . "' AND code = '1062' AND site_id = {$auth->site_id}) AS adcost_2,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-4 day")) . "' AND code = '1062' AND site_id = {$auth->site_id}) AS adcost_3,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-5 day")) . "' AND code = '1062' AND site_id = {$auth->site_id}) AS adcost_4,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-6 day")) . "' AND code = '1062' AND site_id = {$auth->site_id}) AS adcost_5,";
	$sql .= "(SELECT value FROM geninfo_aggregates WHERE target_at = '" .date("Y-m-d", strtotime("-7 day")) . "' AND code = '1062' AND site_id = {$auth->site_id}) AS adcost_6";
        $adcosTotalValues = DB::select($sql)[0];
        $adcosTotalValues->sum =        $adcosTotalValues->adcost_0 +
                                        $adcosTotalValues->adcost_1 +
                                        $adcosTotalValues->adcost_2 +
                                        $adcosTotalValues->adcost_3 +
                                        $adcosTotalValues->adcost_4 +
                                        $adcosTotalValues->adcost_5 +
                                        $adcosTotalValues->adcost_6  ;




//-------

        return view('home.index', compact(
'days',
'usersValues', 'usersTotalValues', 'payValues', 'payTotalValues', 'salesValues', 'salesTotalValues', 'adcosValues', 'adcosTotalValues'

));
    }
}
