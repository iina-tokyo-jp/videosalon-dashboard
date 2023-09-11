<?php

namespace App\Http\Controllers;

use App\Models\Adlink;
use App\Models\Adcode;
use App\Models\User;
use App\Models\UserAppraiser;
use App\Exports\CsvExport;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

/**
 * 事業管理機能コントローラ
 */
class BusinessController extends Controller
{
    /**
     * 一覧画面表示
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        $now = new Carbon();
        $type = intval($request->get('type'));
        $start_date = new Carbon($request->get('start_date') ?? $now->copy()->addDays(-1)->format('Y-m-d'));
        $end_date   = new Carbon($request->get('end_date')   ?? $now->format('Y-m-d'));
        $workDate   = new Carbon($request->get('start_date') ?? $now->copy()->addDays(-1)->format('Y-m-d'));
        $days = array();
        $sql = '';

        while ($workDate->between(Carbon::parse($start_date->format('Y-m-d')), Carbon::parse($end_date->format('Y-m-d')))) {
            $days[] = $workDate->format('n/j');
            $workDate->addDays(1);
        }

        switch ($type) {
            case 0: // 広告動向
                $records = $this->createTypeZero($request);
                break;
            case 1: // 広告動向詳細
                $records = $this->createTypeOne($request);
                break;
            case 2: // 占い師動向詳細
                $records = $this->createTypeTwo($request);
                break;
            case 3: // サイト全体情報
                $records = $this->createTypeThree($request);
                break;
        }

        $queryParams = array(
            'type'       => $type,
            'start_date' => $start_date,
            'end_date'   => $end_date
        );

        return view('businesses.index', compact('records', 'days', 'queryParams'));
    }

    /**
     * CSVダウンロード
     *
     * @param Request $request
     * @return void
     */
    public function downloadCsv(Request $request)
    {
        $now = new Carbon();
        $type = intval($request->get('type'));
        $start_date = new Carbon($request->get('start_date') ?? $now->copy()->addDays(-1)->format('Y-m-d'));
        $end_date = new Carbon($request->get('end_date') ?? $now->format('Y-m-d'));
        $workDate = new Carbon($request->get('start_date') ?? $now->copy()->addDays(-1)->format('Y-m-d'));
        $days = array();

        while ($workDate->between(Carbon::parse($start_date->format('Y-m-d')), Carbon::parse($end_date->format('Y-m-d')))) {
            $days[] = $workDate->format('m月d日');
            $workDate->addDays(1);
        }

        switch ($type) {
            case 0:
                $records = $this->createTypeZero($request);
                return Excel::download(new CsvExport($records, 'businesses.exports.type0', $days), '広告動向_' . $now->format('YmdHi') . '.csv');
                break;
            case 1:
                $records = $this->createTypeOne($request);
                return Excel::download(new CsvExport($records, 'businesses.exports.type1', $days), '広告動向詳細_' . $now->format('YmdHi') . '.csv');
                break;
            case 2:
                $records = $this->createTypeTwo($request);
                return Excel::download(new CsvExport($records, 'businesses.exports.type2', $days), '占い師動向詳細_' . $now->format('YmdHi') . '.csv');
                break;
            case 3:
                $records = $this->createTypeThree($request);
                return Excel::download(new CsvExport($records, 'businesses.exports.type3', $days), 'サイト全体情報_' . $now->format('YmdHi') . '.csv');
                break;
        }
    }

