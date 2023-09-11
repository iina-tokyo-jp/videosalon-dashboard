<?php

namespace App\Http\Controllers;

use App\Exports\AppraiserLogsExport;
use App\Exports\AppraiserExport;
use App\Http\Requests\UpdateUserPointRequest;
use App\Models\Adlink;
use App\Models\Appraiser;
use App\Models\AppraiserPoints;
use App\Models\Datalog;
use App\Models\PointHistory;
use App\Models\Type;
use App\Models\SalesChange;
use App\Models\User;    // edited by ohneta
use App\Models\Userinfo;
use App\Models\UserLine;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

use function PHPSTORM_META\map;

/**
 * 占い師管理コントローラ
 */
class AppraiserController extends Controller
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

        $keyword = $request->get('keyword');
        $type_search = $request->get('type_search');

        $query = Appraiser::query()->with(['user', 'user.info']);
        $query->where('site_id', $auth->site_id);

        if ($auth->authority == 3) {
            $query->where('appraiser_office_code', $auth->appraiser_office_code);
        }

        if ($keyword && $type_search) {

            if ($type_search == 'actual_name') {
                $query->where('name', 'LIKE', "%$keyword%");
            } else {
                $q = Userinfo::query()
                    ->join('user_appraisers', 'user_appraisers.user_id', '=', 'userinfos.user_id');
                if ($type_search == 'username') {
                    $q->where('email', 'LIKE', "%$keyword%");
                } elseif ($type_search == 'phone_number') {
                    $q->where('phoneno', 'LIKE', "%$keyword%");
                }
                $appraiser_ids = $q->pluck('appraiser_id')->toArray();
                $query->whereIn('id', $appraiser_ids);
            }
        }

        $appraisers = $query->orderBy('add_date', 'desc')->paginate(20);
        $queryParams = array(
            'keyword' => $keyword,
            'type_search'  => $type_search
        );

