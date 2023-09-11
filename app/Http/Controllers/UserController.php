<?php

namespace App\Http\Controllers;

use App\Exports\UserLogsExport;
use App\Exports\UserExport;
use App\Http\Requests\UpdateUserPointRequest;
use App\Http\Requests\UpdateUserPointToleranceRequest;
use App\Models\Adlink;
use App\Models\Datalog;
use App\Models\PointHistory;
use App\Models\User;
use App\Models\UserAppraiser;
use App\Models\Userinfo;
use App\Models\UserLine;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\Appraiser;
use App\Models\AppraiserStandby;
use App\Models\AppraiserPoints;
use App\Models\AppraiserDispOrders;

/**
 * 利用者管理コントローラ
 */
class UserController extends Controller
{
    /**
     * 一覧画面表示
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request)
    {
        // 認証情報取得
        $auth = Auth::user();

        $keyword = $request->get('keyword');
        $type_search = $request->get('type_search');

        $query = Userinfo::query();
        $query->where('site_id', $auth->site_id);

        if ($keyword && $type_search) {
            if ($type_search == 'username') {
                $query->where('email', 'LIKE', "%$keyword%");
            } elseif ($type_search == 'actual_name') {
                $query->where('name', 'LIKE', "%$keyword%");
            } elseif ($type_search == 'phone_number') {
                $query->where('phoneno', 'LIKE', "%$keyword%");
            }
        }

        $userinfos = $query->orderBy('add_date', 'desc')->paginate(20);
        $queryParams = array(
            'keyword' => $keyword,
            'type_search'  => $type_search
        );
        return view('users.index', compact('userinfos', 'queryParams'));
    }

    /**
     * 利用者CSVダウンロード
     *
     * @param Request $request
     * @return void
     */
    public function downloadUsers(Request $request)
    {
        // 認証情報取得
        $auth = Auth::user();

        $keyword = $request->get('keyword');
        $type_search = $request->get('type_search');

        $query = Userinfo::query();
        $query->where('site_id', $auth->site_id);

        if ($keyword && $type_search) {
            if ($type_search == 'username') {
                $query->where('email', 'LIKE', "%$keyword%");
            } elseif ($type_search == 'actual_name') {
                $query->where('name', 'LIKE', "%$keyword%");
            } elseif ($type_search == 'phone_number') {
                $query->where('phoneno', 'LIKE', "%$keyword%");
            }
        }

        $userinfos = $query->orderBy('add_date', 'desc')->get();

        return Excel::download(new UserExport($userinfos), 'users.csv');
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

        // 利用者情報取得
        $userinfo = Userinfo::query()->findOrFail($id);

        // 利用者登録情報取得
        $user = User::query()->findOrFail($userinfo->user_id);

        // ログ取得
        $logs = Datalog::query()
            ->with('user')
            ->where('user_id', $userinfo->user_id)
            ->where('site_id', $auth->site_id)
            ->orderBy('add_date', 'desc')
            ->limit(10)
            ->get();

        // 最終ポイント履歴
        $pointHistory = PointHistory::query()
            ->where('user_id', $userinfo->user_id)
            ->where('site_id', $auth->site_id)
            ->orderBy('add_date', 'desc')
            ->limit(1)
            ->firstOrNew();

        // LINEID対応
        $userLine = UserLine::query()
            ->where('user_id', $userinfo->user_id)
            ->where('site_id', $auth->site_id)
            ->firstOrNew();

        // 占い師情報
        $appraiser = UserAppraiser::query()
            ->where('user_id', $userinfo->user_id)
            ->where('site_id', $auth->site_id)
            ->firstOrNew()
            ->appraiser;

// by ohneta
// print_r($appraiser);
// exit();

        // 広告からの流入
        $adlink = Adlink::query()
            ->where('user_id', $userinfo->user_id)
            ->where('site_id', $auth->site_id)
            ->firstOrNew();

        return view('users.show', compact('user', 'userinfo', 'logs', 'pointHistory', 'userLine', 'appraiser', 'adlink'));
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

        $userinfo = Userinfo::query()->findOrFail($id);

        $date = $request->get('date', 'all');
        $query = Datalog::query()
            ->with('user')
            ->where('user_id', $userinfo->user_id)
            ->where('site_id', $auth->site_id);
        if ($date == 'last_month') {
            $query->where('add_date', '>=', Carbon::now()->subMonth());
        } elseif ($date == 'last_3_month') {
            $query->where('add_date', '>=', Carbon::now()->subMonths(3));
        }

        $logs = $query->orderBy('add_date', 'desc')->get();
        return Excel::download(new UserLogsExport($logs), 'userlogs.csv');
    }