    /**
     * 広告動向データ作成
     *
     * @param Request $request
     * @return void
     */
    private function createTypeZero(Request $request)
    {
        // 認証情報取得
        $auth = Auth::user();

        $now = new Carbon();
        $start_date = new Carbon($request->get('start_date') ?? $now->copy()->addDays(-1)->format('Y-m-d'));
        $end_date = new Carbon($request->get('end_date') ?? $now->format('Y-m-d'));
        $workDate = new Carbon($request->get('start_date') ?? $now->copy()->addDays(-1)->format('Y-m-d'));
        $days = array();
        $counter = 0;

        $sql  = " SELECT";
        $sql .= "    adcodes.id";
        $sql .= "   ,adcodes.site_id";
        $sql .= "   ,adcodes.ad_code";
        $sql .= "   ,adcodes.status";
        $sql .= "   ,adcodes.site_name";
        $sql .= "   ,adcodes.start_date";
        $sql .= "   ,adcodes.url";
        $sql .= "   ,adcodes.unit_price";
        $sql .= "   ,adcodes.banner";
        $sql .= "   ,adcodes.add_date";
        $sql .= "   ,adcodes.mod_date";
        $sql .= "   ,adlinks.referer_type";
        $sql .= "   ,MAX(date_trunc('day', adlinks.add_date)) as last_date";

        $sqlSum = '';
        $sqlPoint = '';
        $sqlCost = '';
        while ($workDate->between(Carbon::parse($start_date->format('Y-m-d')), Carbon::parse($end_date->format('Y-m-d')))) {

            $day = $workDate->format('Y-m-d');
            $days[] = $workDate->format('m月d日');

            $sqlSum .= "   ,SUM(case when date_trunc('day', adlinks.add_date) = '" . $day . "' then 1 else 0 end) as sum" . $counter;
            $sqlPoint .= "   ,SUM(case when date_trunc('day', pointhistories.add_date) = '" . $day . "' then pointhistories.point else 0 end) as point" . $counter;
            $sqlCost  .= "   ,COALESCE(adcodes.unit_price, 0) * SUM(case when date_trunc('day', adlinks.add_date) = '" . $day . "' then 1 else 0 end) as cost" . $counter;

            $counter += 1;
            $workDate->addDays(1);
        }

        $sql .= $sqlSum;
        $sql .= $sqlPoint;
        $sql .= $sqlCost;
        $sql .= "   ,COUNT(distinct adlinks.user_id) / " . count($days) . " AS count_average";
        $sql .= "   ,SUM(pointhistories.point) / " . count($days) . " as point_average";
        $sql .= "   ,(COALESCE(adcodes.unit_price, 0) * COUNT(distinct adlinks.user_id)) / " . count($days) . " as cost_average";
        $sql .= "   ,SUM(pointhistories.point)";
        $sql .= "    - (COALESCE(adcodes.unit_price, 0) * SUM(case when adlinks.add_date IS NOT NULL then 1 else 0 end)) as income_and_expenditure";
        $sql .= " FROM";
        $sql .= "   adlinks";
        $sql .= "   LEFT JOIN";
        $sql .= "     adcodes";
        $sql .= "   ON (true)";
        $sql .= "     AND adcodes.site_id = adlinks.site_id";
        $sql .= "     AND adcodes.ad_code = adlinks.ad_code";
        $sql .= "   LEFT JOIN";
        $sql .= "     pointhistories";
        $sql .= "   ON (true)";
        $sql .= "     AND pointhistories.site_id = adlinks.site_id";
        $sql .= "     AND pointhistories.user_id = adlinks.user_id";
        $sql .= "     AND pointhistories.reason = 'spend'";
        $sql .= "     AND date_trunc('day',pointhistories.add_date) BETWEEN '" . $start_date->format('Y-m-d') . "' AND '" . $end_date->format('Y-m-d') . "'";
        $sql .= " WHERE (true)";
        $sql .= "   AND date_trunc('day',adlinks.add_date) BETWEEN '" . $start_date->format('Y-m-d') . "' AND '" . $end_date->format('Y-m-d') . "'";
        $sql .= "   AND adlinks.site_id = " . $auth->site_id;

        if ($auth->authority == 4) {
            $sql .= "   AND adcodes.adagency_code = '" . $auth->adagency_code . "'";
        }

        $sql .= " GROUP BY ";
        $sql .= "    adcodes.id";
        $sql .= "   ,adcodes.site_id";
        $sql .= "   ,adcodes.ad_code";
        $sql .= "   ,adcodes.status";
        $sql .= "   ,adcodes.site_name";
        $sql .= "   ,adcodes.start_date";
        $sql .= "   ,adcodes.url";
        $sql .= "   ,adcodes.unit_price";
        $sql .= "   ,adcodes.banner";
        $sql .= "   ,adcodes.add_date";
        $sql .= "   ,adcodes.mod_date";
        $sql .= "   ,adlinks.referer_type";
        $sql .= " ORDER BY";
        $sql .= "   adcodes.add_date DESC";

        return DB::select($sql);
    }

