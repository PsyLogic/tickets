<?php

namespace App\Http\Controllers\admin;

use App\EmailTemplate;
use Gate;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;
use Yajra\Datatables\Datatables;

class EmailTemplateController extends Controller
{

    //email template view
    public function index()
    {
        if(Gate::denies('admin')) {
            abort(403, trans('messages.notAuthorised'));
        }

        //email template datatable view
        if(\Request::ajax()){
            $emailTemplate = EmailTemplate::select('id', 'subject','content');
            return Datatables::of($emailTemplate)
                ->add_column('action', function ($row) {
                    return '<button class="btn btn-success btn-sm " onclick=showModal("'.$row->id.'") data-pk="'.$row->id.'">Edit</button>';
                })
                ->make(true);
        }

        $data['emailTemplateData'] = EmailTemplate::select('id', 'subject','content')->take(10)->get();
        $data['total'] = EmailTemplate::count();

        $data['activeEmailTemplate'] = "active";
        $data['active'] = "active";
        $data['open'] = 'open';

        return view('admin/emailTemplate/index',$data);
    }

   //email template edit view
    public function edit($id)
    {
        $emailTemplateData = EmailTemplate::find($id);
        return view('admin.emailTemplate.edit', ['emailTemplateData' => $emailTemplateData]);
    }

    //email template update
    public function update(Request $request, $id)
    {
        if(Gate::denies('admin')) {
            abort(403, trans('messages.notAuthorised'));
        }

        if (\Request::ajax()) {
            $emailTemplateUpdate = EmailTemplate::find($id);
            $this->validate($request, $emailTemplateUpdate->rules);

            $emailTemplateUpdate->subject = $request->subject;
            $emailTemplateUpdate->content = $request->contents;
            $emailTemplateUpdate->save();

            Session::flash('toastrType', 'success');
            Session::flash('toastrMessage', trans('messages.emailTemplateUpdateStr'));
            Session::flash('toastrTitle', 'success');

            return ['status' => 'success', 'message' => trans('messages.emailTemplateUpdate'), 'action' => 'redirect', 'url' => route('emailtemplate.index')];
        }
    }

}
