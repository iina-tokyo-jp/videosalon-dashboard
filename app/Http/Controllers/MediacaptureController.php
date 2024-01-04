<?php

namespace App\Http\Controllers;

// use App\Http\Requests\ChangeStatusRequest;
// use App\Models\Userinfo;
// use App\Models\Mediacapture;
// use App\Models\Appraiser;
// use App\Models\video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
// use Carbon\Carbon;

/**
 * 録画ビデオコントローラ
 *
 *
 */
class MediacaptureController extends Controller
{
    /**
     * 録画ビデオ一覧 画面表示
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $auth = Auth::user();   // 認証情報取得

        $param_experienced      = $request->get('experienced'); // 連続しか検索かどうか。
        $param_user_id          = $request->get('user_id');
        $param_user_name        = $request->get('user_name');
        $param_appraiser_id     = $request->get('appraiser_id');
        $param_appraiser_name   = $request->get('appraiser_name');
        $param_start_date       = $request->get('start_date');
        if ($param_start_date != null) {
            $param_start_date = substr($param_start_date, 0, 10);
        }

        // メニューからの呼び出しの場合は検索結果を空にする
        $sqlRequires = ($param_experienced == null) ? "(1 = 0)" : "(1 = 1)";

        // 検索条件
        $sqlRequires .= ($param_user_id == null)        ? '' : " AND (v.user_id = $param_user_id) ";
        $sqlRequires .= ($param_user_name == null)      ? '' : " AND (v.user_name LIKE '%$param_user_name%') ";
        $sqlRequires .= ($param_appraiser_id == null)   ? '' : " AND (v.appraiser_id = $param_appraiser_id) ";
        $sqlRequires .= ($param_appraiser_name == null) ? '' : " AND (v.appraiser_name LIKE '%$param_appraiser_name%') ";
        $sqlRequires .= ($param_start_date == null)     ? '' : " AND ('{$param_start_date} 00:00:00' <= v.begin_date) AND (v.begin_date <= '{$param_start_date} 22:59:59') ";

        $sql = "	SELECT
                        v.id,
                        v.user_id, v.user_name,
                        v.appraiser_id, v.appraiser_name,
                        v.begin_date AS video_begin_date,
                        v.end_date AS video_end_date,
                        vm.status AS vm_status,
                        vm.*
                    FROM
                        video_mediacapturepipelines AS vm
                    INNER JOIN
                        videos AS v
                    ON
                        vm.video_id = v.id
                    AND
                        vm.site_id = {$auth->site_id}
                    AND $sqlRequires
                    ORDER BY v.id DESC	";
        $mediacaptures = DB::connection('pgsql2')->select($sql);

        $queryParams = array(
            'user_id'           => $param_user_id,
            'user_name'         => $param_user_name,
            'appraiser_id'      => $param_appraiser_id,
            'appraiser_name'    => $param_appraiser_name,
            'start_date'        => $param_start_date,
        );

        include("/home/videosalon/myname.php");
        $myname = $_MYNAME;
        return view('mediacapture.index', compact('mediacaptures', 'queryParams', 'myname'));
    }
}
