<?php

namespace App\Http\Controllers;

use App\Models\Appraiser;
use App\Models\AppraiserDispOrder;
use App\Models\Userinfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * 占い師表示順管理コントローラ
 */
class AppraiserDispOrderController extends Controller
{
    /**
     * 占い師表示順管理画面・初期処理
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        session([ 'typeOrder' => '' ]);

        return view('disporder.index');
    }

    /**
     * 占い師表示順管理画面・管理項目選択
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function selection(Request $request)
    {
        // 認証情報取得
        $auth = Auth::user();

        if (session()->has('queryParams')) {
            $sesQueryParams = json_decode(session('queryParams'));
        }

        // パラメータ取得
        // 並び順（プルダウン選択値）は、変更時のみパラメータを取得し、その他はセッション値を取得
        if ($request->has('type_order')) {
            $type_order = intval($request->get('type_order'));
            $sesQueryParams = '';
        } else {
            $type_order = session('typeOrder');
        }
        
        // 検索条件（プルダウン選択値）とキーワードは、設定時のみパラメータを取得、未設定時はセッションから取得
        if ($request->has('keyword')) {
            $keyword = $request->get('keyword');
        } else {
            if($sesQueryParams == ''){
                $keyword = '';
            } else {
                $keyword = $sesQueryParams->keyword;
            }
        }
        if ($request->has('type_search')) {
            $type_search = $request->get('type_search');
        } else {
            if($sesQueryParams == ''){
                $type_search = '';
            } else {
                $type_search = $sesQueryParams->type_search;
            }
        }

        $query = AppraiserDispOrder::query();
        $query->where('site_id', $auth->site_id)->where('disp_order_id', $type_order);

        $appraiser_ids = [];    // 表示対象となる占い師IDの一覧
        { // 検索条件から appraiser_ids に対象となる占い師IDの一覧を設定する
            $appraiserQuery = Appraiser::query()->with(['user', 'user.info']);
            $appraiserQuery->where('site_id', $auth->site_id);

            if (!$keyword || !$type_search) {   // 検索条件なし
                $appraiser_ids = $appraiserQuery->pluck('id')->toArray();
            } else {                            // 検索条件あり
                if ($type_search == 'actual_name') {            // 占い師名(DB:appraisers.name)で検索
                    $appraiserQuery->where('name', 'LIKE', "%$keyword%");
                    $appraiser_ids = $appraiserQuery->pluck('id')->toArray();
                } else if ($type_search == 'username') {        // 占い師のemail(DB:userinfos.email)で検索
                    $userByAppraisersQuery = Userinfo::query()->join('user_appraisers', 'user_appraisers.user_id', '=', 'userinfos.user_id');
                    $userByAppraisersQuery->where('email', 'LIKE', "%$keyword%");
                    $appraiser_ids = $userByAppraisersQuery->pluck('appraiser_id')->toArray();
                } else if ($type_search == 'phone_number') {    // 占い師のphoneno(DB:userinfos.占い師のphoneno)で検索
                    $userByAppraisersQuery = Userinfo::query()->join('user_appraisers', 'user_appraisers.user_id', '=', 'userinfos.user_id');
                    $userByAppraisersQuery->where('phoneno', 'LIKE', "%$keyword%");
                    $appraiser_ids = $userByAppraisersQuery->pluck('appraiser_id')->toArray();
                }
            }
        }
        $query->whereIn('appraiser_id', $appraiser_ids);

        $appraisers = $query->orderBy('appraiser_id')->paginate(20);

        $queryParams = array(
            'keyword' => $keyword,
            'type_search'  => $type_search
        );

        /* 選択値をセッションにセットして、selector側でセッションの値を見て判断する */
        if ($request->has('type_order')) {
            session([ 'typeOrder' => $request->get('type_order') ]);
        }
//ddd($appraisers);

        /* 検索キーをセッションにセットして、更新ボタン押下後に遷移した画面で使用 */
        session([ 'queryParams' => json_encode($queryParams) ]);

        /* 画面表示データをセッションにセットして、更新時のチェック用に利用する*/
        session([ 'appraisersList' => json_encode($appraisers->toArray()) ]);

        return view('disporder.list', compact('appraisers', 'queryParams'));
    }

    /**
     * 占い師表示順管理画面・表示順変更
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function changeOrder(Request $request)
    {
        $appraiserList = json_decode(session('appraisersList'));
        $now = Carbon::now();
        $cnt = 0;

        try {
            DB::beginTransaction();

            foreach($appraiserList->data as $appraiser) {
                $id = $appraiser->id;
                $order_input = $request->get('disporderNum-' . $cnt);

                if($appraiser->disp_order_num != $order_input){
                    $appraiserDispOrder = AppraiserDispOrder::query()->findOrFail($id);
                    $appraiserDispOrder->disp_order_num = $order_input;
                    $appraiserDispOrder->mod_date = $now;
                    $appraiserDispOrder->save();
                    unset($appraiserDispOrder);
                }

                $cnt++;
            }

            DB::commit();
        }
        catch (\Exception $e) {
            Log::info($e);
            DB::rollBack();
        }

        // 完了メッセージをフラッシュメッセージに設定し、リダイレクト
        session()->flash('flashmessage', '更新が完了しました。');
        return redirect(route('disporder.select'));
    }
}
