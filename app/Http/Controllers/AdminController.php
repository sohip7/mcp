<?php

namespace App\Http\Controllers;

use App\Models\program_info;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function change_version_numberForm(){
    return view('admin_forms.change_versionForm');
    }
    public function ApplyChangeVersionNumber(Request $request){
        $program_info=program_info::first();
        $program_info->update([
        'version_number'=>$request->versionNumber
        ]);
        return redirect()->back()->with(['success' => 'تم تحديث رقم الإصدار بنجاح']);

    }
}
