<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeStatusRequest;
use App\Models\SystemInfo;
// use App\Models\Appraiser;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RankingController extends Controller
{
    //------------------------------------------------
    /**
     * ランキング
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request) {
        // 認証情報取得
        $auth = Auth::user();

        session([ 'typeManage' => '' ]);
        return view('rankings.index');
    }

    //------------------------------------------------
    /**
     * ランキング管理画面・管理項目選択
     *
     * @param Request $request
     * @return Application|Factory|View
     */
    public function selection(Request $request)
    {
        // 認証情報取得
        $auth = Auth::user();

        $displayTo = '';
        switch ($request->get('type')) {
            case 'dayofweek':
                $displayTo = 'rankings.dayofweek';
                break;
            case 'weekly':
                $displayTo = 'rankings.weekly';
                break;
            case 'monthly':
                $displayTo = 'rankings.monthly';
                break;
            case 'recommended':
                $displayTo = 'rankings.recommended';
                break;
        }

        // 選択されたセッションにセットして、selector側でセッションの値を見て判断する
        session([ 'typeManage' => $request->get('type') ]);
        switch ($request->get('type')) {
            case 'dayofweek':
                return $this->dayofweek($request);
                break;
            case 'weekly':
                return $this->weekly($request);
                break;
            case 'monthly':
                return $this->monthly($request);
                break;
            case 'recommended':
                return $this->recommended($request);
                break;
        }
    }

    /**
     * 集計開始曜日の設定
     */
    public function dayofweek(Request $request)
    {
        // 認証情報取得
        $auth = Auth::user();

        $dayofweek = $request->get('dayofweek');
        if (isset($dayofweek)) {
            $sql = "UPDATE system_info SET dayofweek = $dayofweek WHERE site_id = {$auth->site_id}";
            DB::connection('pgsql2')->update($sql);
        } else {
            $system_info = $this->getSystemInfo($auth->site_id);
            $dayofweek = $system_info->dayofweek;
        }

        return view('rankings.dayofweek', ['dayofweek' => $dayofweek]);
    }

    //------------------------------------------------
    /**
     * 週間ランキング
     */
    public function weekly(Request $request)
    {
        // 認証情報取得
        $auth = Auth::user();

        $select_weeklyrank = $request->get('select_weeklyrank');
        if (isset($select_weeklyrank)) {
            $this->setSystemInfoWeeklyRank($select_weeklyrank, $auth->site_id);
        }
        $system_info = $this->getSystemInfo($auth->site_id);
        $theme_id = $system_info->weeklyrank;

        $works_appraiser_lists = null;
        $csv = $request->get('csv');
        if (isset($csv)) {
            $works_appraiser_lists = $this->getWorkAppraiserListsByCSV($theme_id, $csv, $auth->site_id);
        }
        if ($works_appraiser_lists == null) {
            $works_appraiser_lists = $this->getWorkAppraiserLists($theme_id, $auth->site_id);
        }

        return view('rankings.weekly', ['weeklyrank' => $system_info->weeklyrank, 'works_appraiser_lists' => $works_appraiser_lists]);
    }

    /**
     * 週間ランキングの更新
     *
     * @param Request $request
     *   ランキングは $requestに含まれる csv文字列
     * @return view()の戻り値
     */
    public function weeklyUpdate(Request $request)
    {
        // 認証情報取得
        $auth = Auth::user();

        $system_info = $this->getSystemInfo($auth->site_id);
        $theme_id = $system_info->weeklyrank;

        $csv = $request->get('csv');
        $this->setWorkAppraiserListsByCSV($theme_id, $csv, $auth->site_id);
        $works_appraiser_lists = $this->getWorkAppraiserLists($theme_id, $auth->site_id);

        $this->replaceAppraiserWorkToList($theme_id, 1, $auth->site_id);

        return view('rankings.weekly', ['weeklyrank' => $system_info->weeklyrank, 'works_appraiser_lists' => $works_appraiser_lists]);
    }

    //------------------------------------------------
    /**
     * 月間ランキング
     */
    public function monthly(Request $request)
    {
        // 認証情報取得
        $auth = Auth::user();

        $select_monthlyrank = $request->get('select_monthlyrank');
        if (isset($select_monthlyrank)) {
            $this->setSystemInfoMonthlyRank($select_monthlyrank, $auth->site_id);
        }
        $system_info = $this->getSystemInfo($auth->site_id);
        $theme_id = $system_info->monthlyrank;

        $works_appraiser_lists = null;
        $csv = $request->get('csv');
        if (isset($csv)) {
            $works_appraiser_lists = $this->getWorkAppraiserListsByCSV($theme_id, $csv, $auth->site_id);
        }

        if ($works_appraiser_lists == null) {
            $works_appraiser_lists = $this->getWorkAppraiserLists($theme_id, $auth->site_id);
        }

        return view('rankings.monthly', ['monthlyrank' => $system_info->monthlyrank, 'works_appraiser_lists' => $works_appraiser_lists]);
    }

    /**
     * 月間ランキングの更新
     *
     * @param Request $request
     *   ランキングは $requestに含まれる csv文字列
     * @return view()の戻り値
     */
    public function monthlyUpdate(Request $request)
    {
        // 認証情報取得
        $auth = Auth::user();

        $system_info = $this->getSystemInfo($auth->site_id);
        $theme_id = $system_info->monthlyrank;

        $csv = $request->get('csv');
        $this->setWorkAppraiserListsByCSV($theme_id, $csv, $auth->site_id);
        $works_appraiser_lists = $this->getWorkAppraiserLists($theme_id, $auth->site_id);

        $this->replaceAppraiserWorkToList($theme_id, 2, $auth->site_id);

        return view('rankings.monthly', ['monthlyrank' => $system_info->monthlyrank, 'works_appraiser_lists' => $works_appraiser_lists]);
    }

    //------------------------------------------------
    /**
     * おすすめランキング
     */
    public function recommended(Request $request)
    {
        // 認証情報取得
        $auth = Auth::user();

        $select_recommendedrank = $request->get('select_recommendedrank');
        if (isset($select_recommendedrank)) {
            $this->setSystemInfoRecommendedRank($select_recommendedrank, $auth->site_id);
        }
        $system_info = $this->getSystemInfo($auth->site_id);
        $theme_id = $system_info->recommendedrank;

        $works_appraiser_lists = null;
        $csv = $request->get('csv');
        if (isset($csv)) {
            $works_appraiser_lists = $this->getWorkAppraiserListsByCSV($theme_id, $csv, $auth->site_id);
        }

        if ($works_appraiser_lists == null) {
            $works_appraiser_lists = $this->getWorkAppraiserLists($theme_id, $auth->site_id);
        }

        return view('rankings.recommended', ['recommendedrank' => $system_info->recommendedrank, 'works_appraiser_lists' => $works_appraiser_lists]);
    }

    /**
     * おすすめランキングの更新
     *
     * @param Request $request
     *   ランキングは $requestに含まれる csv文字列
     * @return view()の戻り値
     */
    public function recommendedUpdate(Request $request)
    {
        // 認証情報取得
        $auth = Auth::user();

        $system_info = $this->getSystemInfo($auth->site_id);
        $theme_id = $system_info->recommendedrank;

        $csv = $request->get('csv');
        $this->setWorkAppraiserListsByCSV($theme_id, $csv, $auth->site_id);
        $works_appraiser_lists = $this->getWorkAppraiserLists($theme_id, $auth->site_id);

        $this->replaceAppraiserWorkToList($theme_id, 3, $auth->site_id);

        return view('rankings.recommended', ['recommendedrank' => $system_info->recommendedrank, 'works_appraiser_lists' => $works_appraiser_lists]);
    }

    //------------------------------------------------
    //------------------------------------------------
    /**
     * work_appraiser_listsテーブルの取得
     *
     * @param integer $theme_id テーマID
     * @param integer $site_id サイトID
     * @return object works_appraiser_lists/appraisers
     */
    protected function getWorkAppraiserLists($theme_id, $site_id = 1)
    {
        $sql = "SELECT * FROM works_appraiser_lists INNER JOIN appraisers ON works_appraiser_lists.appraiser_id = appraisers.id
                WHERE works_appraiser_lists.site_id = $site_id AND works_appraiser_lists.theme_id = $theme_id ORDER BY works_appraiser_lists.sort_no";
        return DB::connection('pgsql2')->select($sql);
    }

    //------------------------------------------------
    /**
     * appraiser_idのcsv文字列からwork_appraiser_listsオブジェクトの配列を生成する
     *
     * @param integer $theme_id テーマID
     * @param string $csv appraiser_idのCSV文字列
     * @param integer $site_id サイトID
     * @return array works_appraiser_lists/appraisersオブジェクトの配列
     */
    protected function getWorkAppraiserListsByCSV($theme_id, $csv, $site_id = 1)
    {
        $works_appraiser_lists = [];

        $appraisers = str_getcsv($csv, ',');
        $sort_no = 1;
        foreach ($appraisers as $appraiser_id) {
            if ($appraiser_id != '') {
                $appraiser_info = (object)[];
                $sql = "SELECT * FROM appraisers WHERE id = $appraiser_id AND site_id = $site_id";
                $appraiser_infos = DB::connection('pgsql2')->select($sql);
                if (isset($appraiser_infos[0])) {
                    $appraiser_info = $appraiser_infos[0];

// $sql = "SELECT login_id FROM users WHERE id = (SELECT user_id FROM user_appraisers WHERE appraiser_id = $appraiser_id AND site_id = $site_id)";
// $login_id = DB::connection('pgsql')->select($sql)[0]->login_id;

                    $works_appraiser_lists[] = (object)array(
                        'site_id'   => $site_id,
                        'theme_id'  => $theme_id,
                        'sort_no'   => $sort_no,
                        'appraiser_id'  => $appraiser_id,

                        'name'  => $appraiser_info->name,
                        // 'account'  => $login_id,
                        'image'  => $appraiser_info->image,
                    );
                    $sort_no++;
                }
            }
        }

        return $works_appraiser_lists;
    }

    //------------------------------------------------
    /**
     * appraiser_idのcsv文字列でwork_appraiser_listsを置き換える
     *
     * @param integer $theme_id テーマID
     * @param string $csv appraiser_idのCSV文字列
     * @param integer $site_id サイトID
     * @return array 設定したworks_appraiser_lists/appraisersオブジェクトの配列
     */
    protected function setWorkAppraiserListsByCSV($theme_id, $csv, $site_id = 1)
    {
        $sql = "DELETE FROM works_appraiser_lists WHERE site_id = $site_id AND theme_id = $theme_id";
        $dummy = DB::connection('pgsql2')->delete($sql);

        $appraisers = str_getcsv($csv, ',');
        $sort_no = 1;
        foreach ($appraisers as $appraiser_id) {
            if ($appraiser_id != '') {
                $sql = "INSERT INTO works_appraiser_lists VALUES ($site_id, $theme_id, $sort_no, $appraiser_id, '2022-10-01',  '2022-10-01', now())";
                $dummy = DB::connection('pgsql2')->insert($sql);
                $sort_no++;
            }
        }

        return $this->getWorkAppraiserLists($theme_id, $site_id);
    }

    //------------------------------------------------
    /**
     * work_appraiser_lists(work_theme_id) を appraiser_lists(theme_id) にコピーする
     *
     * @param integer $work_theme_id  work_appraiser_listsのテーマID
     * @param integer $theme_id       appraiser_listsのテーマID
     * @param integer $site_id サイトID
     * @return void
     */
    protected function replaceAppraiserWorkToList($work_theme_id, $theme_id, $site_id = 1)
    {
        $sql = "DELETE FROM appraiser_lists WHERE site_id = $site_id AND theme_id = $theme_id";
        $dummy = DB::connection('pgsql2')->delete($sql);

        $infos = $this->getWorkAppraiserLists($work_theme_id, $site_id);
        foreach ($infos as $info) {
            $sql = "INSERT INTO appraiser_lists VALUES ({$info->site_id}, {$theme_id}, {$info->sort_no}, {$info->appraiser_id})";
            $dummy = DB::connection('pgsql2')->insert($sql);
        }
    }

    //------------------------------------------------

    protected function getSystemInfo($site_id)
    {
        $sql = "SELECT * FROM system_info WHERE site_id = $site_id";
        $system_info = DB::connection('pgsql2')->select($sql)[0];
        return $system_info;
    }

    protected function setSystemInfoWeeklyRank($weeklyrank, $site_id)
    {
        $sql = "UPDATE system_info SET weeklyrank = $weeklyrank WHERE site_id = $site_id";
        DB::connection('pgsql2')->update($sql);
    }

    protected function setSystemInfoMonthlyRank($monthlyrank, $site_id)
    {
        $sql = "UPDATE system_info SET monthlyrank = $monthlyrank WHERE site_id = $site_id";
        DB::connection('pgsql2')->update($sql);
    }

    protected function setSystemInfoRecommendedRank($recommendedrank, $site_id)
    {
        $sql = "UPDATE system_info SET recommendedrank = $recommendedrank WHERE site_id = $site_id";
        DB::connection('pgsql2')->update($sql);
    }

    //------------------------------------------------
}
