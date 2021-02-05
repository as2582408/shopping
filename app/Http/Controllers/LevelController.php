<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Level;
use App\User;

class LevelController extends Controller
{
    //
    public function index()
    {
        $levels = Level::orderBy('level_rank', 'desc')->get();
        $maxLevel = Level::where('level_status', '=', 'Y')->max('level_rank');
        $status = [
            'Y' => __('shop.Enable'),
            'N' => __('shop.Disable'),
            'D' => __('shop.Delete')
        ];

        return view('level.level', [
            'levels' => $levels,
            'maxLevel' => $maxLevel,
            'status' => $status
            ]);
    }

    public function editLevelPage($id)
    {
        $level = Level::where('level_id' , '=', $id)->first();
        $upMoney = Level::where('level_rank', '=', $level->level_rank+1)->select('level_threshold')->first();
        $downMoney = Level::where('level_rank', '=', $level->level_rank-1)->select('level_threshold')->first();

        return view('level.editLevel', [
            'level' => $level,
            'upMoney' => $upMoney,
            'downMoney' => $downMoney
        ]);
    }

    public function editLevel(Request $request)
    {
        if(empty($request->input('upMoney'))) { //最高層，無更上層
            $this->validate($request, [
                'threshold' => 'required|numeric|min:'.($request->input('downMoney')+1)
            ]);
        }

        if(empty($request->input('downMoney'))) { //最下層，無更下層
            $this->validate($request, [
                'threshold' => 'required|numeric|max:'.($request->input('upMoney')-1)
            ]);
        }
        $this->validate($request, [
            'name' => 'required|max:255|regex:/^[A-Za-z0-9\x7f-\xffA]+$/',
            'threshold' => 'required|numeric|max:'.($request->input('upMoney')-1).'|min:'.($request->input('downMoney')+1)
        ]);

        Level::where('level_id', '=', $request->input('id'))->update([
            'level_name' => $request->input('name'),
            'level_threshold' => $request->input('threshold')
        ]);

        return redirect()->intended('/admin/level');

    }

    public function delLevel($id)
    {
        $oldRank = Level::select('level_rank')->where('level_id', '=', $id)->first();

        Level::where('level_id', '=', $id)->update([
            'level_status' => 'D'
        ]);

        //刪除等級時將刪除等級底下的會員降級
        User::where('level', '=', $oldRank->level_rank)->update([
            'level' => $oldRank->level_rank-1
        ]);
        return redirect()->intended('/admin/level');
    }

    public function redelLevel($id)
    {
        $reRank = Level::select('level_rank', 'level_threshold')->where('level_id', '=', $id)->first();

        Level::where('level_id', '=', $id)->update([
            'level_status' => 'Y'
        ]);
        //復原需要將等級還原
        User::where([
            ['level', '=', $reRank->level_rank-1],
            ['accumulation_point', '>=', $reRank->level_threshold],
        ])->update([
            'level' => $reRank->level_rank
        ]);
        return redirect()->intended('/admin/level');
    }

    public function addLevelPage()
    {
        $maxLevel = Level::where('level_status', '=', 'Y')->max('level_rank');
        $rankMoney = Level::where('level_status', '=', 'Y')->max('level_threshold');

        return view('level.addlevel', [
            'max' => $maxLevel,
            'rankMoney' => $rankMoney
        ]);
    }
    //新增等級
    public function addLevel(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'rank' => 'required|numeric',
            'threshold' => 'required|numeric|min:'.($request->input('upThreshold')+1)
        ]);

        $addLevel = Level::create([
            'level_rank' => $request->input('rank'),
            'level_threshold' => $request->input('threshold'),
            'level_name' => $request->input('name')
        ]);
        //新增等級時將停用的舊等級刪除
        Level::where('level_status', '=', 'D')->delete();
        $addLevel->save();

        return redirect()->intended('/admin/level');
    }

}