    /**
     * 広告動向詳細データ作成
     *
     * @param Request $request
     * @return void
     */
    private function createTypeOne(Request $request)
    {
        // 認証情報取得
        $auth = Auth::user();

        $now = new Carbon();
        $start_date = new Carbon($request->get('start_date') ?? $now->copy()->addDays(-1)->format('Y-m-d'));
        $end_date = new Carbon($request->get('end_date') ?? $now->format('Y-m-d'));
        $workDate = new Carbon($request->get('start_date') ?? $now->copy()->addDays(-1)->format('Y-m-d'));
        $days = array();
        $counter = 0;

        $sql  = " SELECT";
        $sql .= "    adcodes.id";
        $sql .= "   ,adcodes.site_id";
        $sql .= "   ,adcodes.ad_code";
        $sql .= "   ,adcodes.status";
        $sql .= "   ,adcodes.site_name";
        $sql .= "   ,adcodes.start_date";
        $sql .= "   ,adcodes.url";
        $sql .= "   ,adcodes.unit_price";
        $sql .= "   ,adcodes.banner";
        $sql .= "   ,adcodes.add_date";
        $sql .= "   ,adcodes.mod_date";
        $sql .= "   ,adlinks.referer_type";
        $sql .= "   ,MAX(CASE WHEN date_trunc('day', adlinks.add_date) BETWEEN '" . $start_date->format('Y-m-d') . "' AND '" . $end_date->format('Y-m-d') . "' THEN date_trunc('day', adlinks.add_date) ELSE NULL END) as last_date";

        $sqlSum = '';
        $sqlPoint = '';
        $sqlSumOld = '';
        $sqlPointOld = '';
        $sqlSumAll = '';
        $sqlPointAll = '';

        while ($workDate->between(Carbon::parse($start_date->format('Y-m-d')), Carbon::parse($end_date->format('Y-m-d')))) {

            $day = $workDate->format('Y-m-d');
            $days[] = $workDate->format('m月d日');

            $sqlSum .= "   ,SUM(case when date_trunc('day', adlinks.add_date) = '" . $day . "' then 1 else 0 end) as sum" . $counter;
            $sqlPoint .= "   ,SUM(case when date_trunc('day', adlinks.add_date) BETWEEN '" . $start_date->format('Y-m-d') . "' AND '" . $end_date->format('Y-m-d') . "' AND date_trunc('day', pointhistories.add_date) = '" . $day . "' then pointhistories.point else 0 end) as point" . $counter;
            $sqlSumOld .= "  ,SUM(CASE WHEN date_trunc('day', adlinks.add_date) < '" . $start_date->format('Y-m-d') . "' AND users.id IS NOT NULL THEN 1 ELSE 0 END) AS sumold" . $counter;
            $sqlPointOld .= "  ,SUM(CASE WHEN date_trunc('day', adlinks.add_date) < '" . $start_date->format('Y-m-d') ."' AND date_trunc('day', pointhistories.add_date) = '" . $day . "' THEN pointhistories.point ELSE 0 END) AS pointold" . $counter;
            $sqlSumAll .= "   ,SUM(CASE WHEN date_trunc('day', adlinks.add_date) <= '" . $day . "' AND (users.id IS NOT NULL OR date_trunc('day', adlinks.add_date) < '" . $start_date->format('Y-m-d') ."') THEN 1 ELSE 0 END) as sumall" . $counter;
            $sqlPointAll .= "   ,SUM(CASE WHEN date_trunc('day', pointhistories.add_date) = '" . $day . "' THEN pointhistories.point ELSE 0 END) as pointall" . $counter;

            $counter += 1;
            $workDate->addDays(1);
        }

        $sql .= $sqlSum;
        $sql .= $sqlSumOld;
        $sql .= $sqlSumAll;
        $sql .= $sqlPoint;
        $sql .= $sqlPointOld;
        $sql .= $sqlPointAll;

        $sql .= "   ,COALESCE(adcodes.unit_price, 0) * SUM(case when date_trunc('day', adlinks.add_date) >= '" . $start_date->format('Y-m-d') . "' then 1 else 0 end) as cost_summary";
        $sql .= "   ,(COALESCE(adcodes.unit_price, 0) * SUM(case when date_trunc('day', adlinks.add_date) >= '" . $start_date->format('Y-m-d') . "' then 1 else 0 end))";
        $sql .= "    / NULLIF(SUM(CASE WHEN date_trunc('day', pointhistories.add_date) >= '" . $start_date->format('Y-m-d') . "' THEN pointhistories.point ELSE 0 END), 0) as rate";
        $sql .= " FROM";
        $sql .= "   adlinks";
        $sql .= "   LEFT JOIN";
        $sql .= "     users";
        $sql .= "   ON (true)";
        $sql .= "     AND users.site_id = adlinks.site_id";
        $sql .= "     AND users.id = adlinks.user_id";
        $sql .= "     AND users.state in (1,2) ";
        $sql .= "   LEFT JOIN";
        $sql .= "     adcodes";
        $sql .= "   ON (true)";
        $sql .= "     AND adcodes.site_id = adlinks.site_id";
        $sql .= "     AND adcodes.ad_code = adlinks.ad_code";
        $sql .= "   LEFT JOIN";
        $sql .= "     pointhistories";
        $sql .= "   ON (true)";
        $sql .= "     AND pointhistories.site_id = adlinks.site_id";
        $sql .= "     AND pointhistories.user_id = adlinks.user_id";
        $sql .= "     AND pointhistories.reason = 'spend'";
        $sql .= "     AND date_trunc('day',pointhistories.add_date) BETWEEN '" . $start_date->format('Y-m-d') . "' AND '" . $end_date->format('Y-m-d') . "'";
        $sql .= " WHERE (true)";
        $sql .= "   AND date_trunc('day', adlinks.add_date) <= '" . $end_date->format('Y-m-d') . "'";
        $sql .= "   AND adlinks.site_id = " . $auth->site_id;

        if ($auth->authority == 4
        ) {
            $sql .= "   AND adcodes.adagency_code = '" . $auth->adagency_code . "'";
        }

        $sql .= " GROUP BY ";
        $sql .= "    adcodes.id";
        $sql .= "   ,adcodes.site_id";
        $sql .= "   ,adcodes.ad_code";
        $sql .= "   ,adcodes.status";
        $sql .= "   ,adcodes.site_name";
        $sql .= "   ,adcodes.start_date";
        $sql .= "   ,adcodes.url";
        $sql .= "   ,adcodes.unit_price";
        $sql .= "   ,adcodes.banner";
        $sql .= "   ,adcodes.add_date";
        $sql .= "   ,adcodes.mod_date";
        $sql .= "   ,adlinks.referer_type";
        $sql .= " ORDER BY";
        $sql .= "   adcodes.add_date DESC";

        return DB::select($sql);
    }