    /**
     * ポイント変更
     *
     * @param [type] $id
     * @param UpdateUserPointRequest $request
     * @return void
     */
    public function updatePoint($id, UpdateUserPointRequest $request)
    {
        $userinfo = Userinfo::query()->findOrFail($id);
        $type = $request->type;
        $point = intval($request->point);
        $chargereason = $request->chargereason;   // by ohneta 2022.08.25
        $now = Carbon::now();

        // 排他
        if (!$userinfo->checkExclusionary($request->mod_date)) {
            return back()->withErrors([
                'mod_date' => '更新済みエラー',
            ]);
        }

        DB::beginTransaction();
        try {
            // ポイント更新
            if ($type === 'addition') {
                $userinfo->point += $point;
            } else {
                $userinfo->point -= $point;
            }
            $userinfo->mod_date = $now;

            $userinfo->save();

            // ポイント履歴追加
            $history = new PointHistory();
            $history->fill([
                'site_id' => $userinfo->site_id,
                'user_id' => $userinfo->user_id,
                'user_name' => $userinfo->name,
                'user_account' => $userinfo->email,
                'point' => $type === 'addition' ? $point : -$point,
                //'reason' => 'charge',
                'reason' => $chargereason,   // by ohneta 2022.08.25
                'detail' => $request->reason ?? '',
                'add_date' => $now
            ]);
            $history->save();
            DB::commit();
        } catch (\Exception $e) {
            Log::info($e);
            DB::rollBack();
        }

        return back();
    }


    /**
     * 許容ポイント数変更
     *
     * @param [type] $id
     * @param UpdateUserPointToleranceRequest $request
     * @return 
     */
    public function updateUserPointTolerance($id, UpdateUserPointToleranceRequest $request)
    {
        $point_tolerance = $request->point_tolerance;
        $userinfo = Userinfo::query()->findOrFail($id);
        $point_tolerance = intval($request->point_tolerance);
        $now = Carbon::now();
        DB::beginTransaction();
        try {
            $userinfo->point_tolerance = $point_tolerance;
            $userinfo->mod_date = $now;

            $userinfo->save();
            DB::commit();
        } catch (\Exception $e) {
            Log::info($e);
            DB::rollBack();
        }

        return back();
    }


