<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeStatusRequest;
use App\Models\Review;
use App\Models\Appraiser;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReviewController extends Controller
{
    /**
     * 一覧画面表示
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request) {
        // 認証情報取得
        $auth = Auth::user();

        $keyword = $request->get('keyword');
        $type_search = $request->get('type_search');

        $query = Review::query();
        $query->where('site_id', $auth->site_id);

        if ($auth->authority == 3) {
            $appraisers = Appraiser::query()
                ->where('site_id', $auth->site_id)
                ->where('appraiser_office_code', $auth->appraiser_office_code);
            $appraiser_ids = $appraisers->pluck('id')->toArray();
            $query->whereIn('appraiser_id', $appraiser_ids);
        }

        if ($keyword && $type_search) {
            if ($type_search == 'written') {
                $query->where('user_name', 'LIKE', "%$keyword%")
                    ->orWhere('user_account', 'LIKE', "%$keyword%");
            } elseif ($type_search == 'sendto') {
                $query->where('appraiser_name', 'LIKE', "%$keyword%")
                    ->orWhere('appraiser_account', 'LIKE', "%$keyword%");
            } elseif ($type_search == 'status') {
                $query->where('status', intval($keyword));
            } elseif ($type_search == 'authorizer') {
                $query->where('authorizer_name', 'LIKE', "%$keyword%")
                    ->orWhere('authorizer_account', 'LIKE', "%$keyword%");
            } elseif ($type_search == 'date') {
                // $keywordの形式yyyy-mm-dd
                $query->whereDate('pub_date', $keyword);
            }
        }
        $reviews = $query->orderBy('add_date', 'desc')->paginate(20);

        $status_options = [
            '-1' => '非掲載',
            '0' => '未確認',
            '1' => '掲載',
            '2' => '掲載(修正)'
        ];

        $queryParams = array(
            'keyword' => $keyword,
            'type_search'  => $type_search
        );

        return view('reviews.index', compact('reviews', 'status_options', 'queryParams'));
    }

    /**
     * レビュー状態一括変更
     *
     * @param ChangeStatusRequest $request
     * @return void
     */
    public function changeAllStatus(ChangeStatusRequest $request)
    {
        $ids = explode(',', $request->ids);
        $status = intval($request->status);
        $now = Carbon::now();

        // 認証情報取得
        $auth = Auth::user();

        DB::beginTransaction();
        try {
            foreach ($ids as $id) {
                $review = Review::query()->findOrFail($id);
                $review->status = $status;
                $review->authorizer_id = $auth->id;
                $review->authorizer_name = $auth->name;
                $review->authorizer_account = $auth->email;
                // 掲載日
                if ($status > 0) {
                    $review->pub_date = $now;
                }

                $review->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            Log::info($e);
            DB::rollBack();
        }

        return back();
    }

    /**
     * レビュー状態個別変更
     *
     * @param ChangeStatusRequest $request
     * @return void
     */
    public function changeEachStatus(ChangeStatusRequest $request)
    {
        $review = Review::query()->findOrFail($request->ids);
        $status = intval($request->status);
        $now = Carbon::now();

        // 認証情報取得
        $auth = Auth::user();

        DB::beginTransaction();
        try {
            $review->status = $status;
            $review->authorizer_id = $auth->id;
            $review->authorizer_name = $auth->name;
            $review->authorizer_account = $auth->email;
            $review->body = $request->body ?? '';
            $review->authorizer_report = $request->authorizer_report ?? '';

            // 掲載日
            if ($status > 0) {
                $review->pub_date = $now;
            }

            // 更新
            $review->save();
            DB::commit();
        } catch (\Exception $e) {
            Log::info($e);
            DB::rollBack();
        }

        return back();
    }
}