    /**
     * 占い師動向詳細情報
     *
     * @param Request $request
     * @return void
     */
    private function createTypeTwo(Request $request)
    {
        // 認証情報取得
        $auth = Auth::user();

        $now = new Carbon();
        $start_date = new Carbon($request->get('start_date') ?? $now->copy()->addDays(-1)->format('Y-m-d'));
        $end_date = new Carbon($request->get('end_date') ?? $now->format('Y-m-d'));
        $workDate = new Carbon($request->get('start_date') ?? $now->copy()->addDays(-1)->format('Y-m-d'));
        $counter = 0;

        $users = Adlink::query()
            ->where('site_id', $auth->site_id)
            ->whereBetween('add_date', [$start_date, $end_date ])
            ->pluck('user_id');

        $userStr = $users->implode(',');

        if (empty($userStr)) {
            $userStr = '0';
        }

        $sqlUsers  = " INNER JOIN";
        $sqlUsers .= " (";
        $sqlUsers .= "  SELECT";
        $sqlUsers .= "    unnest(ARRAY[" . $userStr . "]) AS user_id";
        $sqlUsers .= " ) AS users";
        $sqlUsers .= "   ON";

        $sql = '';
        $sql .= " SELECT ";
        $sql .= "    A.site_id";
        $sql .= "   ,A.appraiser_id";
        $sql .= "   ,A.name AS appraiser_name";     // 占い師名
        $sql .= "   ,A.unit_point";                 // 鑑定額
$sql .= "   ,A.unit_point_purchase";                 // add bny ohneta
        $sql .= "   ,A.worktime AS taiki";          // 累計待機時間
        $sql .= "   ,A.counts";                     // 累計鑑定数
        $sql .= "   ,A.exec_time";                  // 累計鑑定時間
        $sql .= "   ,A.point";                      // 累計売上
        $sql .= "   ,0 AS nyukin";                  // 累計入金額
        $sql .= "   ,A.avg_worktime AS avg_taiki";  // 平均待機時間
        $sql .= "   ,A.avg_counts";                 // 平均鑑定数
        $sql .= "   ,A.avg_exec_time";              // 平均鑑定時間
        $sql .= "   ,A.avg_point";                  // 平均売上
        $sql .= "   ,0 AS avg_nyukin";              // 平均入金額

//$sql .= "   ,A.sales_amountX";


        $sql1 = '';
        $sql2 = '';
        $sql3 = '';
        $sql4 = '';
        $sql5 = '';
        $sql6 = '';
        $sql7 = '';
        $sql8 = '';
        $sql9 = '';

        $workDate2 = $workDate->copy();
        while ($workDate2->between(Carbon::parse($start_date->format('Y-m-d')), Carbon::parse($end_date->format('Y-m-d')))) {

            // 累積会員鑑定回数
            $sql1 .= "  ,A.counts" . $counter;
            // 累積会員鑑定時間
            $sql2 .= "  ,A.exec_time" . $counter;
            // 累積会員鑑定売上
            $sql3 .= "  ,A.point" . $counter;
            // 新規会員鑑定回数
            $sql4 .= "  ,COALESCE(B.counts" . $counter . ", 0) AS count_new" . $counter;
            // 新規会員鑑定時間
            $sql5 .= "  ,COALESCE(B.exec_time" . $counter . ", '00:00') AS exec_time_new" . $counter;
            // 新規会員鑑定売上
            $sql6 .= "  ,COALESCE(B.point" . $counter . ", 0) AS point_new" . $counter;
            // 既存会員鑑定回数
            $sql7 .= "  ,A.counts" . $counter . " - COALESCE(B.counts" . $counter . ", 0) AS count_old" . $counter;
            // 既存会員鑑定時間
            $sql8 .= "  ,A.exec_time" . $counter . " - COALESCE(B.exec_time" . $counter . ", '00:00') AS exec_time_old" . $counter;
            // 既存会員鑑定売上
            $sql9 .= "  ,A.point" . $counter . " - COALESCE(B.point" . $counter . ", 0) AS point_old" . $counter;

            $counter += 1;
            $workDate2->addDays(1);
        }

        $sql .= $sql1;
        $sql .= $sql2;
        $sql .= $sql3;
        $sql .= $sql4;
        $sql .= $sql5;
        $sql .= $sql6;
        $sql .= $sql7;
        $sql .= $sql8;
        $sql .= $sql9;

        $sql .= "  ,0 AS misyunou";
        $sql .= " FROM";

        for ($i = 0; $i < 2; $i++) {
            $sql .= " (";
            $sql .= " SELECT";
/*
// comment out by ohneta
            $sql .= "    appraisers.site_id";
            $sql .= "   ,appraisers.id AS appraiser_id";
            $sql .= "   ,appraisers.name";
            $sql .= "   ,appraiser_points.point as unit_point";             // 鑑定価格(分) 
    // 累計
            $sql .= "   ,SUM(reservations.counts) AS counts";                                                           // 累計鑑定数
            $sql .= "   ,SUM(histories.exec_time) AS exec_time";                                                        // 累計鑑定時間
            $sql .= "   ,SUM(reservations.point) + SUM(saleschanges.sales_amount) as point";                            // 累計売上
            $sql .= "   ,justify_hours(CAST(SUM(appraiser_worktimes.minutes) || 'minute' as interval)) AS worktime";    // 累計待機時間
    // 平均
            $sql .= "   ,AVG(reservations.counts) AS avg_counts";                                                           // 平均鑑定数
            $sql .= "   ,AVG(histories.exec_time) AS avg_exec_time";                                                        // 平均鑑定時間
            $sql .= "   ,AVG(reservations.point) + AVG(saleschanges.sales_amount) AS avg_point";                            // 平均売上
            $sql .= "   ,justify_hours(CAST(AVG(appraiser_worktimes.minutes) || 'minute' as interval)) AS avg_worktime";    // 平均待機時間
*/


            $sql .= "    appraisers.site_id";
            $sql .= "   ,appraisers.id AS appraiser_id";
            $sql .= "   ,appraisers.name";
            $sql .= "   ,appraiser_points.point_sales AS unit_point";       // 鑑定価格(分) mod. by ohneta
            $sql .= "   ,appraiser_points.point_purchase AS unit_point_purchase";      // add. by ohneta

    // 累計
            $sql .= "   ,SUM(reservations.counts) AS counts";                                                           // 累計鑑定数
            $sql .= "   ,SUM(histories.exec_time) AS exec_time";                                                        // 累計鑑定時間
$sql .= "   ,SUM(reservations.point) AS point";                                                             // 累計売上 (TODO: saleschanges.sales_amountが抜けてる)
            $sql .= "   ,justify_hours(CAST(SUM(appraiser_worktimes.minutes) || 'minute' as interval)) AS worktime";    // 累計待機時間

$sql .= "   ,SUM(saleschanges.sales_amount) AS sales_amountX";

    // 平均
            $sql .= "   ,AVG(reservations.counts) AS avg_counts";                                                           // 平均鑑定数
            $sql .= "   ,AVG(histories.exec_time) AS avg_exec_time";                                                        // 平均鑑定時間
            $sql .= "   ,AVG(reservations.point) AS avg_point";                                                             // 平均売上
            $sql .= "   ,justify_hours(CAST(AVG(appraiser_worktimes.minutes) || 'minute' as interval)) AS avg_worktime";    // 平均待機時間

            $sqlCount = '';
            $sqlTimes = '';
            $sqlPoint = '';
            $sqlWait  = '';
            $counter = 0;

            $workDate3 = $workDate->copy();
            while ($workDate3->between(Carbon::parse($start_date->format('Y-m-d')), Carbon::parse($end_date->format('Y-m-d')))) {
                $day = $workDate3->format('Y-m-d');

                // 日ごとの回数
                $sqlCount .= "   ,SUM(case when date_trunc('day', reservations.start_date) = '" . $day . "' then reservations.counts else 0 end) as counts" . $counter;

                // 日ごとの鑑定時間
                $sqlTimes .= "   ,SUM(case when date_trunc('day', reservations.start_date) = '" . $day . "' then histories.exec_time else '00:00' end) as exec_time" . $counter;

                // 日ごとの鑑定売上
                $sqlPoint .= "   ,SUM(case when date_trunc('day', reservations.start_date) = '" . $day . "' then reservations.point else 0 end) as point" . $counter;

                $counter += 1;
                $workDate3->addDays(1);
            }

            $sql .= $sqlCount;
            $sql .= $sqlTimes;
            $sql .= $sqlPoint;

            $sql .= " FROM";
            $sql .= "   appraisers";
            $sql .= "   LEFT JOIN";
            $sql .= "     appraiser_points";
            $sql .= "   ON";
            $sql .= "     appraiser_points.site_id = appraisers.site_id";
            $sql .= "     AND appraiser_points.appraiser_id = appraisers.id";
//            $sql .= "     AND appraiser_points.kind = 0";
            $sql .= "     AND appraiser_points.kind = 1001";    // mod. by ohneta
            $sql .= "   LEFT JOIN";
            $sql .= "     appraiser_worktimes";
            $sql .= "   ON";
            $sql .= "      appraiser_worktimes.site_id = appraisers.site_id";
            $sql .= "     AND appraiser_worktimes.appraiser_id = appraisers.id";
            $sql .= "     AND date_trunc('day', target_date) BETWEEN '" . $start_date->format('Y-m-d') . "' AND '" . $end_date->format('Y-m-d') . "'";
            $sql .= "   LEFT JOIN (";
            $sql .= "     SELECT ";
            $sql .= "        site_id";
            $sql .= "       ,appraiser_id";
            $sql .= "       ,start_date";
            $sql .= "       ,count(*) as counts";
            $sql .= "       ,SUM(point) as point";
            $sql .= "     FROM (";
            $sql .= "       SELECT";
            $sql .= "          site_id";
            $sql .= "         ,status";
            $sql .= "         ,appraiser_id";
            $sql .= "         ,date_trunc('day', start_date) as start_date";
            $sql .= "         ,point";
            $sql .= "         ,user_id";
            $sql .= "       FROM";
            $sql .= "         reservations";
            $sql .= "       WHERE ";
            $sql .= "         status = 3";
            $sql .= "         AND start_date BETWEEN '" . $start_date->format('Y-m-d') . "' AND '" . $end_date->format('Y-m-d') . "'";
            $sql .= "       UNION ALL";
            $sql .= "       SELECT";
            $sql .= "          site_id";
            $sql .= "         ,status";
            $sql .= "         ,appraiser_id";
            $sql .= "         ,date_trunc('day', post_date) as start_date";
            $sql .= "         ,point";
            $sql .= "         ,user_id";
            $sql .= "       FROM";
            $sql .= "         rightnows";
            $sql .= "       WHERE ";
            $sql .= "         status = 3";
            $sql .= "         AND post_date BETWEEN '" . $start_date->format('Y-m-d') . "' AND '" . $end_date->format('Y-m-d') . "'";
            $sql .= "       ) reservations";

            if ($i == 1) {
                $sql .= $sqlUsers;
                $sql .= " users.user_id = reservations.user_id";
            }

            $sql .= "     GROUP BY ";
            $sql .= "        site_id";
            $sql .= "       ,appraiser_id";
            $sql .= "       ,start_date";
            $sql .= "   ) reservations";
            $sql .= "   ON ";
            $sql .= "     reservations.site_id = appraisers.site_id";
            $sql .= "     AND reservations.appraiser_id = appraisers.id";
            $sql .= "     AND reservations.start_date = COALESCE(appraiser_worktimes.target_date,reservations.start_date)";
            $sql .= "   LEFT JOIN (";
            $sql .= "     SELECT ";
            $sql .= "        site_id";
            $sql .= "       ,appraiser_id";
            $sql .= "       ,date_trunc('day', sales_date) as sales_date";
            $sql .= "       ,SUM(sales_amount) sales_amount";
            $sql .= "     FROM";
            $sql .= "       saleschanges";
            $sql .= "     WHERE";
            $sql .= "       sales_date BETWEEN '" . $start_date->format('Y-m-d') . "' AND '" . $end_date->format('Y-m-d') . "'";
            $sql .= "     GROUP BY";
            $sql .= "        site_id";
            $sql .= "       ,appraiser_id";
            $sql .= "       ,date_trunc('day', sales_date)";
            $sql .= "   ) AS saleschanges";
            $sql .= "   ON";
            $sql .= "     saleschanges.site_id = appraisers.site_id";
            $sql .= "     AND saleschanges.appraiser_id = appraisers.id";
            $sql .= "     AND saleschanges.sales_date = reservations.start_date";
            $sql .= "   LEFT JOIN (";
            $sql .= "     SELECT";
            $sql .= "        site_id";
            $sql .= "       ,appraiser_id";
            $sql .= "       ,date_trunc('day', start_date) as start_date";
            $sql .= "       ,SUM(end_date - start_date) exec_time";
            $sql .= "     FROM";
            $sql .= "       histories";

            if ($i == 1) {
                $sql .= $sqlUsers;
                $sql .= " users.user_id = histories.user_id";
            }

            $sql .= "     WHERE";
            $sql .= "       start_date BETWEEN '" . $start_date->format('Y-m-d') . "' AND '" . $end_date->format('Y-m-d') . "'";
            $sql .= "     GROUP BY";
            $sql .= "        site_id";
            $sql .= "       ,appraiser_id";
            $sql .= "       ,date_trunc('day', start_date) ";
            $sql .= "   ) histories";
            $sql .= "   ON";
            $sql .= "     histories.site_id = appraisers.site_id";
            $sql .= "     AND histories.appraiser_id = appraisers.id";
            $sql .= "     AND histories.start_date = reservations.start_date";
            $sql .= " WHERE (true)";
            $sql .= "   AND appraisers.site_id = " . $auth->site_id;

            if ($auth->authority == 3) {
                $sql .= "   AND appraisers.appraiser_office_code = '" . $auth->appraiser_office_code . "'";
            }

            $sql .= " GROUP BY";
            $sql .= "    appraisers.site_id";
            $sql .= "   ,appraisers.id";
            $sql .= "   ,appraisers.name";
//            $sql .= "   ,appraiser_points.point";
            $sql .= "   ,appraiser_points.point_sales";      // mod. by ohneta
            $sql .= "   ,appraiser_points.point_purchase";      // add by ohneta

            if ($i == 0) {
                $sql .= " ) AS A";
                $sql .= " LEFT JOIN";
            } else {
                $sql .= " ) AS B";
                $sql .= " ON";
                $sql .= "   A.site_id = B.site_id";
                $sql .= "   AND A.appraiser_id = B.appraiser_id";
            }
        }

        $records = DB::connection('pgsql2')->select($sql);

        foreach ($records as $req) {

            $ua = UserAppraiser::query()
                ->where('site_id', $req->site_id)
                ->where('appraiser_id', $req->appraiser_id)->firstOrNew();

            $user = User::query()->findOrFail($ua->user_id);

            $req->login_id = $user->login_id;

        }

        return $records;
    }