    /**
     * 利用者ステータス変更
     *
     * @param [type] $id
     * @param Request $request
     * @return void
     */
    public function changeStatus($id, Request $request)
    {
        $user = User::query()->findOrFail($id);
        $now = Carbon::now();

        {
            $state = intval($request->state);
            $actor_id = intval($request->actor_id);
            $auth = Auth::user();
            $query = Userinfo::query();
            $userinfo = $query->where('user_id', $id)->first();
            $imageUrl = 'https://videosalon.org/services/' . $auth->site_id . '/appraisers/portrait.png';

            // 占い師情報
            $appraiser = UserAppraiser::query()
                ->where('user_id', $userinfo->user_id)
                ->where('site_id', $auth->site_id)
                ->firstOrNew()
                ->appraiser;

            if (isset($appraiser->id)) {    // 占い師情報(appraiser)が設定されている
                // 占い師情報更新
                $appraiser->status = ($actor_id == 1) ? 0 : 1; // $state = 1 or 2
                $appraiser->save();

            } else {                        // 占い師情報(appraiser)が設定されていない

                if ($actor_id == 1) {  // 占い師無効
                    // なにもしない

                } else if ($actor_id == 2) {  // 占い師有効

                    $appraiser_id = 0;  // 新しく生成される 占い師ID

                    // 空の占い師情報を生成して利用者と関連付ける
                    DB::beginTransaction();
                    try {
                        // 占い師情報(appraiserテーブル)の追加
                        $appraiser_id = Appraiser::insertGetId([
                            'site_id' => $auth->site_id,
                            'status' => 0,  // 不可視
                            'name' => '占い師' . $userinfo->user_id,
                            'image' => $imageUrl
                        ]);
                        DB::commit();
                    } catch (\Exception $e) {
                        Log::info($e);
                        DB::rollBack();
                    }

                    DB::beginTransaction();
                    try {
                        // 利用者/占い師対応情報(user_appraisersテーブル)の追加
                        UserAppraiser::create([
                            'site_id' => $auth->site_id,
                            'user_id' => $userinfo->user_id,
                            'appraiser_id' => $appraiser_id,
                        ]);
                        DB::commit();
                    } catch (\Exception $e) {
                        Log::info($e);
                        DB::rollBack();
                    }

                    DB::beginTransaction();
                    try {
                        // 占い師待機状態(appraiser_standby)の追加
                        AppraiserStandby::create([
                            'appraiser_id' => $appraiser_id,
                            'site_id' => $auth->site_id,
                            'status' => 0,
                        ]);
                        DB::commit();
                    } catch (\Exception $e) {
                        Log::info($e);
                        DB::rollBack();
                    }

                    DB::beginTransaction();
                    try {
                        // 鑑定料金設定
                        AppraiserPoints::create([
                            'site_id' => $auth->site_id,
                            'appraiser_id' => $appraiser_id,
                            'kind' => 1001,
                            'point_purchase' => 300,
                            'point_sales' => 300,
                        ]);
                        AppraiserPoints::create([
                            'site_id' => $auth->site_id,
                            'appraiser_id' => $appraiser_id,
                            'kind' => 1011,
                            'point_purchase' => 300,
                            'point_sales' => 300,
                        ]);
                        DB::commit();
                    } catch (\Exception $e) {
                        Log::info($e);
                        DB::rollBack();
                    }

                    DB::beginTransaction();
                    try {
                        // appraiser_disp_orders テーブルのレコード追加
                        AppraiserDispOrders::create([
                            'site_id' => $auth->site_id,
                            'appraiser_id' => $appraiser_id,
                            'disp_order_id' => 2,
                            'disp_order_num' => 500,
                        ]);
                        AppraiserDispOrders::create([
                            'site_id' => $auth->site_id,
                            'appraiser_id' => $appraiser_id,
                            'disp_order_id' => 3,
                            'disp_order_num' => 500,
                        ]);
                        AppraiserDispOrders::create([
                            'site_id' => $auth->site_id,
                            'appraiser_id' => $appraiser_id,
                            'disp_order_id' => 4,
                            'disp_order_num' => 500,
                        ]);
                        AppraiserDispOrders::create([
                            'site_id' => $auth->site_id,
                            'appraiser_id' => $appraiser_id,
                            'disp_order_id' => 5,
                            'disp_order_num' => 500,
                        ]);
                        DB::commit();
                    } catch (\Exception $e) {
                        Log::info($e);
                        DB::rollBack();
                    }

                    // 占い師用作業フォルダーをつくる -- 実行環境のみ/開発環境では生成しない
                    $basepath = "/home/videosalon/www/services/";
                    if (file_exists($basepath)) {
                        $dirpath = "/home/videosalon/www/services/" . $auth->site_id . "/appraisers/" . $appraiser_id;
                        if (!file_exists($dirpath)) {
                            mkdir($dirpath, 0777);
                            chmod($dirpath, 0777);
                        }
                    }

                }
            }
        }

//Debugbar::info('break');

// mod-end by ohneta, 2022/06/19

        // 排他
        if (!$user->checkExclusionary($request->mod_date)) {
            return back()->withErrors([
                'mod_date' => '更新済みエラー',
            ]);
        }

        DB::beginTransaction();
        try {
            $state = intval($request->state);
            $actorId = intval($request->actor_id);
            $user->state = $state;
            $user->actor_id = $actorId;
            $user->mod_date = $now;

            // 更新
            $user->save();
            DB::commit();
        } catch (\Exception $e) {
            Log::info($e);
            DB::rollBack();
        }

        return back();
    }
}