error_log("----------------");
error_log("AppraiserController - 1");
error_log(print_r($appraisers[0], true));
error_log("----------------");
error_log("AppraiserController - 2");
error_log(print_r($appraisers[0]->user, true));
error_log("----------------");
// error_log("AppraiserController - 3");
// error_log(print_r($appraisers[0]->user->info, true));
//exit();


        return view('appraisers.index', compact('appraisers', 'queryParams'));
    }

    /**
     * 占い師CSVダウンロード
     *
     * @param Request $request
     * @return void
     */
    public function downloadAppraisers(Request $request)
    {
        // 認証情報取得
        $auth = Auth::user();

        $keyword = $request->get('keyword');
        $type_search = $request->get('type_search');

        $query = Appraiser::query()->with(['user', 'user.info']);
        $query->where('site_id', $auth->site_id);

        if ($auth->authority == 3) {
            $query->where('appraiser_office_code', $auth->appraiser_office_code);
        }

        if ($keyword && $type_search) {

            if ($type_search == 'actual_name') {
                $query->where('name', 'LIKE', "%$keyword%");
            } else {
                $q = Userinfo::query()
                    ->join('user_appraisers', 'user_appraisers.user_id', '=', 'userinfos.user_id');
                if ($type_search == 'username') {
                    $q->where('email', 'LIKE', "%$keyword%");
                } elseif ($type_search == 'phone_number') {
                    $q->where('phoneno', 'LIKE', "%$keyword%");
                }
                $appraiser_ids = $q->pluck('appraiser_id')->toArray();
                $query->whereIn('id', $appraiser_ids);
            }
        }

        $appraisers = $query->orderBy('add_date', 'desc')->get();

        return Excel::download(new AppraiserExport($appraisers), 'appraisers.csv');
    }

    /**
     * 基本情報表示
     *
     * @param [type] $id
     * @return void
     */
    public function show($id)
    {
        // 認証情報取得
        $auth = Auth::user();

        // 占い師情報
        $appraiser = Appraiser::query()->findOrFail($id);
//error_log(print_r($appraiser, true));
        // ログ取得
        $logs = Datalog::query()
            ->with('user')
            ->where('user_id', $appraiser->user->user_id)
            ->where('site_id', $auth->site_id)
            ->orderBy('add_date', 'desc')
            ->limit(10)
            ->get();

        // 最終ポイント履歴
        $pointHistory = PointHistory::query()
            ->where('user_id', $appraiser->user->user_id)
            ->where('site_id', $auth->site_id)
            ->orderBy('add_date', 'desc')
            ->limit(1)
            ->firstOrNew();

        // LINEID対応
        $userLine = UserLine::query()
            ->where('user_id', $appraiser->user->user_id)
            ->where('site_id', $auth->site_id)
            ->firstOrNew();

        // 占い種類定義
        $types = Type::query()
            ->orderBy('sort_no', 'asc')
            ->get();

        $types = $types->pluck("name")->toArray();
        $appraiser_types = explode(',', $appraiser->types);
        $other_types = array_diff($appraiser_types, $types);

        // 広告からの流入
        $adlink = Adlink::query()
            ->where('user_id', $appraiser->user->user_id)
            ->where('site_id', $auth->site_id)
            ->firstOrNew();

// by ohneta 2022.08.27
        // 占い師ごとの占い種類別　ポイント単価(売上、仕入れ) appraiser_points
/*
        $appraiser_point_reserve = AppraiserPoints::query()
                    ->where('appraiser_id', $appraiser->id)
                    ->where('site_id', $auth->site_id)
                    ->where('kind', 1001)
                    ->firstOrNew();
        $appraiser_point_now = AppraiserPoints::query()
                    ->where('appraiser_id', $appraiser->id)
                    ->where('site_id', $auth->site_id)
                    ->where('kind', 1011)
                    ->firstOrNew();
*/
        $appraiser_point = AppraiserPoints::query()
                    ->where('appraiser_id', $appraiser->id)
                    ->where('site_id', $auth->site_id)
                    ->where('kind', 1001)
                    ->firstOrNew();
// Log::info(print_r($appraiser_point_reserve, true));
// Log::info(print_r($appraiser_point_now, true));


// rightnow_idから ChimeSDKのMeeting-Idを取得する
// {
// foreach ($logs) {
// }
// }

        return view(    'appraisers.show',
                        compact(
                            'appraiser',
                            'logs',
                            'pointHistory',
                            'userLine',
                            'types',
                            'appraiser_types',
                            'other_types',
                            'adlink',
                            'appraiser_point',
                            // 'appraiser_point_reserve',
                            // 'appraiser_point_now',
                        )   );
    }

    /**
     * ログダウンロード
     *
     * @param [type] $id
     * @param Request $request
     * @return void
     */
    public function downloadLogs($id, Request $request)
    {
        // 認証情報取得
        $auth = Auth::user();

        $appraiser = Appraiser::query()->findOrFail($id);

        $date = $request->get('date', 'all');
        $query = Datalog::query()
            ->with('user')
            ->where('user_id', $appraiser->user->user_id)
            ->where('site_id', $auth->site_id);

        if ($date == 'last_month') {
            $query->where('add_date', '>=', Carbon::now()->subMonth());
        } elseif ($date == 'last_3_month') {
            $query->where('add_date', '>=', Carbon::now()->subMonths(3));
        }

        $logs = $query->orderBy('add_date', 'desc')->get();
        return Excel::download(new AppraiserLogsExport($logs), 'appraiserlogs.csv');
    }

    /**
     * 売上額変更
     *
     * @param [type] $id
     * @param UpdateUserPointRequest $request
     * @return void
     */
    public function updatePoint($id, UpdateUserPointRequest $request)
    {
        $appraiser = Appraiser::query()->findOrFail($id);
        $type = $request->type;
        $salesAmount = intval($request->point);
        $now = Carbon::now();

        if ($type === 'addition') {
            $salesAmount = $salesAmount;
        } else {
            $salesAmount = $salesAmount * -1;
        }

        DB::beginTransaction();
        try {
            $salesChange = new SalesChange();
            $salesChange->fill([
                'site_id' => $appraiser->site_id,
                'appraiser_id' => $appraiser->id,
                'sales_date' => $now,
                'sales_amount' => $salesAmount,
                'detail' => $request->reason ?? '',
                'add_date' => $now
            ]);
            $salesChange->save();


            // edited by ohneta
            {
                $user = User::query()->findOrFail($appraiser->user->user_id);
                $userinfo = Userinfo::query()->where('user_id', $appraiser->user->user_id)->get();
                $datalog = new Datalog();
                $datalog->fill([
                    'site_id' => $appraiser->site_id,
                    'actor_kind' => 2,  // 占い師
                    // 本来なら user_xx はadminとする
                    'user_id' => $appraiser->user->user_id,
                    'user_name' => 'admin', //$userinfo[0]->name,
                    'user_account' => 'admin',  //$userinfo[0]->email,
                    'by_api' => 101,
                    'kind' => 10101,
                    'title' => "システムポイント付加(point=$salesAmount)",
                    'description' => "",
                    'gp4app' => '',
                    'add_date' => $now
                ]);
                $datalog->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            Log::info($e);
            DB::rollBack();
        }


        return back();
    }


    /**
     * configファイル(/home/videosalon/config.php)の内容から、占い師画像のディレクトリ/URLを返す。
     * configファイルが無い場合はデフォルト値を返す。
     * デフォルト値
     *     dir: "/home/videosalon/www/services/$site_id/appraisers/$appraiser_id"
     *     url: "https://videosalon.org/services/$site_id/appraisers/$appraiser_id"
     *
     * 備考:
     *   占い師画像ファイルの fullpath("/home/videosalon/www/services/$site_id/appraisers/$appraiser_id/portrait.png")のうち、
     *   "$site_id/appraisers/$appraiser_id" と、対応するURLを取得する。
     *
     * @param int $site_id サイトID
     * @param int $appraiser_id 占い師ID
     * @return array 'dir' => htdocs配下のディレクトリ名
     *               'url' => 画像ディレクトリのURL
     */
    private function getImageDirUrl($site_id, $appraiser_id)
    {
        $configFile = "/home/videosalon/config.php";
        $targetUrl = 'https://videosalon.org';      // 仮サーバURL
        if (file_exists($configFile)) {
            require_once($configFile);
            foreach ($_VIDEOSALON as $credential => $tmpVideosalon) {
                if ($site_id == $tmpVideosalon['site']['site_id']) {
                    $targetUrl = "https://" . $tmpVideosalon['api']['server'];
                }
            }
        }

        $imageDir = "$site_id/appraisers/$appraiser_id";
        $imageUrl = $targetUrl . "/services/" . $imageDir;

        return array('dir' => $imageDir, 'url' => $imageUrl);
    }

    /**
     * プロフィール変更
     *
     * @param [type] $id
     * @param Request $request
     * @return void
     */
    public function updateProfile($id, Request $request)
    {
        $appraiser = Appraiser::query()->findOrFail($id);
        $now = Carbon::now();

        // 排他
        if (!$appraiser->checkExclusionary($request->mod_date)) {
            return back()->withErrors([
                'mod_date' => '更新済みエラー',
            ]);
        }

        // 画像ファイルステータス(0：変更なし、1：変更、-1：削除)
        $imageStatus = $request->image_status;

        // 画像ファイル
        $file = $request->file('image_file');

        DB::beginTransaction();
        try {
            $dirUrl = $this->getImageDirUrl($appraiser->site_id, $id);
            $imageUrl = $dirUrl['url'];
            $savePath = $dirUrl['dir'];
            $fileName = 'portrait.png';
            $imageUrl = $dirUrl['url'] . "/" . $fileName;

            $types = '';

            if (!empty($request->types)) {
                $types = implode(',', $request->types);
            }
            if (!empty($request->other_types)) {
                if (!empty($types)) {
                    $types .= ',';
                }
                $types .= $request->other_types;
            }


            $appraiser->name = $request->name;
            $appraiser->gender = intval($request->gender);

// mod. by ohneta
            $appraiser->profile1 = ($request->profile1 == null) ? '' : $request->profile1;
            $appraiser->profile2 = ($request->profile2 == null) ? '' : $request->profile2;

            $appraiser->pref_no = intval($request->pref_no);
            if ($imageStatus != 0) {
                $appraiser->image = $imageUrl;
            }
            $appraiser->types = $types;

// mod. by ohneta 2023.07.26
            $appraiser->link        = ($request->link == null)      ? '' : $request->link;
            $appraiser->link_name   = ($request->link_name == null) ? '' : $request->link_name;
            $appraiser->link_url    = ($request->link_url == null)  ? '' : $request->link_url;


            $appraiser->mod_date = $now;

            $appraiser->save(); // 更新

            // ファイルの保存
            if (!is_null($file)) {
                $file->storeAs($savePath, $fileName, 'image_uploads');
            }

            DB::commit();

        } catch (\Exception $e) {
            Log::info($e);
            DB::rollBack();
        }


// by ohneta 2022.08.27
// ポイント単価の設定(DB::appraiser_points)
		$appraiser_point_reserve = AppraiserPoints::query()
                    ->where('appraiser_id', $id)
                    ->where('kind', 1001)
                    ->firstOrNew();

		DB::beginTransaction();
        try {
            //			$appraiser_point_reserve->point_purchase = $request->point_purchase_reserve;
            $appraiser_point_reserve->point_purchase = $request->point_purchase;
$appraiser_point_reserve->point_sales = $request->point_purchase;   // 2023.08.26 add. by ohneta
			$appraiser_point_reserve->mod_date = $now;
            $appraiser_point_reserve->save();
            DB::commit();
        } catch (\Exception $e) {
            Log::info($e);
            DB::rollBack();
        }

        $appraiser_point_now = AppraiserPoints::query()
                    ->where('appraiser_id', $id)
                    ->where('kind', 1011)
                    ->firstOrNew();
		DB::beginTransaction();
        try {
            //			$appraiser_point_now->point_purchase = $request->point_purchase_now;
            $appraiser_point_now->point_purchase = $request->point_purchase;
$appraiser_point_now->point_sales = $request->point_purchase;   // 2023.08.26 add. by ohneta
			$appraiser_point_now->mod_date = $now;
            $appraiser_point_now->save();
            DB::commit();
        } catch (\Exception $e) {
            Log::info($e);
            DB::rollBack();
        }


        return back();
    }

    /**
     * 占い師デビュー可否
     *
     * @param [type] $id
     * @param Request $request
     * @return void
     */
    public function changeStatus($id, Request $request)
    {
        $appraiser = Appraiser::query()->findOrFail($id);
        $now = Carbon::now();

// mod by ohneta
// print("<br >appraiser<br />\n");
// print_r($appraiser);
// exit();

        // 排他
        if (!$appraiser->checkExclusionary($request->mod_date)) {
            return back()->withErrors([
                'mod_date' => '更新済みエラー',
            ]);
        }

        DB::beginTransaction();
        try {
            $status = intval($request->status);
            $appraiser->status = $status;
            $appraiser->authorizer_report = $request->authorizer_report ?? '';
            $appraiser->mod_date = $now;

            // 更新
            $appraiser->save();
            DB::commit();
        } catch (\Exception $e) {
            Log::info($e);
            DB::rollBack();
        }

        return back();
    }
}
