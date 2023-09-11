<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeStatusRequest;
use App\Models\Adcode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 * メッセージ管理コントローラ
 */
class AdcodeController extends Controller
{
    /**
     * 一覧画面表示
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        // 認証情報取得
        $auth = Auth::user();

        $now = new Carbon();
        $keyword = $request->get('keyword');
        $type_search = $request->get('type_search');
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
        while ($workDate->between(Carbon::parse($start_date->format('Y-m-d')), Carbon::parse($end_date->format('Y-m-d')))) {

            $day = $workDate->format('Y-m-d');
            $days[] = $workDate->format('n/j');

            $sqlSum .= "   ,SUM(case when date_trunc('day', adlinks.add_date) = '" . $day . "' then 1 else 0 end) as sum" . $counter;
            $sqlPoint .= "   ,SUM(case when date_trunc('day', pointhistories.add_date) = '" . $day . "' then pointhistories.point else 0 end) as point" . $counter;

            $counter += 1;
            $workDate->addDays(1);
        }

        $sql .= $sqlSum;
        $sql .= $sqlPoint;
        $sql .= "   ,AVG(pointhistories.point) as point_average";
        $sql .= "   ,adcodes.unit_price * SUM(case when adlinks.add_date IS NOT NULL then 1 else 0 end) as cost_summary";
        $sql .= "   ,SUM(pointhistories.point)";
        $sql .= "    - (adcodes.unit_price * SUM(case when adlinks.add_date IS NOT NULL then 1 else 0 end)) as income_and_expenditure";
        $sql .= " FROM";
        $sql .= "   adcodes";
        $sql .= "   LEFT JOIN";
        $sql .= "     adlinks";
        $sql .= "   ON (true)";
        $sql .= "     AND adcodes.site_id = adlinks.site_id";
        $sql .= "     AND adcodes.ad_code = adlinks.ad_code";
        $sql .= "     AND date_trunc('day', adlinks.add_date) BETWEEN '" . $start_date->format('Y-m-d') . "' AND '" . $end_date->format('Y-m-d') . "'";
        $sql .= "   LEFT JOIN";
        $sql .= "     pointhistories";
        $sql .= "   ON (true)";
        $sql .= "     AND pointhistories.site_id = adlinks.site_id";
        $sql .= "     AND pointhistories.user_id = adlinks.user_id";
        $sql .= "     AND pointhistories.reason = 'spend'";
        $sql .= "     AND date_trunc('day',pointhistories.add_date) BETWEEN '" . $start_date->format('Y-m-d') . "' AND '" . $end_date->format('Y-m-d') . "'";
        $sql .= " WHERE (true)";
        $sql .= "   AND adcodes.site_id = " . $auth->site_id;

        if ($auth->authority == 4) {
            $sql .= "   AND adcodes.adagency_code = '" . $auth->adagency_code . "'";
        }

        if ($keyword && $type_search) {
            if ($type_search == 'status') {
                $sql .= " AND adcodes.status = " . intval($keyword);
            } elseif ($type_search == 'ad_code') {
                $sql .= " AND (adcodes.ad_code LIKE '%" . $keyword . "%'";
                $sql .= "      OR adcodes.site_name LIKE '%" . $keyword . "%'";
            }
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

        $adcodes = DB::select($sql);

        $status_options = [
            '0' => '無効',
            '1' => '有効',
        ];

        $queryParams = array(
            'keyword' => $keyword,
            'type_search'  => $type_search,
            'start_date' => $start_date,
            'end_date' => $end_date,
        );

        return view('adcodes.index', compact('adcodes', 'days', 'status_options', 'queryParams'));
    }

    /**
     * 広告状態一括変更
     *
     * @param ChangeStatusRequest $request
     * @return void
     */
    public function changeAllStatus(ChangeStatusRequest $request)
    {
        $ids = explode(',', $request->ids);
        $modDates = explode(',', $request->mod_dates);
        $status = intval($request->status);
        $now = Carbon::now();

        DB::beginTransaction();
        try {
            foreach ($ids as $index => $id) {
                $adcode = Adcode::query()->findOrFail($id);

                // 排他
                if (!$adcode->checkExclusionary($modDates[$index])) {
                    DB::rollBack();
                    return back()->withErrors([
                        'mod_date' => '更新済みエラー',
                    ]);
                }

                $adcode->status = $status;
                $adcode->mod_date = $now;

                $adcode->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            Log::info($e);
            DB::rollBack();
        }

        return back();
    }

    /**
     * 広告状態個別変更
     *
     * @param [type] $id
     * @param Request $request
     * @return void
     */
    public function changeEachStatus(ChangeStatusRequest $request)
    {
        // 認証情報取得
        $auth = Auth::user();

        $id = intval($request->ids);
        $status = intval($request->status);
        $now = Carbon::now();
        // 画像ファイルステータス(0：変更なし、1：変更、-1：削除)
        $imageStatus = $request->image_status;

        // 画像ファイル
        $file = $request->file('image_file');

        $fileName = 'banner.png';

        $imageUrl = 'https://videosalon.org/services/'; // 本番
        //$imageUrl = 'https://localhost/VideoSalon/services/';


        DB::beginTransaction();
        // 新規
        if ($id === -1) {
            try {
                $adcode = new Adcode();

                $adagency_code = '';

                if ($auth->authority == 4) {
                    $adagency_code = $auth->adagency_code;
                }

                $adcode->fill([
                    'site_id' => intval($auth->site_id),
                    'ad_code' => $request->ad_code,
                    'adagency_code' => $adagency_code,
                    'status' => $status,
                    'site_name' => $request->site_name,
                    'start_date' => $request->start_date,
                    'url' => $request->url,
                    'unit_price' => intval($request->unit_price),
                    'banner' => '',
                    'add_date' => $now,
                    'mod_date' => $now
                ]);

                $adcode->save();

                if (!is_null($file)) {
                    $workPath = $auth->site_id . '/ads/' . $adcode->id;
                    $imageUrl .= $workPath . '/' . $fileName;

                    $adcode->banner = $imageUrl;
                    $adcode->save();
                }

                DB::commit();
            } catch (\Exception $e) {
                Log::info($e);
                DB::rollBack();
                $file = null;
            }
        }
        // 更新
        else {
            $adcode = Adcode::query()->findOrFail($request->ids);
            $modDates = $request->mod_dates;

            // 排他
            if (!$adcode->checkExclusionary($modDates)) {
                return back()->withErrors([
                    'mod_date' => '更新済みエラー',
                ]);
            }

            $workPath = $auth->site_id . '/ads/' . $id;
            $imageUrl .= $workPath . '/' . $fileName;

            if (is_null($file)) {
                $imageUrl = '';
            }

            try {
                $adcode->site_id = intval($auth->site_id);
                $adcode->ad_code = $request->ad_code;
                $adcode->status = $status;
                $adcode->site_name = $request->site_name;
                $adcode->start_date = $request->start_date;
                $adcode->url = $request->url;
                $adcode->unit_price = intval($request->unit_price);
                if ($imageStatus != 0) {
                    $adcode->banner = $imageUrl;
                }
                $adcode->mod_date = $now;

                // 更新
                $adcode->save();
                DB::commit();
            } catch (\Exception $e) {
                Log::info($e);
                DB::rollBack();
                $file = null;
            }
        }

        // ファイルの保存
        if (!is_null($file)) {
            $savePath = $workPath;
            $file->storeAs($savePath, $fileName, 'image_uploads');
        }

        return back();
    }
}
