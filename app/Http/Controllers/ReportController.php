<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Report;
use App\Report_reply;
use Illuminate\Support\Facades\Auth;

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
            'reply' => 'required|regex:/^[\x7f-\xffA-Za-z0-9 ()（）!,:;\n\s]+$/',
        ]);
        $textToStore = nl2br(htmlentities($request->input('reply'), ENT_COMPAT, 'UTF-8'));
        $newMessage = 'System:  "'.$textToStore.'"';

        Report_reply::create([
            'reply_id' => $request->input('id'),
            'reply' => $newMessage,
            'reply_time' => date("Y-m-d H:i:s")
        ])->save();
        
        Report::where('report_id', '=', $request->input('id'))->update([
        'report_updata_time' => date("Y-m-d H:i:s"),
        'report_reply' => 'System'
        ]);

        return redirect()->back();
    }

    public function userIndex()
    {   
        $id = Auth::id();
        $reports = Report::where('user_id', '=', $id)->orderBy('report_updata_time', 'desc')->get();
        return view('user.report', ['reports' => $reports]);
    }

    public function userTalk($id)
    {
        $replys = Report_reply::join('report', 'report.report_id', '=', 'report_reply.reply_id')->where('reply_id', '=', $id)->orderBy('reply_time', 'desc')->get();
        return view('user.reporttalk', [
            'replys' => $replys,
            'reply_id' => $id
            ]);
    }

    public function userReportReplyPage($id)
    {
        return view('user.reportreply', ['reply_id' => $id]);
    }

    public function userReportReply(Request $request)
    {
        $this->validate($request, [
            'reply' => 'required|regex:/^[\x7f-\xffA-Za-z0-9 ()（）!,:;\n\s]+$/',
        ]);
        $textToStore = nl2br(htmlentities($request->input('reply'), ENT_COMPAT, 'UTF-8'));
        $newMessage = 'Member:  "'.$textToStore.'"';

        Report_reply::create([
            'reply_id' => $request->input('id'),
            'reply' => $newMessage,
            'reply_time' => date("Y-m-d H:i:s")
        ])->save();
        
        Report::where('report_id', '=', $request->input('id'))->update([
        'report_updata_time' => date("Y-m-d H:i:s"),
        'report_reply' => 'Member'
        ]);

        return redirect()->back();
    }

    public function addReportPage()
    {
        return view('user.reportnew');
    }

    public function addReport(Request $request)
    {
        $this->validate($request, [
            'reply' => 'required|regex:/^[\x7f-\xffA-Za-z0-9 ()（）!,:;\n\s]+$/',
            'title' => 'required|regex:/^[\x7f-\xffA-Za-z0-9 ()（）!,:;\n\s]+$/'
        ]);

        $reportId = Report::insertGetId([
            'user_id' => Auth::id(),
            'report_description' => $request->title,
            'report_reply' =>  'Member',
            'report_updata_time' => date("Y-m-d H:i:s")
        ]);
        
        $textToStore = nl2br(htmlentities($request->input('reply'), ENT_COMPAT, 'UTF-8'));
        $newMessage = 'Member:  "'.$textToStore.'"';

        Report_reply::create([
            'reply_id' => $reportId,
            'reply' => $newMessage,
            'reply_time' => date("Y-m-d H:i:s")
        ])->save();

        return redirect('/report');
    }
}