    /**
     * サイト全体情報
     *
     * @param Request $request
     * @return void
     */
    private function createTypeThree(Request $request)
    {
        // 認証情報取得
        $auth = Auth::user();

        $now = new Carbon();
        $start_date = new Carbon($request->get('start_date') ?? $now->copy()->addDays(-1)->format('Y-m-d'));
        $end_date = new Carbon($request->get('end_date') ?? $now->format('Y-m-d'));
        $workDate = new Carbon($request->get('start_date') ?? $now->copy()->addDays(-1)->format('Y-m-d'));
        $counter = 0;

        if ($auth->authority == 4) {
            $adcodes = Adcode::query()
                ->where('site_id', $auth->site_id)
                ->where('adagency_code', $auth->adagency_code)
                ->pluck('ad_code');
        }

        $sql = '';
        $sql .= " SELECT ";
        $sql .= "    A.site_id";

        $sql1 = '';
        $sql2 = '';
        $sql3 = '';
        $sql4 = '';
        $sql5 = '';
        $sql6 = '';
        $sql7 = '';
        $sql8 = '';
        $sql9 = '';

        $workDate2 = $workDate->copy();
        while ($workDate2->between(Carbon::parse($start_date->format('Y-m-d')), Carbon::parse($end_date->format('Y-m-d')))) {

            // 累積会員鑑定回数
            $sql1 .= "  ,A.counts" . $counter;
            // 累積会員鑑定時間
            $sql2 .= "  ,A.exec_time" . $counter;
            // 累積会員鑑定売上
            $sql3 .= "  ,A.point" . $counter;
            // 新規会員鑑定回数
            $sql4 .= "  ,COALESCE(B.counts" . $counter . ", 0) AS count_new" . $counter;
            // 既存会員鑑定時間
            $sql5 .= "  ,COALESCE(B.exec_time" . $counter . ", '00:00') AS exec_time_new" . $counter;
            // 既存会員鑑定売上
            $sql6 .= "  ,COALESCE(B.point" . $counter . ", 0) AS point_new" . $counter;
            // 既存会員鑑定回数
            $sql7 .= "  ,A.counts" . $counter . " - COALESCE(B.counts" . $counter . ", 0) AS count_old" . $counter;
            // 既存会員鑑定時間
            $sql8 .= "  ,A.exec_time" . $counter . " - COALESCE(B.exec_time" . $counter . ", '00:00') AS exec_time_old" . $counter;
            // 既存会員鑑定売上
            $sql9 .= "  ,A.point" . $counter . " - COALESCE(B.point" . $counter . ", 0) AS point_old" . $counter;

            $counter += 1;
            $workDate2->addDays(1);
        }

        $sql .= $sql1;
        $sql .= $sql2;
        $sql .= $sql3;
        $sql .= $sql4;
        $sql .= $sql5;
        $sql .= $sql6;
        $sql .= $sql7;
        $sql .= $sql8;
        $sql .= $sql9;

        $sql .= "  ,0 AS misyunou"; // 累計未収額
        $sql .= "  ,0 AS cost"; // 累計広告費
        $sql .= "  ,0 AS sales_rate"; // 累計売上率
        $sql .= "  ,0 AS recovery_rate"; // 累計回収率
        $sql .= " FROM";

        for ($i = 0; $i < 2; $i++) {

            $qUsers = Adlink::query()->where('site_id', $auth->site_id);

            if ($auth->authority == 4) {
                if ($i == 0) {
                    $users = $qUsers->whereIn('ad_code', $adcodes)->pluck('user_id');
                }
                else {
                    $users = $qUsers->whereIn('ad_code', $adcodes)->whereBetween('add_date', [$start_date, $end_date])->pluck('user_id');
                }
            } else {
                $users = $qUsers->whereBetween('add_date', [$start_date, $end_date])->pluck('user_id');
            }

            $userStr = $users->implode(',');

            if (empty($userStr)) {
                $userStr = '0';
            }

            $sqlUsers  = " INNER JOIN";
            $sqlUsers .= " (";
            $sqlUsers .= "  SELECT";
            $sqlUsers .= "    unnest(ARRAY[" . $userStr . "]) AS user_id";
            $sqlUsers .= " ) AS users";
            $sqlUsers .= "   ON";

            $sql .= " (";
            $sql .= " SELECT";
            $sql .= "    reservations.site_id";
            $sql .= "   ,SUM(reservations.counts) AS counts";
            $sql .= "   ,SUM(histories.exec_time) AS exec_time";
            $sql .= "   ,SUM(reservations.point) + SUM(saleschanges.sales_amount) AS point";
            $sql .= "   ,AVG(reservations.counts) AS avg_counts";
            $sql .= "   ,AVG(histories.exec_time) AS avg_exec_time";
            $sql .= "   ,AVG(reservations.point) + AVG(saleschanges.sales_amount) AS avg_point";

            $sqlCount = '';
            $sqlTimes = '';
            $sqlPoint = '';
            $counter = 0;

            $workDate3 = $workDate->copy();
            while ($workDate3->between(Carbon::parse($start_date->format('Y-m-d')), Carbon::parse($end_date->format('Y-m-d')))) {
                $day = $workDate3->format('Y-m-d');

                // 日ごとの累積回数
                $sqlCount .= "   ,SUM(case when date_trunc('day', reservations.start_date) = '" . $day . "' then reservations.counts else 0 end) AS counts" . $counter;

                // 日ごとの累積鑑定時間
                $sqlTimes .= "   ,SUM(case when date_trunc('day', reservations.start_date) = '" . $day . "' then histories.exec_time else '00:00' end) AS exec_time" . $counter;

                // 日ごとの累積鑑定売上
                $sqlPoint .= "   ,SUM(case when date_trunc('day', reservations.start_date) = '" . $day . "' then reservations.point else 0 end) AS point" . $counter;

                $counter += 1;
                $workDate3->addDays(1);
            }

            $sql .= $sqlCount;
            $sql .= $sqlTimes;
            $sql .= $sqlPoint;

            $sql .= " FROM";
            $sql .= "   appraisers";
            $sql .= "   LEFT JOIN";
            $sql .= "   appraiser_points";
            $sql .= "   ON";
            $sql .= "     appraiser_points.site_id = appraisers.site_id";
            $sql .= "     AND appraiser_points.appraiser_id = appraisers.id";
            $sql .= "     AND appraiser_points.kind = 0";
            $sql .= "   INNER JOIN (";
            $sql .= "     SELECT ";
            $sql .= "        site_id";
            $sql .= "       ,appraiser_id";
            $sql .= "       ,start_date";
            $sql .= "       ,count(*) as counts";
            $sql .= "       ,SUM(point) as point";
            $sql .= "     FROM (";
            $sql .= "       SELECT";
            $sql .= "          site_id";
            $sql .= "         ,status";
            $sql .= "         ,appraiser_id";
            $sql .= "         ,date_trunc('day', start_date) as start_date";
            $sql .= "         ,point";
            $sql .= "         ,user_id";
            $sql .= "       FROM";
            $sql .= "         reservations";
            $sql .= "       WHERE ";
            $sql .= "         status = 3";
            $sql .= "         AND start_date >= '" . $start_date->format('Y-m-d') . "'"; // 指定期間の開始日
            $sql .= "       UNION ALL";
            $sql .= "       SELECT";
            $sql .= "          site_id";
            $sql .= "         ,status";
            $sql .= "         ,appraiser_id";
            $sql .= "         ,date_trunc('day', post_date) as start_date";
            $sql .= "         ,point";
            $sql .= "         ,user_id";
            $sql .= "       FROM";
            $sql .= "         rightnows";
            $sql .= "       WHERE ";
            $sql .= "         status = 3";
            $sql .= "         AND post_date >= '" . $start_date->format('Y-m-d') . "'";
            $sql .= "       ) reservations";

            if ($auth->authority == 4 && $i == 0) {
                $sql .= $sqlUsers;
                $sql .= " users.user_id = reservations.user_id";
            }

            if ($i == 1) {
                $sql .= $sqlUsers;
                $sql .= " users.user_id = reservations.user_id";
            }

            $sql .= "     GROUP BY ";
            $sql .= "        site_id";
            $sql .= "       ,appraiser_id";
            $sql .= "       ,start_date";
            $sql .= "   ) reservations";
            $sql .= "   ON ";
            $sql .= "     reservations.site_id = appraisers.site_id";
            $sql .= "     AND reservations.appraiser_id = appraisers.id";
            $sql .= "   LEFT JOIN (";
            $sql .= "     SELECT ";
            $sql .= "        site_id";
            $sql .= "       ,appraiser_id";
            $sql .= "       ,date_trunc('day', sales_date) AS sales_date";
            $sql .= "       ,SUM(sales_amount) AS sales_amount";
            $sql .= "     FROM";
            $sql .= "       saleschanges";
            $sql .= "     WHERE";
            $sql .= "       sales_date >= '" . $start_date->format('Y-m-d') . "'";
            $sql .= "     GROUP BY";
            $sql .= "        site_id";
            $sql .= "       ,appraiser_id";
            $sql .= "       ,date_trunc('day', sales_date)";
            $sql .= "   ) saleschanges";
            $sql .= "   ON";
            $sql .= "     saleschanges.site_id = appraisers.site_id";
            $sql .= "     AND saleschanges.appraiser_id = appraisers.id";
            $sql .= "     AND saleschanges.sales_date = reservations.start_date";
            $sql .= "   LEFT JOIN (";
            $sql .= "     SELECT";
            $sql .= "        site_id";
            $sql .= "       ,appraiser_id";
            $sql .= "       ,date_trunc('day', start_date) as start_date";
            $sql .= "       ,SUM(end_date - start_date) exec_time";
            $sql .= "     FROM";
            $sql .= "       histories";

            if ($auth->authority == 4 && $i == 0) {
                $sql .= $sqlUsers;
                $sql .= " users.user_id = histories.user_id";
            }

            if ($i == 1) {
                $sql .= $sqlUsers;
                $sql .= " users.user_id = histories.user_id";
            }

            $sql .= "     WHERE";
            $sql .= "       start_date >= '" . $start_date->format('Y-m-d') . "'";
            $sql .= "     GROUP BY";
            $sql .= "        site_id";
            $sql .= "       ,appraiser_id";
            $sql .= "       ,date_trunc('day', start_date) ";
            $sql .= "   ) histories";
            $sql .= "   ON";
            $sql .= "     histories.site_id = appraisers.site_id";
            $sql .= "     AND histories.appraiser_id = appraisers.id";
            $sql .= "     AND histories.start_date = reservations.start_date";
            $sql .= " WHERE (true)";
            $sql .= "   AND appraisers.site_id = " . $auth->site_id;

            if ($auth->authority == 3) {
                $sql .= "   AND appraisers.appraiser_office_code = '" . $auth->appraiser_office_code . "'";
            }

            $sql .= " GROUP BY";
            $sql .= "    reservations.site_id";

            if ($i == 0) {
                $sql .= " ) AS A";
                $sql .= " LEFT JOIN";
            } else {
                $sql .= " ) AS B";
                $sql .= " ON";
                $sql .= "   A.site_id = B.site_id";
            }
        }

        $records = DB::connection('pgsql2')->select($sql);

        foreach ($records as $req) {

            $adSql = '';
            $adSql .= " SELECT";
            $adSql .= "   adlinks.site_id";
            $adSql .= "   ,SUM(adcodes.unit_price) AS cost";
            $adSql .= "   ,COUNT(distinct adlinks.id) AS counts";
            $adSql .= "   ,SUM(pointhistories.point)  as point";
            $adSql .= " FROM";
            $adSql .= "   adlinks";
            $adSql .= "   LEFT JOIN";
            $adSql .= "     adcodes";
            $adSql .= "   ON (true)";
            $adSql .= "     AND adcodes.site_id = adlinks.site_id";
            $adSql .= "     AND adcodes.ad_code = adlinks.ad_code";
            $adSql .= "   LEFT JOIN";
            $adSql .= "     pointhistories";
            $adSql .= "   ON (true)";
            $adSql .= "     AND pointhistories.site_id = adlinks.site_id";
            $adSql .= "     AND pointhistories.user_id = adlinks.user_id";
            $adSql .= "     AND pointhistories.reason = 'spend'";
            $adSql .= "     AND date_trunc('day', pointhistories.add_date) BETWEEN '" . $start_date->format('Y-m-d') . "' AND '" . $end_date->format('Y-m-d') . "'";
            $adSql .= " WHERE (true)";
            $adSql .= "   AND date_trunc('day', adlinks.add_date) BETWEEN '" . $start_date->format('Y-m-d') . "' AND '" . $end_date->format('Y-m-d') . "'";
            $adSql .= "   AND adlinks.site_id = " . $req->site_id;
            $adSql .= " GROUP BY ";
            $adSql .= "   adlinks.site_id";

            $ads = DB::select($adSql);

            if (count($ads) > 0){
                $ad = $ads[0];
                $req->cost = $ad->cost;
                $req->sales_rate = $ad->cost / $ad->point;
                $req->recovery_rate = $ad->cost / ($ad->point - 0);
            }
        }

        return $records;
    }
}
