<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Report;
use App\Report_reply;

class ReportController extends Controller
{
    public function index()
    {
        $reports = Report::join('users', 'report.user_id', '=', 'users.id')->orderBy('report_updata_time', 'desc')->get();
        return view('report.report', ['reports' => $reports]);
    }

    public function talk($id)
    {
        $replys = Report_reply::join('report', 'report.report_id', '=', 'report_reply.reply_id')->where('reply_id', '=', $id)->orderBy('reply_time', 'desc')->get();
        return view('report.reporttalk', [
            'replys' => $replys,
            'reply_id' => $id
            ]);
    }

    public function reportReplyPage($id)
    {
        return view('report.reportreply', ['reply_id' => $id]);
    }

    public function reportReply(Request $request)
    {
        $this->validate($request, [
            'reply' => 'required',
        ]);
        $newMessage = 'System:  "'.$request->input('reply').'"';

        Report_reply::create([
            'reply_id' => $request->input('id'),
            'reply' => $newMessage,
            'reply_time' => date("Y-m-d H:i:s")
        ])->save();
        
        Report::where('report_id', '=', $request->input('id'))->update([
        'report_updata_time' => date("Y-m-d H:i:s"),
        'report_reply' => 'System'
        ]);

        return redirect()->intended('/admin/report');
    }
    //
}