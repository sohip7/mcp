<?php

namespace App\Http\Controllers;

use App\Models\balance_inout;
use App\Models\balance_sale;
use App\Models\CustomerPay;
use App\Models\dailydata;
use App\Models\DealersBuy;
use App\Models\end_daily_outs;
use App\Models\lenddata;
use App\Models\note;
use App\Models\Outs;
use App\Models\PlatformBalance;
use App\Models\User;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function MongoDB\BSON\toJSON;
use mPDF;
use Barryvdh\DomPDF\Facade\Pdf as sPDF;


class DailyController extends Controller
{
    //


    public function SalesForm()
    {
        $user_data = Auth::user();
        return view('Forms.SalesForm', compact('user_data'));
    }

    public function enterDailyNotes()
    {
        $user_data = Auth::user();
        return view('Forms.enterNotes', compact('user_data'));
    }


    public function balance_sales_show()
    {
        $date = today()->format('Y-m-d');
        $balancesales = balance_inout::whereDate('created_at', $date)->get();
        return view('Show.balanceSalesShow', get_defined_vars());
    }

    public function balanceSalesShowWithDate(Request $request)
    {
        $date = $request->date;
        $balancesales = balance_inout::whereDate('created_at', $date)->get();
        return view('Show.balanceSalesShow', get_defined_vars());
    }

    public function DailyNotesWithDate(Request $request)
    {
        $date = $request->date;
        $note = note::whereDate('created_at', $date)->get();
        return view('DailyNotesShow', get_defined_vars());
    }

    public function PayMerchantShow()
    {

        $date = today()->format('Y-m-d');
        $MerchPaysOuts = Outs::where('service_number', '1')->whereDate('created_at', $date)->get();
        $balanceOuts = balance_inout::where('service_number', '1')->whereDate('created_at', $date)->get();
        $todayTotal = balance_inout::where('service_number', '1')->whereDate('created_at', $date)->sum('amount');
        return view('Show.merchantPaysShow', get_defined_vars());
    }

    public function MerchantPaysWithDate(Request $request)
    {

        $date = $request->input('date');

        $MerchPaysOuts = Outs::where('service_number', '1')->whereDate('created_at', $date)->get();
        $balanceOuts = balance_inout::where('service_number', '1')->whereDate('created_at', $date)->get();
        $todayTotal = balance_inout::where('service_number', '1')->whereDate('created_at', $date)->sum('amount');
        return view('Show.merchantPaysShow', get_defined_vars());
    }


    /************************************ Sales *****************************/
    public function StoreSales(Request $request)
    {
        $user_data = Auth::user();

        $insertedID = dailydata::insertGetId([
            'sim_place_of_sale' => $request->OSSBselect,
            'item' => $request->item_name,
            'RecordType' => $request->RecordType,
            'amount' => $request->amount,
            'quantity' => $request->quantity,
            'osap' => $request->ActivePrice,
            'notes' => $request->notes,
            'total' => $request->amount * $request->quantity,
            'user_name' => $user_data->name,
            'created_at' => now()
        ]);
        if (!$insertedID) {
            return redirect()->back()->with(['Error' => 'لم تنجح الاضافة']);

        }
        if ($request->has('RecordType')) {
            $selectedValue = $request->input('RecordType');

            if ($selectedValue === 'Ooredoo') {
                $store_balance_out = balance_inout::create([
                    'record_type' => 'مخرج',
                    'platform_name' => $selectedValue, //Ooredoo
                    'sales_foreign_id' => $insertedID,
                    'amount' => $request->amount * $request->quantity,
                    'notes' => "نقداُ",
                    'created_by' => $user_data->name
                ]);

            } elseif ($selectedValue === 'Jawwal') {
                $store_balance_out = balance_inout::create([
                    'record_type' => 'مخرج',
                    'platform_name' => $selectedValue, //Jawwal
                    'sales_foreign_id' => $insertedID,
                    'amount' => $request->amount * $request->quantity,
                    'notes' => "نقداُ",
                    'created_by' => $user_data->name
                ]);
            } elseif ($selectedValue === 'JawwalPay') {
                $store_balance_out = balance_inout::create([
                    'record_type' => 'مخرج',
                    'platform_name' => $selectedValue, //JawwalPay
                    'sales_foreign_id' => $insertedID,
                    'amount' => $request->amount * $request->quantity,
                    'notes' => "نقداُ",
                    'created_by' => $user_data->name
                ]);
                if ($request->JPAccountType == 'agent') {
                    $store_balance_out = balance_inout::create([
                        'record_type' => 'مدخل',
                        'jawwalpay_account_type' => 'agent',
                        'platform_name' => $selectedValue, //JawwalPay
                        'salescomssion_foreign_id' => $insertedID,
                        'amount' => $request->amount * (0.5 / 100),
                        'notes' => "عمولة إيداع جوال باي عبر حساب الوكيل",
                        'created_by' => $user_data->name
                    ]);
                }
            } elseif ($selectedValue === 'OoredooBills') {
                $store_balance_out = balance_inout::create([
                    'record_type' => 'مخرج',
                    'platform_name' => $selectedValue, //OoredooBills
                    'sales_foreign_id' => $insertedID,
                    'amount' => $request->amount * $request->quantity,
                    'notes' => "نقداُ",
                    'created_by' => $user_data->name
                ]);
            } elseif ($selectedValue === 'Electricity') {
                $store_balance_out = balance_inout::create([
                    'record_type' => 'مخرج',
                    'platform_name' => $selectedValue, //Electricity
                    'sales_foreign_id' => $insertedID,
                    'amount' => $request->amount * $request->quantity,
                    'notes' => "نقداُ",
                    'created_by' => $user_data->name
                ]);
            } elseif ($selectedValue === 'OoredooSim') {
                $store_lend_ooredooSim = lenddata::create([
                    'item' => "تفعيل شريحة أوريدوا",
                    'sales_foreign_id' => $insertedID,
                    'RecordType' => $selectedValue,
                    'amount' => $request->ActivePrice,
                    'quantity' => $request->quantity,
                    'total' => $request->ActivePrice * $request->quantity,
                    'debtorName' => "تفعيل شريحة Ooredoo",
                    'UserName' => $user_data->name,
                ]);
                $store_balance_out = balance_inout::create([
                    'record_type' => 'مخرج',
                    'platform_name' => "Ooredoo",
                    'loans_foreign_id' => $insertedID,
                    'amount' => $request->ActivePrice * $request->quantity,
                    'notes' => "مديونية تفعيل شريحة",
                    'created_by' => $user_data->name
                ]);

                if ($request->OSSBselect == 'shaaf') {
                    $UpdateAmount = dailydata::where('id', $insertedID)->first();
                    $store_lend_ooredooSim = lenddata::create([
                        'item' => "تفعيل شريحة أوريدوا",
                        'shaaf_sim_sales_foreign_id' => $insertedID,
                        'RecordType' => $selectedValue,
                        'amount' => $request->amount,
                        'quantity' => $request->quantity,
                        'total' => $request->amount * $request->quantity,
                        'debtorName' => "الشعف",
                        'UserName' => $user_data->name,
                        'notes' => "سُجلت ك دَين على الشعف",
                    ]);
                    $UpdateAmount->update([
                        'sim_place_of_sale' => $request->OSSBselect,
                        'amount' => 0,
                        'total' => 0,
                        'notes' => "تم تسجيلها ك دَين على الشعف"
                    ]);
                }


                return redirect()->back()->with(['success' => "تم حفظ بيع الشريحة بنجاح وتسجيل مديونية التفعيل وهي $request->ActivePrice لكل شريحة "]);
            }
        } else {

        }

        return redirect()->back()->with(['success' => "تم حفظ بيع الصنف:$request->item_name ، بمبلغ:$request->amount ، وإجمالي:$request->amount * $request->quantity   "]);
    }

    public function SalesDelete(Request $request)
    {
        $user_data = Auth::user();
        $Sales2 = dailydata::find($request->id);
        $loans_ooredooSim = lenddata::where('sales_foreign_id', $request->id)->first();
        $shaaf_loans_ooredooSim = lenddata::where('shaaf_sim_sales_foreign_id', $request->id)->first();
        $OSbalance_inout = balance_inout::where('loans_foreign_id', $request->id)->first();
        $balance_inout = balance_inout::where('sales_foreign_id', $request->id)->first();
        $JPCbalance_inout = balance_inout::where('salescomssion_foreign_id', $request->id)->first();
        if (!$Sales2) {
            return redirect()->back()->with(['error' => ('لم يتم إيجاد الصف في قاعدة البيانات')]);
        } elseif ($loans_ooredooSim) {
            $loans_ooredooSim->update([
                'deleted_by' => $user_data->name
            ]);
            $OSbalance_inout->update([
                'deleted_by' => $user_data->name
            ]);
            $loans_ooredooSim->delete();
            $OSbalance_inout->delete();
            if ($shaaf_loans_ooredooSim)
                $shaaf_loans_ooredooSim->update([
                    'deleted_by' => $user_data->name
                ]);
            $shaaf_loans_ooredooSim->delete();

            return redirect()->back()->with(['success' => 'تم حذف تسجيل الشريحة بنجاح ']);
        } elseif ($JPCbalance_inout and $balance_inout) {
            $JPCbalance_inout->update([
                'deleted_by' => $user_data->name
            ]);
            $balance_inout->update([
                'deleted_by' => $user_data->name
            ]);
            $JPCbalance_inout->delete();
            $balance_inout->delete();
        } elseif ($balance_inout) {
            $balance_inout->update([
                'deleted_by' => $user_data->name
            ]);
            $balance_inout->delete();
        }
        $Sales2->update([
            'deleted_by' => $user_data->name
        ]);
        $Sales2->delete();


        return redirect()->back()->with(['success' => 'تم حذف البيان بنجاح ']);

    }


    public function SalesEdit(Request $request)
    {
        $user_data = Auth::user();
        $Sales = dailydata::find($request->id);
        $loans_ooredooSim = lenddata::where('sales_foreign_id', $request->id)->first();
        $JPCbalance_inout = balance_inout::where('salescomssion_foreign_id', $request->id)->first();//jawwal pay commission
        if (!$Sales)
            return redirect()->back()->with(['Error' => 'لم يتم إيجاد الصنف في قاعدة بياناتنا ']);
        return view('Edit_Forms.SalesEdit', get_defined_vars());

    }

    public function SalesUpdate(Request $request)
    {
        $user_data = Auth::user();
        $sales = dailydata::find($request->id);
        $loans_ooredooSim = lenddata::where('sales_foreign_id', $request->id);
        $OSbalance_inout = balance_inout::where('loans_foreign_id', $request->id)->first();// ooredoo sim
        $shaaf_loans_ooredooSim = lenddata::where('shaaf_sim_sales_foreign_id', $request->id)->first();
        $balance_inout = balance_inout::where('sales_foreign_id', $request->id)->first();
        $JPCbalance_inout = balance_inout::where('salescomssion_foreign_id', $request->id)->first();//jawwal pay commission
        if (!$sales)
            return redirect()->back()->with(['success' => 'لم يتم إيجاد الصنف في قاعدة بياناتنا ']);

        if ($request->RecordType == 'OoredooSim' and !$OSbalance_inout)
            return redirect()->route('sales.show')->with(['Error' => 'هذا التعديل غير مدعوم حالياً، سيتم دعمه قريباً، يرجى إعلام المطور | كود الخطأ:1']);


        //update data

        if ($request->RecordType != 'General' and $request->RecordType != 'OoredooSim') {

            if (!$balance_inout)
                return redirect()->route('sales.show')->with(['Error' => 'هذا التعديل غير مدعوم حالياً، سيتم دعمه قريباً، يرجى إعلام المطور | كود الخطأ:2']);
            if ($request->RecordType == 'JawwalPay') {
                $balance_inout->update([
                    'amount' => $request->amount * $request->quantity,
                    'platform_name' => $request->RecordType,
                    'updated_By' => $user_data->name
                ]);
                if ($request->JPAccountType == 'agent') {
                    if (!$JPCbalance_inout) {
                        return redirect()->route('sales.show')->with(['Error' => 'هذا التعديل غير مدعوم حالياً، سيتم دعمه قريباً، يرجى إعلام المطور | كود الخطأ:3']);

                    }
                    $JPCbalance_inout->update([
                        'amount' => $request->amount * (0.5 / 100),
                        'updated_By' => $user_data->name

                    ]);
                } elseif ($JPCbalance_inout) {
                    $JPCbalance_inout->update([
                        'deleted_by' => $user_data->name
                    ]);
                    $JPCbalance_inout->delete();
                }

            } else
                $balance_inout->update([
                    'amount' => $request->amount * $request->quantity,
                    'platform_name' => $request->RecordType,
                    'updated_By' => $user_data->name
                ]);
        }
        if ($request->RecordType == 'General') {
            if ($balance_inout) {
                $balance_inout->update([
                    'deleted_by' => $user_data->name
                ]);
                $balance_inout->delete();
            }
            if ($OSbalance_inout) {
                $OSbalance_inout->update([
                    'deleted_by' => $user_data->name
                ]);
                $loans_ooredooSim->update([
                    'deleted_by' => $user_data->name
                ]);
                $OSbalance_inout->delete();

                $loans_ooredooSim->delete();
            }
        } elseif ($request->RecordType == 'OoredooSim') {
            if (!$OSbalance_inout or !$loans_ooredooSim)
                return redirect()->route('sales.show')->with(['Error' => 'هذا التعديل غير مدعوم حالياً، سيتم دعمه قريباً، يرجى إعلام المطور | كود الخطأ:4']);
            if ($request->OSSBselect == 'nasser') {
                if ($shaaf_loans_ooredooSim) {
                    $shaaf_loans_ooredooSim->update([
                        'deleted_by' => $user_data->name
                    ]);
                    $shaaf_loans_ooredooSim->delete();
                }

            } elseif ($request->OSSBselect == 'shaaf') {
                if (!$shaaf_loans_ooredooSim) {
                    return redirect()->route('sales.show')->with(['Error' => 'هذا التعديل غير مدعوم حالياً، سيتم دعمه قريباً، يرجى إعلام المطور | كود الخطأ:5']);
                }
            }
            $loans_ooredooSim->update([
                'amount' => $request->ActivePrice,
                'quantity' => $request->quantity,
                'total' => $request->ActivePrice * $request->quantity,
                'updated_By' => $user_data->name,
            ]);
            $OSbalance_inout->update([
                'amount' => $request->ActivePrice * $request->quantity,
                'updated_By' => $user_data->name,
            ]);

        }
        $sales->update([
            'item' => $request->item_name,
            'sim_place_of_sale' => $request->OSSBselect,
            'RecordType' => $request->RecordType,
            'amount' => $request->amount,
            'quantity' => $request->quantity,
            'osap' => $request->ActivePrice,
            'notes' => $request->notes,
            'total' => $request->amount * $request->quantity,
            'updated_By' => $user_data->name,
        ]);


        return redirect()->route('sales.show')->with(['success' => 'تم التحديث بنجاح']);

    }

    /************************************ CustomerPayment *****************************/
    public function CustomerPaymentDelete(Request $request)
    {
        $user_data = Auth::user();
        $CusPay = CustomerPay::find($request->id);
        $out = Outs::where('cuspay_foreign_id', $request->id);
        $balance_inout = balance_inout::where('cuspay_foreign_id', $request->id);
        if (!$CusPay) {
            return redirect()->back()->with(['error' => ('لم يتم إيجاد الصف في قاعدة البيانات')]);
        } elseif ($balance_inout) {
            $CusPay->update([
                'deleted_by' => $user_data->name
            ]);
            $balance_inout->update([
                'deleted_by' => $user_data->name
            ]);
            $out->update([
                'deleted_by' => $user_data->name
            ]);
            $CusPay->delete();
            $balance_inout->delete();
            $out->delete();

        } else {
            $CusPay->update([
                'deleted_by' => $user_data->name
            ]);
            $CusPay->delete();
        }


        return redirect()->back()->with(['success' => 'تم حذف البيان بنجاح ']);
    }


    public function CustomerPaymentEdit(Request $request)
    {
        $user_data = Auth::user();
        $CusPay = CustomerPay::find($request->id);
        if (!$CusPay)
            return redirect()->back()->with(['success' => 'لم يتم إيجاد الصنف في قاعدة بياناتنا ']);

        return view('Edit_Forms.CustomerPaymentEdit', get_defined_vars());

    }

    public function CustomerPaymentUpdate(Request $request)
    {
        $user_data = Auth::user();
        $CusPay = CustomerPay::find($request->id);
        $Outs = Outs::where('cuspay_foreign_id', $request->id)->first();
        $balance_inout = balance_inout::where('cuspay_foreign_id', $request->id)->first();
        if (!$CusPay)
            return redirect()->back()->with(['success' => 'لم يتم إيجاد الصنف في قاعدة بياناتنا ']);

        //update data

        if ($request->PayMethod != 'Cash') {
            //update data
            if (!$Outs)
                return redirect()->route('CustomerPay.show')->with(['Error' => 'عملية تعديل غير مسموح بها، قم بحذف السجل ومعاودة إضافة سجل جديد بالبيانات الصحيحة']);

            $Outs->update([
                'item' => "إخراج إلى رصيد $request->PayMethod ",
                'amount' => $request->amount,
                'RecordType' => $request->PayMethod,
                'notes' => $request->notes,
                'updated_By' => $user_data->name
            ]);
            $balance_inout->update([
                'amount' => $request->amount,
                'platform_name' => $request->PayMethod,
                'updated_By' => $user_data->name
            ]);
        }

        $CusPay->update([
            'CustomerName' => $request->CustomerName,
            'amount' => $request->amount,
            'PayMethod' => $request->PayMethod,
            'notes' => $request->notes,
            'user_name' => $user_data->name,
            'updated_By' => $user_data->name
        ]);

        if ($request->PayMethod == 'Cash' and $balance_inout) {
            $balance_inout->update([
                'deleted_by' => $user_data->name
            ]);
            $Outs->update([
                'deleted_by' => $user_data->name
            ]);
            $balance_inout->delete();
            $Outs->delete();
        }


        return redirect()->route('CustomerPay.show')->with(['success' => 'تم التحديث بنجاح']);

    }


    /************************************ Loans *****************************/
    public function LoansDelete(Request $request)
    {
        $user_data = Auth::user();
        $loans = lenddata::find($request->id);
        $firstpay_rec = dailydata::where('lend_foreign_id', $request->id)->first();
        $balance_inout = balance_inout::where('loans_foreign_id', $request->id)->first();

        if (!$loans) {
            return redirect()->back()->with(['error' => ('لم يتم إيجاد الصف في قاعدة البيانات')]);
        } elseif ($firstpay_rec and $balance_inout) {
            $loans->update([
                'deleted_by' => $user_data->name
            ]);
            $firstpay_rec->update([
                'deleted_by' => $user_data->name
            ]);
            $balance_inout->update([
                'deleted_by' => $user_data->name
            ]);
            $loans->delete();
            $firstpay_rec->delete();
            $balance_inout->delete();

        } elseif ($firstpay_rec) {
            $firstpay_rec->update([
                'deleted_by' => $user_data->name
            ]);
            $loans->update([
                'deleted_by' => $user_data->name
            ]);
            $loans->delete();
            $firstpay_rec->delete();

        } elseif ($balance_inout) {
            $loans->update([
                'deleted_by' => $user_data->name
            ]);
            $balance_inout->update([
                'deleted_by' => $user_data->name
            ]);
            $balance_inout->delete();
            $loans->delete();
        } else {
            $loans->update([
                'deleted_by' => $user_data->name
            ]);
            $loans->delete();
        }
        return redirect()->back()->with(['success' => 'تم حذف البيان بنجاح ']);

    }


    public function LoansEdit(Request $request)
    {

        $user_data = Auth::user();
        $loans = lenddata::find($request->id);
        $balance_inout = balance_inout::where('loans_foreign_id', $request->id)->first();
        if (!$loans)
            return redirect()->back()->with(['success' => 'لم يتم إيجاد الصنف في قاعدة بياناتنا ']);


        return view('Edit_Forms.LendEdit', get_defined_vars());

    }


    public function LoansUpdate(Request $request)
    {
        $user_data = Auth::user();
        $loans = lenddata::find($request->id);
        $balance_inout = balance_inout::where('loans_foreign_id', $request->id)->first();
        $firstpay_rec = dailydata::where('lend_foreign_id', $request->id)->first();

        if (!$loans)
            return redirect()->back()->with(['success' => 'لم يتم إيجاد الصنف في قاعدة بياناتنا ']);

        /* if(!$balance_inout)
             return redirect()->route('sales.show')->with(['Error' => 'هذا التعديل غير مقبول، قم بحذف السجل المراد تعديله وإضافته مجدداً بالشكل الصحيح']);
 */
        //update data

        if ($request->RecordType == 'installment_transaction' and $firstpay_rec) {
            $firstpay_rec->update([
                'item' => "دفعة أولى من معاملة تقسيط $request->debtor_name",
                'RecordType' => "دفعة أولى",
                'amount' => $request->FirstPay,
                'total' => $request->FirstPay,
                'updated_By' => $user_data->name,
                'notes' => "نوع الجهاز : $request->item_name
                مبلغ الدَين الاجمالي : $request->amount",
            ]);
        }
//        if($request->RecordType == 'General' and !$balance_inout and !$firstpay_rec ){
//
//
//        }
        if ($request->RecordType != 'General' and $request->RecordType != 'installment_transaction' and !$balance_inout) {
            return redirect()->route('Loans.show')->with(['Error' => 'هذا التعديل غير مدعوم حالياً، سيتم إتاحته قريبا، يرجى إبلاغ المطور | كود الخطأ 1']);

        }
        if ($request->RecordType != 'General' and $request->RecordType != 'installment_transaction') {
            $balance_inout->update([
                'platform_name' => $request->RecordType,
                'amount' => $request->amount,
                'notes' => "دَين على $request->debtor_name",
                'updated_By' => $user_data->name
            ]);
        }
        if ($request->RecordType == 'General' or $request->RecordType == 'installment_transaction' and $balance_inout) {
            if ($balance_inout) {
                $balance_inout->update([
                    'deleted_by' => $user_data->name
                ]);
                $balance_inout->delete();
            }
        }
        if ($firstpay_rec != null) {
            if ($request->RecordType == 'General' or $request->RecordType == 'Ooredoo' or $request->RecordType == 'Jawwal' or $request->RecordType == 'OoredooBills' or $request->RecordType == 'JawwalPay' or $request->RecordType == 'Electricity') {
                $firstpay_rec->update([
                    'deleted_by' => $user_data->name
                ]);
                $firstpay_rec->delete();
            }
        }
        $loans->update([
            'item_name' => $request->item_name,
            'RecordType' => $request->RecordType,
            'amount' => $request->amount,
            'quantity' => $request->quantity,
            'FirstPay' => $request->FirstPay,
            'total' => $request->amount * $request->quantity,
            'debtorName' => $request->debtor_name,
            'notes' => $request->notes,
            'updated_By' => $user_data->name
        ]);


        return redirect()->route('Loans.show')->with(['success' => 'تم التحديث بنجاح']);
    }

    /************************************ Outs *****************************/
    public function OutsDelete(Request $request)
    {
        $user_data = Auth::user();
        $Outs = Outs::find($request->id);
        $balance_inout = balance_inout::where('outs_foreign_id', $request->id);

        if (!$Outs) {
            return redirect()->back()->with(['error' => ('لم يتم إيجاد الصف في قاعدة البيانات')]);
        } elseif ($balance_inout) {

            $balance_inout->update([
                'deleted_by' => $user_data->name
            ]);
            $balance_inout->delete();

        }
        $Outs->update([
            'deleted_by' => $user_data->name
        ]);
        $Outs->delete();
        return redirect()->back()->with(['success' => 'تم حذف البيان بنجاح ']);
    }


    public function OutsEdit(Request $request)
    {
        $user_data = Auth::user();
        $Outs = Outs::find($request->id);
        if (!$Outs)
            return redirect()->back()->with(['success' => 'لم يتم إيجاد الصنف في قاعدة بياناتنا ']);

        $Outs = Outs::find($request->id);

        return view('Edit_Forms.OutEdit', get_defined_vars());

    }

    public function OutsUpdate(Request $request)
    {
        $user_data = Auth::user();
        $Outs = Outs::find($request->id);
        $balance_inout = balance_inout::where('outs_foreign_id', $request->id)->first();
        if (!$Outs)
            return redirect()->back()->with(['success' => 'لم يتم إيجاد الصنف في قاعدة بياناتنا ']);

        $selectedValue2 = $request->input('RecordType2');
        if ($selectedValue2 == "OutFromIn")
            $operationType = 4;
        else
            $operationType = 3;
        //update data
        $Outs->update([
            'item_name' => $request->item_name,
            'amount' => $request->amount,
            'service_number' => $operationType,
            'RecordType' => $request->RecordType,
            'beneficiary' => $request->beneficiary,
            'notes' => $request->notes,
            'updated_By' => $user_data->name
        ]);
        if ($request->RecordType != 'Cash' and $balance_inout) {

            $balance_inout->update([
                'amount' => $request->amount,
                'platform_name' => $request->RecordType,
                'updated_By' => $user_data->name
            ]);
        } elseif ($request->RecordType == 'Cash' and $balance_inout) {
            $balance_inout->update([
                'deleted_by' => $user_data->name
            ]);
            $balance_inout->delete();
        }


        return redirect()->route('Outs.show')->with(['success' => 'تم التحديث بنجاح']);

    }


    /************************************ Purchases *****************************/
    public function PurchasesDelete(Request $request)
    {
        $user_data = Auth::user();
        $Purchases = DealersBuy::find($request->id);
        $balance_inout = balance_inout::where('purchases_foreign_id', $request->id)->first();

        if (!$Purchases) {
            return redirect()->back()->with(['error' => ('لم يتم إيجاد الصف في قاعدة البيانات')]);
        } elseif ($balance_inout) {
            $Purchases->update([
                'deleted_by' => $user_data->name
            ]);
            $balance_inout->update([
                'deleted_by' => $user_data->name
            ]);
            $Purchases->delete();
            $balance_inout->delete();
        } elseif ($Purchases) {
            $Purchases->update([
                'deleted_by' => $user_data->name
            ]);
            $Purchases->delete();
        }
        return redirect()->back()->with(['success' => 'تم حذف البيان بنجاح ']);

    }


    public function PurchasesEdit(Request $request)
    {
        $user_data = Auth::user();
        $Purchases = DealersBuy::find($request->id);
        if (!$Purchases)
            return redirect()->back()->with(['success' => 'لم يتم إيجاد الصنف في قاعدة بياناتنا ']);

        $Purchases = DealersBuy::find($request->id);


        return view('Edit_Forms.DealersBuyEdit', get_defined_vars());

    }

    public function PurchasesUpdate(Request $request)
    {
        $user_data = Auth::user();
        $Purchases = DealersBuy::find($request->id);
        $balance_inout = balance_inout::where('purchases_foreign_id', $request->id)->first();
        if (!$Purchases)
            return redirect()->back()->with(['success' => 'لم يتم إيجاد الصنف في قاعدة بياناتنا ']);


        //update data

        if ($request->RecordType != 'General' and !$balance_inout)
            return redirect()->route('Purchases.show')->with(['Error' => 'هذا التعديل غير مدعوم حالياً، سيتم دعمه قريباً، يرجى إعلام المطور | كود الخطأ:1']);

        if ($request->RecordType != 'General' and $balance_inout) {
            if ($request->RecordType == 'Electricity') {
                $balance_inout->update([
                    'amount' => $request->amount + ($request->amount * (15 / 1000)),
                    'platform_name' => $request->RecordType,
                    'updated_By' => $user_data->name
                ]);
            }
            if ($request->RecordType == 'Ooredoo') {

                $balance_inout->update([
                    'amount' => $request->amount + ($request->amount * (40 / 1000)),
                    'platform_name' => $request->RecordType,
                    'updated_By' => $user_data->name
                ]);
            } elseif ($request->RecordType != 'General' and $request->RecordType != 'Ooredoo' and $request->RecordType != 'Electricity' and $balance_inout) {
                $balance_inout->update([
                    'amount' => $request->amount,
                    'platform_name' => $request->RecordType,
                    'updated_By' => $user_data->name
                ]);
            }
        } elseif ($request->RecordType == 'General' and $balance_inout) {
            $balance_inout->update([
                'deleted_by' => $user_data->name
            ]);
            $balance_inout->delete();
        }
        $Purchases->update([
            'item' => $request->item_name,
            'amount' => $request->amount,
            'SellerName' => $request->DealerName,
            'RecordType' => $request->RecordType,
            'notes' => $request->notes,
            'user_name' => $user_data->name,
            'updated_By' => $user_data->name
        ]);


        return redirect()->route('Purchases.show')->with(['success' => 'تم التحديث بنجاح']);

    }


    /************************************ PlatformBalance *****************************/
    public function PlatformBalanceDelete(Request $request)
    {
        $user_data = Auth::user();
        $PlatformBalances = PlatformBalance::find($request->id);   // $Sales::where('id','') -> first();

        if (!$PlatformBalances) {
            return redirect()->back()->with(['error' => ('لم يتم إيجاد الصف في قاعدة البيانات')]);
        } else {
            $PlatformBalances->update([
                'deleted_by' => $user_data->name
            ]);
            $PlatformBalances->delete();

            return redirect()->back()->with(['success' => 'تم حذف البيان بنجاح ']);
        }
    }


    public function PlatformBalanceEdit(Request $request)
    {
        $user_data = Auth::user();
        $PlatformBalances = PlatformBalance::find($request->id);  // search in given table id only
        if (!$PlatformBalances)
            return redirect()->back()->with(['success' => 'لم يتم إيجاد الصنف في قاعدة بياناتنا ']);

        $PlatformBalances = PlatformBalance::select('id', 'notes', 'OoredooBalance', 'JawwalBalance', 'JawwalPayBalance', 'ElectricityBalance', 'OoredooBillsBalance', 'BankOfPalestineBalance', 'BankAlQudsBalance', 'BalanceType')->find($request->id);

        return view('Edit_Forms.PlatformsBalanceEdit', compact('PlatformBalances', 'user_data'));

    }

    public function PlatformBalanceUpdate(Request $request)
    {
        $user_data = Auth::user();
        $PlatformBalances = PlatformBalance::find($request->id);
        if (!$PlatformBalances)
            return redirect()->back()->with(['success' => 'لم يتم إيجاد الصنف في قاعدة بياناتنا ']);


        //update data
        $PlatformBalances->update([
            'OoredooBalance' => $request->OoredooBalance,
            'JawwalBalance' => $request->JawwalBalance,
            'JawwalPayBalance' => $request->JawwalPayBalance,
            'ElectricityBalance' => $request->ElectricityBalance,
            'OoredooBillsBalance' => $request->OoredooBillsBalance,
            'BankOfPalestineBalance' => $request->BankOfPalestineBalance,
            'BankAlQudsBalance' => $request->BankAlQudsBalance,
            'notes' => $request->notes,
            'BalanceType' => $request->BalanceType,
            'updated_By' => $user_data->name
        ]);

        return redirect()->route('PlatformBalance.show')->with(['success' => 'تم التحديث بنجاح']);

    }

    /************************************ PayMerchant *****************************/
    public function PayMerchantDelete(Request $request)
    {
        $user_data = Auth::user();
        $outs = Outs::find($request->id);
        $balance_inout = balance_inout::where('merchantpay_foreign_id', $request->id);

        if (!$balance_inout or !$outs) {
            return redirect()->back()->with(['error' => ('لم يتم إيجاد الصف في قاعدة البيانات')]);
        } else {
            $outs->update([
                'deleted_by' => $user_data->name
            ]);
            $balance_inout->update([
                'deleted_by' => $user_data->name
            ]);
            $balance_inout->delete();
            $outs->delete();

            return redirect()->back()->with(['success' => 'تم حذف البيان بنجاح ']);
        }

    }


    public function PayMerchantEdit(Request $request)
    {
        $user_data = Auth::user();

        $outs = Outs::find($request->id);
        $balance_inout = balance_inout::where('merchantpay_foreign_id', $request->id)->first();
        if (!$balance_inout or !$outs)
            return redirect()->back()->with(['Error' => 'لم يتم إيجاد الصنف في قاعدة بياناتنا ']);


        return view('Edit_Forms.payToMerchantEdit', get_defined_vars());

    }

    public function PayMerchantUpdate(Request $request)
    {
        $user_data = Auth::user();
        $outs = Outs::find($request->id);
        $balance_inout = balance_inout::where('merchantpay_foreign_id', $request->id)->first();
        if (!$balance_inout)
            return redirect()->back()->with(['Error' => 'لم يتم إيجاد الصنف في قاعدة بياناتنا ']);

        if ($request->PayMethod == 'Cash' or $request->PayMethod == 'under' or $request->PayMethod == 'check' and $balance_inout) {

            $outs->update([
                'deleted_by' => $user_data->name
            ]);
            $balance_inout->update([
                'deleted_by' => $user_data->name
            ]);
            $balance_inout->delete();
            $outs->delete();
            return redirect()->route('PayMerchant.show')->with([
                'success' => 'تم التعديل بنجاح، مع حذف السجل من هنا كون العملية أصبحت كاش وليست إلكترونية',
                'info' => 'للإطلاع على السجل بعد التعديل اذهب لصفحة المخرجات ،حيث أن العملية اصبحت في سجل المخرجات كدفعة للتجار'
            ]);

        }
        if ($request->PayMethod == 'under' or $request->PayMethod == 'check') {
            return "under";
        }
        //update data
        if ($request->PayMethod != 'Cash' or $request->PayMethod != 'under' or $request->PayMethod != 'check')
            $outs->update([
                'amount' => $request->amount,
                'item' => "دفعة إلى تاجر عن طريق $request->PayMethod",
                'RecordType' => $request->PayMethod,
                'beneficiary' => $request->merchant_name,
                'notes' => $request->notes,
                'updated_By' => $user_data->name
            ]);
        if ($request->PayMethod != 'Cash') {
            $balance_inout->update([
                'amount' => $request->amount,
                'platform_name' => $request->PayMethod,
                'updated_By' => $user_data->name,
                'notes' => "دفعة إلى  :$request->merchant_name ",
            ]);
        }


        return redirect()->route('PayMerchant.show')->with(['success' => 'تم التحديث بنجاح']);

    }


##########################################   Store Operation   ################################

    public function StoreDealersBuy(Request $request)
    {
        $user_data = Auth::user();
        $insertedId = DealersBuy::insertGetId([
            'item' => $request->item_name,
            'amount' => $request->amount,
            'SellerName' => $request->DealerName,
            'RecordType' => $request->RecordType,
            'notes' => $request->notes,
            'UserName' => $user_data->name,
            'created_at' => now()
        ]);

        $selectedValue = $request->input('RecordType');

        if ($selectedValue === 'Ooredoo') {
            $store_balance_in = balance_inout::create([
                'record_type' => 'مدخل',
                'platform_name' => $selectedValue, //Ooredoo
                'purchases_foreign_id' => $insertedId,
                'amount' => $request->amount + ($request->amount * (40 / 1000)),
                'notes' => "شحن المنصة من تاجر",
                'created_by' => $user_data->name
            ]);

        } elseif ($selectedValue === 'Jawwal') {
            $store_balance_in = balance_inout::create([
                'record_type' => 'مدخل',
                'platform_name' => $selectedValue,//Jawwal
                'purchases_foreign_id' => $insertedId,
                'amount' => $request->amount,
                'notes' => "شحن المنصة من تاجر",
                'created_by' => $user_data->name
            ]);
        } elseif ($selectedValue === 'JawwalPay') {
            $store_balance_in = balance_inout::create([
                'record_type' => 'مدخل',
                'platform_name' => $selectedValue, //JawwalPay
                'purchases_foreign_id' => $insertedId,
                'amount' => $request->amount,
                'notes' => "شحن المنصة من تاجر",
                'created_by' => $user_data->name
            ]);
        } elseif ($selectedValue === 'OoredooBills') {
            $store_balance_in = balance_inout::create([
                'record_type' => 'مدخل',
                'platform_name' => $selectedValue, //OoredooBills
                'purchases_foreign_id' => $insertedId,
                'amount' => $request->amount,
                'notes' => "شحن المنصة من تاجر",
                'created_by' => $user_data->name
            ]);
        } elseif ($selectedValue === 'Electricity') {
            $store_balance_in = balance_inout::create([
                'record_type' => 'مدخل',
                'platform_name' => $selectedValue, //Electricity
                'purchases_foreign_id' => $insertedId,
                'amount' => $request->amount + ($request->amount * (15 / 1000)),
                'notes' => "شحن المنصة من تاجر",
                'created_by' => $user_data->name
            ]);
        } else {

        }


        return redirect()->back()->with(['success' => "تم حفظ عملية شراء : $request->item_name   بمبلغ: ،$request->amount  من التاجر:، $request->DealerName"]);
    }

    public function StoreOuts(Request $request)
    {
        $user_data = Auth::user();


        if ($request->has('RecordType')) {
            $selectedValue = $request->input('RecordType');
            $selectedValue2 = $request->input('RecordType2');
            if ($selectedValue2 == "OutFromIn")
                $operationType = 4;
            else
                $operationType = 3;
            // تنفيذ العمليات المطلوبة بناءً على القيمة المختارة
            if ($selectedValue === 'bankOfPalestine') {
                $insertedID = Outs::insertGetId([
                    'item' => $request->item_name,
                    'amount' => $request->amount,
                    'RecordType' => $request->RecordType,
                    'service_number' => $operationType,
                    'beneficiary' => $request->beneficiary,
                    'notes' => "تم إخراجها إلى رصيد وسيلة الدفع المستخدمة",
                    'userName' => $user_data->name,
                    'created_at' => now()
                ]);

                $store_balance_in = balance_inout::create([
                    'record_type' => 'مدخل',
                    'platform_name' => $selectedValue, //bankOfPalestine
                    'outs_foreign_id' => $insertedID,
                    'amount' => $request->amount,
                    'notes' => "تم إضافة هذا المبلغ  إلى رصيد المتصة بناءاً على  تسجيل عملية إخراج لهذه المنصة ",
                    'created_by' => $user_data->name
                ]);


            } elseif ($selectedValue === 'bankquds') {

                $insertedID = Outs::insertGetId([
                    'item' => $request->item_name,
                    'amount' => $request->amount,
                    'RecordType' => $request->RecordType,
                    'service_number' => $operationType,
                    'beneficiary' => $request->beneficiary,
                    'notes' => "تم إخراجها إلى رصيد وسيلة الدفع المستخدمة",
                    'userName' => $user_data->name,
                    'created_at' => now()
                ]);


                $store_balance_in = balance_inout::create([
                    'record_type' => 'مدخل',
                    'platform_name' => $selectedValue, //bankquds
                    'outs_foreign_id' => $insertedID,
                    'amount' => $request->amount,
                    'notes' => "تم إضافة هذا المبلغ  إلى رصيد المتصة بناءاً على  تسجيل عملية إخراج لهذه المنصة ",
                    'created_by' => $user_data->name
                ]);


            } elseif ($selectedValue === 'JawwalPay') {
                $insertedID = Outs::insertGetId([
                    'item' => $request->item_name,
                    'amount' => $request->amount,
                    'RecordType' => $request->RecordType,
                    'service_number' => $operationType,
                    'beneficiary' => $request->beneficiary,
                    'notes' => "تم إخراجها إلى رصيد وسيلة الدفع المستخدمة",
                    'userName' => $user_data->name,
                    'created_at' => now()
                ]);


                $store_balance_in = balance_inout::create([
                    'record_type' => 'مدخل',
                    'platform_name' => $selectedValue, //JawwalPay
                    'outs_foreign_id' => $insertedID,
                    'amount' => $request->amount,
                    'notes' => "تم إضافة هذا المبلغ  إلى رصيد المتصة بناءاً على  تسجيل عملية إخراج لهذه المنصة ",
                    'created_by' => $user_data->name
                ]);


            } else {
                $sales = Outs::create([
                    'item' => $request->item_name,
                    'amount' => $request->amount,
                    'RecordType' => $request->RecordType,
                    'beneficiary' => $request->beneficiary,
                    'notes' => $request->notes,
                    'userName' => $user_data->name
                ]);
            }
        }

        return redirect()->back()->with(['success' => "،تم حفظ إخراج : $request->item_name  بمبلغ: ،$request->amount  إلى المستفيد:  ،$request->beneficiary"]);
    }

    public function StoreLend(Request $request)
    {
        $user_data = Auth::user();

        $insertedID = lenddata::insertGetId([
            'item' => $request->item_name,
            'RecordType' => $request->RecordType,
            'amount' => $request->amount,
            'quantity' => $request->quantity,
            'FirstPay' => $request->FirstPay,
            'debtorName' => $request->debtor_name,
            'notes' => $request->notes,
            'total' => $request->amount * $request->quantity,
            'UserName' => $user_data->name,
            'created_at' => now()
        ]);

        $selectedValue = $request->input('RecordType');

        if ($selectedValue === 'Ooredoo') {
            $store_balance_out = balance_inout::create([
                'record_type' => 'مخرج',
                'platform_name' => $selectedValue, //Ooredoo
                'loans_foreign_id' => $insertedID,
                'amount' => $request->amount * $request->quantity,
                'notes' => "دَين على $request->debtor_name",
                'created_by' => $user_data->name
            ]);

        } elseif ($selectedValue === 'Jawwal') {
            $store_balance_out = balance_inout::create([
                'record_type' => 'مخرج',
                'platform_name' => $selectedValue, //Jawwal
                'loans_foreign_id' => $insertedID,
                'amount' => $request->amount * $request->quantity,
                'notes' => "دَين على $request->debtor_name",
                'created_by' => $user_data->name
            ]);
        } elseif ($selectedValue === 'JawwalPay') {
            $store_balance_out = balance_inout::create([
                'record_type' => 'مخرج',
                'platform_name' => $selectedValue, //JawwalPay
                'loans_foreign_id' => $insertedID,
                'amount' => $request->amount * $request->quantity,
                'notes' => "دَين على $request->debtor_name",
                'created_by' => $user_data->name
            ]);
        } elseif ($selectedValue === 'OoredooBills') {
            $store_balance_out = balance_inout::create([
                'record_type' => 'مخرج',
                'platform_name' => $selectedValue, //OoredooBills
                'loans_foreign_id' => $insertedID,
                'amount' => $request->amount * $request->quantity,
                'notes' => "دَين على $request->debtor_name",
                'created_by' => $user_data->name
            ]);
        } elseif ($selectedValue === 'Electricity') {
            $store_balance_out = balance_inout::create([
                'record_type' => 'مخرج',
                'platform_name' => $selectedValue, //Electricity
                'loans_foreign_id' => $insertedID,
                'amount' => $request->amount * $request->quantity,
                'notes' => "دَين على $request->debtor_name",
                'created_by' => $user_data->name
            ]);
        } elseif ($selectedValue === 'installment_transaction') {
            $store_in_sales = dailydata::create([
                'item' => "دفعة أولى من معاملة تقسيط $request->debtor_name",
                'RecordType' => "دفعة أولى",
                'amount' => $request->FirstPay,
                'total' => $request->FirstPay,
                'lend_foreign_id' => $insertedID,
                'user_name' => $user_data->name,
                'notes' => "نوع الجهاز : $request->item_name
                مبلغ الدَين الاجمالي : $request->amount",
            ]);
        } else {

        }

        return redirect()->back()->with(['success' => "  تم حفظ دَين: $request->item_name بمبلغ: $request->amount إلى الجهة المخرج لها : ،$request->debtor_name "]);
    }


    public function StoreCustomerPay(Request $request)
    {
        $user_data = Auth::user();

        $insertedID = CustomerPay::insertGetId([
            'CustomerName' => $request->CustomerName,
            'PayMethod' => $request->PayMethod,
            'amount' => $request->amount,
            'notes' => "$request->notes",
            'userName' => $user_data->name,
            'created_at' => now()
        ]);


        $selectedValue = $request->input('PayMethod');

        // تنفيذ العمليات المطلوبة بناءً على القيمة المختارة
        if ($selectedValue === 'bankOfPalestine') {
            $store_balance_in = balance_inout::create([
                'record_type' => 'مدخل',
                'platform_name' => 'bankOfPalestine',
                'cuspay_foreign_id' => $insertedID,
                'amount' => $request->amount,
                'notes' => "دفعة من :$request->CustomerName ",
                'created_by' => $user_data->name
            ]);
            $sales = Outs::create([
                'item' => "إخراج إلى رصيد $request->PayMethod ",
                'amount' => $request->amount,
                'RecordType' => $selectedValue,
                'cuspay_foreign_id' => $insertedID,
                'service_number' => '2',
                'notes' => "دفعة من :$request->CustomerName ",
                'userName' => $user_data->name
            ]);


        } elseif ($selectedValue === 'bankquds') {
            $store_balance_in = balance_inout::create([
                'record_type' => 'مدخل',
                'platform_name' => 'bankquds',
                'cuspay_foreign_id' => $insertedID,
                'amount' => $request->amount,
                'notes' => "دفعة من :$request->CustomerName ",
                'created_by' => $user_data->name
            ]);
            $sales = Outs::create([
                'item' => "إخراج إلى رصيد $request->PayMethod ",
                'amount' => $request->amount,
                'notes' => "دفعة من :$request->CustomerName ",
                'cuspay_foreign_id' => $insertedID,
                'RecordType' => $selectedValue,
                'service_number' => '2',
                'userName' => $user_data->name
            ]);
        } elseif ($selectedValue === 'JawwalPay') {
            $store_balance_in = balance_inout::create([
                'record_type' => 'مدخل',
                'platform_name' => $selectedValue, //JawwalPay
                'cuspay_foreign_id' => $insertedID,
                'amount' => $request->amount,
                'notes' => "دفعة من :$request->CustomerName ",
                'created_by' => $user_data->name
            ]);
            $sales = Outs::create([
                'item' => "إخراج إلى رصيد $request->PayMethod ",
                'amount' => $request->amount,
                'notes' => "دفعة من :$request->CustomerName ",
                'RecordType' => $selectedValue,
                'service_number' => '2',
                'cuspay_foreign_id' => $insertedID,
                'userName' => $user_data->name
            ]);

        } else {

        }

        return redirect()->back()->with(['success' => " تم حفظ الدفعة من:$request->CustomerName ، طريقة الدفع : $request->PayMethod بمبلغ: $request->amount  "]);
    }

    public function StorePlatformBalance(Request $request)
    {
        $user_data = Auth::user();
        $OpenPlatformBalances = PlatformBalance::whereDate('created_at', today())->where('BalanceType', 'افتتاحي')->first();
        $ClosePlatformBalances = PlatformBalance::whereDate('created_at', today())->where('BalanceType', 'نهائي')->first();
        if ($request->BalanceType == 'افتتاحي' and $OpenPlatformBalances)
            return redirect()->back()->with(['Error' => 'سبق لك وأن أدخلت الأرصدة الافتتاحية لا يجوز الادخال مرتين لنفس اليوم، برجاء التعديل او حذف القديم وإعادة الإضافة']);
        if ($request->BalanceType == 'نهائي' and $ClosePlatformBalances)
            return redirect()->back()->with(['Error' => ' سبق لك وأن أدخلت الأرصدة النهائية لا يجوز الادخال مرتين لنفس اليوم، برجاء التعديل او حذف الفديم وإعادة الاضافة']);
        $sales = PlatformBalance::create([
            'OoredooBalance' => $request->OoredooBalance,
            'JawwalBalance' => $request->JawwalBalance,
            'JawwalPayBalance' => $request->JawwalPayBalance,
            'ElectricityBalance' => $request->ElectricityBalance,
            'OoredooBillsBalance' => $request->OoredooBillsBalance,
            'BankOfPalestineBalance' => $request->BankOfPalestineBalance,
            'BankAlQudsBalance' => $request->BankAlQudsBalance,
            'BalanceType' => $request->BalanceType,
            'notes' => $request->notes,
            'userName' => $user_data->name
        ]);
        return redirect()->back()->with(['success' => "تم حفظ أرصدة المنصات من نوع $request->BalanceType  بنجاح"]);
    }

    public function storeenddaily(Request $request)
    {
        $user_data = Auth::user();
        $Enddaily = end_daily_outs::whereDate('created_at', today())->first();
        if ($Enddaily)
            return redirect()->back()->with(['Error' => 'هناك سجل موجود ببيانات الترحيلات لهذا اليوم، يمكنك التعديل عليه أو حذفه']);

        $storetoEnddaily = end_daily_outs::create([
            'amount_usd' => $request->amount_usd,
            'amount_jod' => $request->amount_jod,
            'amount_ils' => $request->amount_ils,
            'amount_daily' => $request->amount_daily,
            'created_by' => $user_data->name,
        ]);
        if ($storetoEnddaily)
            return redirect()->back()->with(['success' => "تم حفظ الترحيلات اليومية بنجاح، كما تم تسجيل $request->amount_daily في يومية غداً "]);
    }


    public function store_pay_to_merchant(Request $request)
    {
        $user_data = Auth::user();
        $selectedValue = $request->input('PayMethod');


        if ($selectedValue === 'bankOfPalestine') {

            $insertedId = Outs::insertGetId([
                'amount' => $request->amount,
                'item' => " دفعة إلى تاجر عن طريق بنك فلسطين",
                'RecordType' => $selectedValue,
                'beneficiary' => $request->merchant_name,
                'service_number' => '1',
                'notes' => $request->notes,
                'userName' => $user_data->name,
                'created_at' => now()
            ]);

            $balance_out = balance_inout::create([
                'record_type' => 'مخرج',
                'platform_name' => $selectedValue,
                'service_number' => '1',
                'merchantpay_foreign_id' => $insertedId,
                'amount' => $request->amount,
                'notes' => "دفعة إلى  :$request->merchant_name ",
                'created_by' => $user_data->name
            ]);


        } elseif ($selectedValue === 'bankquds') {
            $insertedId = Outs::insertGetId([
                'amount' => $request->amount,
                'item' => " دفعة إلى تاجر عن طريق بنك القدس",
                'RecordType' => $selectedValue,
                'beneficiary' => $request->merchant_name,
                'service_number' => '1',
                'notes' => $request->notes,
                'userName' => $user_data->name,
                'created_at' => now()
            ]);

            $balance_out = balance_inout::create([
                'record_type' => 'مخرج',
                'platform_name' => $selectedValue,
                'service_number' => '1',
                'merchantpay_foreign_id' => $insertedId,
                'amount' => $request->amount,
                'notes' => "دفعة إلى  :$request->merchant_name ",
                'created_by' => $user_data->name
            ]);
        } elseif ($selectedValue === 'JawwalPay') {
            $insertedId = Outs::insertGetId([
                'amount' => $request->amount,
                'item' => " دفعة إلى تاجر عن طريق جوال باي",
                'RecordType' => $selectedValue,
                'beneficiary' => $request->merchant_name,
                'service_number' => '1',
                'notes' => $request->notes,
                'userName' => $user_data->name,
                'created_at' => now()
            ]);

            $balance_out = balance_inout::create([
                'record_type' => 'مخرج',
                'platform_name' => $selectedValue,
                'service_number' => '1',
                'merchantpay_foreign_id' => $insertedId,
                'amount' => $request->amount,
                'notes' => "دفعة إلى  :$request->merchant_name ",
                'created_by' => $user_data->name
            ]);
        } elseif ($selectedValue === 'under') {

            $note = note::create([
                'notes' => " تم إخراج مبلغ $request->amount إلى $request->merchant_name من الخزينة بالاسفل ",
                'user_name' => $user_data->name

            ]);
        } elseif ($selectedValue === 'check') {
            $note = note::create([
                'notes' => " تم إخراج شيك بقيمة $request->amount إلى $request->merchant_name  ",
                'user_name' => $user_data->name
            ]);
        } else {
            $Msg = "لم يتم تسجيل الدفعة، ما دام الدفع كاش توجه لتسجيل الدفعة من خلال صفحة المخرجات من الزر أدناه";
            $SUrl = 'OutsForm';
            return view('ErrorPage', compact('Msg', 'SUrl'));
        }

        return redirect()->back()->with(['success' => "تم حفظ الدفعة بمبلغ :$request->amount ، طريقة الدفع: $selectedValue للتاجر: ،$request->merchant_name "]);
    }

######################################### End Stors #######################################
    public function PlatformsBalanceForms()
    {
        $user_data = Auth::user();

        return view('Forms.PlatformsBalanceForms', compact('user_data'));
    }

    public function payToMerchantForm()
    {
        $user_data = Auth::user();

        return view('Forms.payToMerchant', compact('user_data'));
    }

    public function OutForm()
    {
        $user_data = Auth::user();
        return view('Forms.OutForm', compact('user_data'));
    }

    public function LendForm()
    {
        $user_data = Auth::user();
        return view('Forms.LendForm', compact('user_data'));
    }

    public function DealersBuyForm()
    {
        $user_data = Auth::user();
        return view('Forms.DealersBuyForm', compact('user_data'));
    }

    public function CustomersPaymentForm()
    {
        $user_data = Auth::user();
        return view('Forms.CustomersPaymentForm', compact('user_data'));
    }

    public function SalesShow()
    {

        $sales = dailydata::whereDate('created_at', today())->get();
        $todayTotal = dailydata::whereDate('created_at', today())->sum('total');
        $date = today()->format('Y-m-d');


        return view('Show.SalesShow', compact('sales', 'todayTotal', 'date'));
    }

    public function SalesShowWhithDates(Request $request)
    {

        $date = $request->input('date');


        if ($date) {
            $sales = dailydata::whereDate('created_at', $date)
                ->get();

            $todayTotal = dailydata::whereDate('created_at', $date)->sum('total');

            return view('Show.SalesShow', compact('sales', 'todayTotal', 'date'));
        } else {

            return route('sales.show');
        }
    }


    public function PurchasesShow()
    {

        $Purchases = DealersBuy::whereDate('created_at', today())->get();
        $todayTotal = $Purchases->sum('amount');
        $date = today()->format('Y-m-d');
        return view('Show.PurchasesShow', compact('Purchases', 'date', 'todayTotal'));
    }

    public function PurchasesShowWithDate(Request $request)
    {

        $date = $request->input('date');
        $todayTotal = DealersBuy::whereDate('created_at', $date)->sum('amount');


        if ($date) {
            $Purchases = DealersBuy::whereDate('created_at', $date)
                ->get();

            return view('Show.PurchasesShow', compact('Purchases', 'date', 'todayTotal'));
        } else {

            return route('Purchases.show');
        }
    }

    public function CustomerPaymentsShow()
    {

        $CusPays = CustomerPay::whereDate('created_at', today())->get();
        $todayTotal = CustomerPay::whereDate('created_at', today())->sum('amount');
        $date = today()->format('Y-m-d');


        return view('Show.CustomerPaymentsShow', get_defined_vars());
    }

    public function CustomerPaymentsShowWithDate(Request $request)
    {

        $date = $request->input('date');
        $todayTotal = CustomerPay::whereDate('created_at', $date)->sum('amount');


        if ($date) {
            $CusPays = CustomerPay::whereDate('created_at', $date)
                ->get();

            return view('Show.CustomerPaymentsShow', get_defined_vars());
        } else {

            return route('CustomerPay.show');
        }
    }

    public function LoansShow()
    {

        $Loans = lenddata::whereDate('created_at', today())->get();
        $todayTotal = lenddata::whereDate('created_at', today())->sum('total');
        $date = today()->format('Y-m-d');


        return view('Show.LoansShow', compact('Loans', 'todayTotal', 'date'));
    }

    public function LoansShowWithDate(Request $request)
    {

        $date = $request->input('date');
        $todayTotal = lenddata::whereDate('created_at', $date)->sum('total');


        if ($date) {
            $Loans = lenddata::whereDate('created_at', $date)
                ->get();

            return view('Show.LoansShow', compact('Loans', 'date', 'todayTotal'));
        } else {

            return route('Loans.show');
        }
    }

    public function OutsShow()
    {

        $Outs = Outs::whereDate('created_at', today())->get();
        $todayTotal = Outs::whereDate('created_at', today())->sum('amount');
        $date = today()->format('Y-m-d');


        return view('Show.OutsShow', compact('Outs', 'todayTotal', 'date'));
    }

    public function OutsShowWithDate(Request $request)
    {

        $date = $request->input('date');
        $todayTotal = Outs::whereDate('created_at', $date)->sum('amount');


        if ($date) {
            $Outs = Outs::whereDate('created_at', $date)
                ->get();

            return view('Show.OutsShow', compact('Outs', 'date', 'todayTotal'));
        } else {

            return route('Outs.show');
        }
    }

    public function PlatformBalanceShow()
    {

//       $c=balance_inout::all();
//        return $c;
        $PlatsBalance = PlatformBalance::whereDate('created_at', today())->get();
        $date = today()->format('Y-m-d');

        $todayOpenBal = PlatformBalance::whereDate('created_at', today())->where('BalanceType', 'افتتاحي')->first();
        $todayCloseBal = PlatformBalance::whereDate('created_at', today())->where('BalanceType', 'نهائي')->first();
        if ($todayOpenBal and $todayCloseBal) {
            $OoredooBalanceEnd = $todayCloseBal->OoredooBalance - $todayOpenBal->OoredooBalance;
            $JawwalBalanceEnd = $todayCloseBal->JawwalBalance - $todayOpenBal->JawwalBalance;
            $JawwalPayBalanceEnd = $todayCloseBal->JawwalPayBalance - $todayOpenBal->JawwalPayBalance;
            $ElectricityBalanceEnd = $todayCloseBal->ElectricityBalance - $todayOpenBal->ElectricityBalance;
            $OoredooBillsBalanceEnd = $todayCloseBal->OoredooBillsBalance - $todayOpenBal->OoredooBillsBalance;
            $BankOfPalestineBalanceEnd = $todayCloseBal->BankOfPalestineBalance - $todayOpenBal->BankOfPalestineBalance;
            $BankAlQudsBalanceEnd = $todayCloseBal->BankAlQudsBalance - $todayOpenBal->BankAlQudsBalance;
        }


        return view('Show.PlatformBalanceShow', get_defined_vars());
    }


    public function PlatformBalanceShowWithDate(Request $request)
    {

        $date = $request->input('date');


        if ($date) {
            $PlatsBalance = PlatformBalance::whereDate('created_at', $date)
                ->get();


            $todayOpenBal = PlatformBalance::whereDate('created_at', $date)->where('BalanceType', 'افتتاحي')->first();
            $todayCloseBal = PlatformBalance::whereDate('created_at', $date)->where('BalanceType', 'نهائي')->first();
            $Msg = "";
            $SUrl = "";
            $OoredooBalanceEnd = "";
            if ($todayOpenBal == null) {
                $Msg = "انت لم تدخل الرصيد الافتتاحي لمحطات الشحن لهذا اليوم";
                $SUrl = 'PlatformBalanceForm';
                return view('ErrorPage', compact('Msg', 'SUrl'));
            } elseif ($todayCloseBal == null) {
                $Msg = "انت لم تدخل الرصيد النهائي لمحطات الشحن لهذا اليوم";
                $SUrl = 'PlatformBalanceForm';
                return view('ErrorPage', compact('Msg', 'SUrl'));

            } else {

                $OoredooBalanceEnd = $todayCloseBal->OoredooBalance - $todayOpenBal->OoredooBalance;
                $JawwalBalanceEnd = $todayCloseBal->JawwalBalance - $todayOpenBal->JawwalBalance;
                $JawwalPayBalanceEnd = $todayCloseBal->JawwalPayBalance - $todayOpenBal->JawwalPayBalance;
                $ElectricityBalanceEnd = $todayCloseBal->ElectricityBalance - $todayOpenBal->ElectricityBalance;
                $OoredooBillsBalanceEnd = $todayCloseBal->OoredooBillsBalance - $todayOpenBal->OoredooBillsBalance;
                $BankOfPalestineBalanceEnd = $todayCloseBal->BankOfPalestineBalance - $todayOpenBal->BankOfPalestineBalance;
                $BankAlQudsBalanceEnd = $todayCloseBal->BankAlQudsBalance - $todayOpenBal->BankAlQudsBalance;


            }

            return view('Show.PlatformBalanceShow', get_defined_vars());
        } else {

            return route('PlatformBalance.show');
        }
    }


    public function DailySummary()
    {

        $openbalance = PlatformBalance::whereDate('created_at', today())->where('BalanceType', 'افتتاحي')->first();
        $closebalance = PlatformBalance::whereDate('created_at', today())->where('BalanceType', 'نهائي')->first();
        $balancsales = balance_inout::whereDate('created_at', today())->get();
        if (!$openbalance) {
            $Msg = "عليك أولاً إدخال الارصدة الافتاحية الخاصة بمحطات الشحن";
            $SUrl = 'PlatformBalanceForm';
            return view('ErrorPage', compact('Msg', 'SUrl'));
        }

        $balancsales_in = $balancsales->where('record_type', 'مدخل');
        $balancsales_out = $balancsales->where('record_type', 'مخرج');

        $totalOoredooLoan = lenddata::whereDate('created_at', today())->where('RecordType', 'Ooredoo')->sum('total');
        $totalJawwalLoan = lenddata::whereDate('created_at', today())->where('RecordType', 'Jawwal')->sum('total');
        $totalOoredooBillsLoan = lenddata::whereDate('created_at', today())->where('RecordType', 'OoredooBills')->sum('total');
        $totalJawwalPayLoan = lenddata::whereDate('created_at', today())->where('RecordType', 'JawwalPay')->sum('total');
        $totalElectricityLoan = lenddata::whereDate('created_at', today())->where('RecordType', 'Electricity')->sum('total');

        ################## Platform Dealer Buy #########################


        #Purchases From Dealer
        $TotalOoredooBalanceinDealer = $balancsales->where('record_type', 'مدخل')->where('platform_name', 'Ooredoo')->whereNotNull('purchases_foreign_id')->sum('amount');
        $TotalJawwalBalanceinDealer = $balancsales->where('record_type', 'مدخل')->where('platform_name', 'Jawwal')->whereNotNull('purchases_foreign_id')->sum('amount');
        $TotalJawwalPayBalanceinDealer = $balancsales->where('record_type', 'مدخل')->where('platform_name', 'JawwalPay')->whereNotNull('purchases_foreign_id')->sum('amount');
        $TotalOoredooBillsBalanceinDealer = $balancsales->where('record_type', 'مدخل')->where('platform_name', 'OoredooBills')->whereNotNull('purchases_foreign_id')->sum('amount');
        $TotalElectricityBalanceinDealer = $balancsales->where('record_type', 'مدخل')->where('platform_name', 'Electricity')->whereNotNull('purchases_foreign_id')->sum('amount');

        #Cash Out Balance
        $TotalOoredooBalanceCashOut = $balancsales->where('record_type', 'مخرج')->where('platform_name', 'Ooredoo')->whereNotNull('sales_foreign_id')->sum('amount');
        $TotalJawwalBalanceCashOut = $balancsales->where('record_type', 'مخرج')->where('platform_name', 'Jawwal')->whereNotNull('sales_foreign_id')->sum('amount');
        $TotalJawwalPayBalanceCashOut = $balancsales->where('record_type', 'مخرج')->where('platform_name', 'JawwalPay')->whereNotNull('sales_foreign_id')->sum('amount');
        $TotalOoredooBillsBalanceCashOut = $balancsales->where('record_type', 'مخرج')->where('platform_name', 'OoredooBills')->whereNotNull('sales_foreign_id')->sum('amount');
        $TotalElectricityBalanceCashOut = $balancsales->where('record_type', 'مخرج')->where('platform_name', 'Electricity')->whereNotNull('sales_foreign_id')->sum('amount');

        # Loans Balance
        $TotalOoredooBalanceLoans = $balancsales->where('record_type', 'مخرج')->where('platform_name', 'Ooredoo')->whereNotNull('loans_foreign_id')->sum('amount');
        $TotalJawwalBalanceLoans = $balancsales->where('record_type', 'مخرج')->where('platform_name', 'Jawwal')->whereNotNull('loans_foreign_id')->sum('amount');
        $TotalJawwalPayBalanceLoans = $balancsales->where('record_type', 'مخرج')->where('platform_name', 'JawwalPay')->whereNotNull('loans_foreign_id')->sum('amount');
        $TotalOoredooBillsBalanceLoans = $balancsales->where('record_type', 'مخرج')->where('platform_name', 'OoredooBills')->whereNotNull('loans_foreign_id')->sum('amount');
        $TotalElectricityBalanceLoans = $balancsales->where('record_type', 'مخرج')->where('platform_name', 'Electricity')->whereNotNull('loans_foreign_id')->sum('amount');

        # Ooredoo Sim Detail
        $totalOoredooSimActiveCashIn = dailydata::whereDate('created_at', today())->where('RecordType', 'OoredooSim')->sum('total');
        $SIMactivationFees = lenddata::whereDate('created_at', today())->where('RecordType', 'OoredooSim')->sum('total');

        #Total Balance in to all platform
        $OoredooTotalIn = $balancsales_in->where('platform_name', 'Ooredoo')->sum('amount');
        $JawwalTotalIn = $balancsales_in->where('platform_name', 'Jawwal')->sum('amount');
        $JawwalPayTotalIn = $balancsales_in->where('platform_name', 'JawwalPay')->sum('amount');
        $OoredooBillsTotalIn = $balancsales_in->where('platform_name', 'OoredooBills')->sum('amount');
        $ElectricityTotalIn = $balancsales_in->where('platform_name', 'Electricity')->sum('amount');
        $BopTotalIn = $balancsales_in->where('platform_name', 'bankOfPalestine')->sum('amount');
        $BankQudsTotalIn = $balancsales_in->where('platform_name', 'bankquds')->sum('amount');

        #Total Balance Out to all platform
        $OoredooTotalOut = $balancsales_out->where('platform_name', 'Ooredoo')->sum('amount');
        $JawwalTotalOut = $balancsales_out->where('platform_name', 'Jawwal')->sum('amount');
        $JawwalPayTotalOut = $balancsales_out->where('platform_name', 'JawwalPay')->sum('amount');
        $OoredooBillsTotalOut = $balancsales_out->where('platform_name', 'OoredooBills')->sum('amount');
        $ElectricityTotalOut = $balancsales_out->where('platform_name', 'Electricity')->sum('amount');
        $BopTotalOut = $balancsales_out->where('platform_name', 'bankOfPalestine')->sum('amount');
        $BankQudsTotalOut = $balancsales_out->where('platform_name', 'bankquds')->sum('amount');


        #jawwalpay with merchant transaction
        $JawwalPayMerchantPay = $balancsales->where('record_type', 'مخرج')->where('platform_name', 'JawwalPay')->whereNotNull('merchantpay_foreign_id')->sum('amount');
        $JawwalPayCustPay = $balancsales->where('record_type', 'مدخل')->where('platform_name', 'JawwalPay')->whereNotNull('cuspay_foreign_id')->sum('amount');

        #special
        if ($closebalance and $openbalance) {
            if ($closebalance->OoredooBalance != null)
                $TotalOoredooBalanceCashSale = $openbalance->OoredooBalance + $TotalOoredooBalanceinDealer - $closebalance->OoredooBalance - $TotalOoredooBalanceLoans - $TotalOoredooBalanceCashOut;
            if ($closebalance->JawwalBalance != null)
                $TotalJawwalBalanceCashSale = $openbalance->JawwalBalance + $TotalJawwalBalanceinDealer - $closebalance->JawwalBalance - $TotalJawwalBalanceLoans - $TotalJawwalBalanceCashOut;
            if ($closebalance->JawwalPayBalance != null)
                $TotalJawwalPayBalanceCashSale = $openbalance->JawwalPayBalance + $TotalJawwalPayBalanceinDealer - $closebalance->JawwalPayBalance - $TotalJawwalPayBalanceLoans - $TotalJawwalPayBalanceCashOut - $JawwalPayMerchantPay + $JawwalPayCustPay;
            if ($closebalance->OoredooBillsBalance != null)
                $TotalOoredooBillsBalanceCashSale = $openbalance->OoredooBillsBalance + $TotalOoredooBillsBalanceinDealer - $closebalance->OoredooBillsBalance - $TotalOoredooBillsBalanceLoans - $TotalOoredooBillsBalanceCashOut;
            if ($closebalance->ElectricityBalance != null)
                $TotalElectricityBalanceCashSale = $openbalance->ElectricityBalance + $TotalElectricityBalanceinDealer - $closebalance->ElectricityBalance - $TotalElectricityBalanceLoans - $TotalElectricityBalanceCashOut;


            $OoredooEnd = $openbalance->OoredooBalance + $OoredooTotalIn - $OoredooTotalOut;
            $JawwalEnd = $openbalance->JawwalBalance + $JawwalTotalIn - $JawwalTotalOut;
            $JawwalPayEnd = $openbalance->JawwalPayBalance + $JawwalTotalIn - $JawwalPayTotalOut;
            $OoredooBillsEnd = $openbalance->OoredooBillsBalance + $JawwalTotalIn - $OoredooBillsTotalOut;
            $ElectricityEnd = $openbalance->ElectricityBalance + $JawwalTotalIn - $ElectricityTotalOut;

            $BopEnd = $openbalance->BankOfPalestineBalance + $BopTotalIn - $BopTotalOut;
            $BankQudsEnd = $openbalance->BankAlQudsBalance + $BankQudsTotalIn - $BankQudsTotalOut;


        }

//        $TotalOoredooBuyDealer=balance_sale::whereDate('created_at',today() )->where('RecordType','Ooredooin');
//        $TotalJawwalBuyDealer=balance_sale::whereDate('created_at',today() )->where('RecordType','Jawwalin');
//        $TotalJawwalPayBuyDealer=DealersBuy::whereDate('created_at',today() )->where('RecordType','JawwalPay')->sum('amount');
//        $TotalOoredooBillsBuyDealer=balance_sale::whereDate('created_at',today() )->where('RecordType','OoredooBillsin');
//        $TotalElectricityBuyDealer=balance_sale::whereDate('created_at',today() )->where('RecordType','Electricityin');
//        $TotalBankOfPalestine=balance_sale::whereDate('created_at',today() )->where('RecordType','OoredooBillsin');
//        $TotalBankQuds=balance_sale::whereDate('created_at',today() )->where('RecordType','Electricityin');

        $TotalCustPay = CustomerPay::whereDate('created_at', today())->sum('amount');
        $TotalSales = dailydata::whereDate('created_at', today())->sum('total');
        $TotalBuy = DealersBuy::whereDate('created_at', today())->sum('amount');

//
//        }else {
//           $OoredooEnd = $openbalance->OoredooBalance - $balancsales->ooredoo - $totalOoredooLoan + $Balancein->Ooredooin;
//            $OoredooBillsEnd = $openbalance->OoredooBillsBalance - $balancsales->ooredoobills - $totalOoredooBillsLoan + $Balancein->OoredooBillsin;
//            $JawwalEnd = $openbalance->JawwalBalance - $balancsales->jawwal - $totalJawwalLoan + $Balancein->Jawwalin;
//            $JawwalpayEnd = $openbalance->JawwalPayBalance - $balancsales->jawwalpay - $totalJawwalPayLoan + $Balancein->JawwalPayin;
//            $ElectricityEnd = $openbalance->ElectricityBalance - $balancsales->electricity - $totalElectricityLoan + $Balancein->Electricityin;
//            $BopEnd = $openbalance->BankOfPalestineBalance - $balancsales->bop+$Balancein->bopin;
//            $BankQudsEnd = $openbalance->BankAlQudsBalance - $balancsales->bankquds+$Balancein->bankqudsin;
//        }
        ################## Entire & Outs & Final #########################

        #Entire
        $DailySalesTotal = dailydata::whereDate('created_at', today())->sum('total');
        $CustomerPayTotal = CustomerPay::whereDate('created_at', today())->sum('amount');

        $dailyEntireTotal = $DailySalesTotal + $CustomerPayTotal;

        #Outs

        $OutsTotalGeneral = Outs::whereDate('created_at', today())->whereNull('service_number')->sum('amount');
        $OutsTotalCustomerPay = Outs::whereDate('created_at', today())->where('service_number', '2')->sum('amount');
        $OutsTotalCustomerPay2 = Outs::whereDate('created_at', today())->where('service_number', '4')->sum('amount');

        #Final
        $finalBalance = $dailyEntireTotal - $OutsTotalGeneral - $OutsTotalCustomerPay;


        return view('SummaryPage', get_defined_vars());
    }

    public function print_daily_date()
    {




    $openbalance = PlatformBalance::whereDate('created_at', today())->where('BalanceType', 'افتتاحي')->first();
        $closebalance = PlatformBalance::whereDate('created_at', today())->where('BalanceType', 'نهائي')->first();
        $balancsales = balance_inout::whereDate('created_at', today())->get();
        if (!$openbalance) {
            $Msg = "عليك أولاً إدخال الارصدة الافتاحية الخاصة بمحطات الشحن";
            $SUrl = 'PlatformBalanceForm';
            return view('ErrorPage', compact('Msg', 'SUrl'));
        }

        $balancsales_in = $balancsales->where('record_type', 'مدخل');
        $balancsales_out = $balancsales->where('record_type', 'مخرج');

        $totalOoredooLoan = lenddata::whereDate('created_at', today())->where('RecordType', 'Ooredoo')->sum('total');
        $totalJawwalLoan = lenddata::whereDate('created_at', today())->where('RecordType', 'Jawwal')->sum('total');
        $totalOoredooBillsLoan = lenddata::whereDate('created_at', today())->where('RecordType', 'OoredooBills')->sum('total');
        $totalJawwalPayLoan = lenddata::whereDate('created_at', today())->where('RecordType', 'JawwalPay')->sum('total');
        $totalElectricityLoan = lenddata::whereDate('created_at', today())->where('RecordType', 'Electricity')->sum('total');

        ################## Platform Dealer Buy #########################


        #Purchases From Dealer
        $TotalOoredooBalanceinDealer = $balancsales->where('record_type', 'مدخل')->where('platform_name', 'Ooredoo')->whereNotNull('purchases_foreign_id')->sum('amount');
        $TotalJawwalBalanceinDealer = $balancsales->where('record_type', 'مدخل')->where('platform_name', 'Jawwal')->whereNotNull('purchases_foreign_id')->sum('amount');
        $TotalJawwalPayBalanceinDealer = $balancsales->where('record_type', 'مدخل')->where('platform_name', 'JawwalPay')->whereNotNull('purchases_foreign_id')->sum('amount');
        $TotalOoredooBillsBalanceinDealer = $balancsales->where('record_type', 'مدخل')->where('platform_name', 'OoredooBills')->whereNotNull('purchases_foreign_id')->sum('amount');
        $TotalElectricityBalanceinDealer = $balancsales->where('record_type', 'مدخل')->where('platform_name', 'Electricity')->whereNotNull('purchases_foreign_id')->sum('amount');

        #Cash Out Balance
        $TotalOoredooBalanceCashOut = $balancsales->where('record_type', 'مخرج')->where('platform_name', 'Ooredoo')->whereNotNull('sales_foreign_id')->sum('amount');
        $TotalJawwalBalanceCashOut = $balancsales->where('record_type', 'مخرج')->where('platform_name', 'Jawwal')->whereNotNull('sales_foreign_id')->sum('amount');
        $TotalJawwalPayBalanceCashOut = $balancsales->where('record_type', 'مخرج')->where('platform_name', 'JawwalPay')->whereNotNull('sales_foreign_id')->sum('amount');
        $TotalOoredooBillsBalanceCashOut = $balancsales->where('record_type', 'مخرج')->where('platform_name', 'OoredooBills')->whereNotNull('sales_foreign_id')->sum('amount');
        $TotalElectricityBalanceCashOut = $balancsales->where('record_type', 'مخرج')->where('platform_name', 'Electricity')->whereNotNull('sales_foreign_id')->sum('amount');

        # Loans Balance
        $TotalOoredooBalanceLoans = $balancsales->where('record_type', 'مخرج')->where('platform_name', 'Ooredoo')->whereNotNull('loans_foreign_id')->sum('amount');
        $TotalJawwalBalanceLoans = $balancsales->where('record_type', 'مخرج')->where('platform_name', 'Jawwal')->whereNotNull('loans_foreign_id')->sum('amount');
        $TotalJawwalPayBalanceLoans = $balancsales->where('record_type', 'مخرج')->where('platform_name', 'JawwalPay')->whereNotNull('loans_foreign_id')->sum('amount');
        $TotalOoredooBillsBalanceLoans = $balancsales->where('record_type', 'مخرج')->where('platform_name', 'OoredooBills')->whereNotNull('loans_foreign_id')->sum('amount');
        $TotalElectricityBalanceLoans = $balancsales->where('record_type', 'مخرج')->where('platform_name', 'Electricity')->whereNotNull('loans_foreign_id')->sum('amount');

        # Ooredoo Sim Detail
        $totalOoredooSimActiveCashIn = dailydata::whereDate('created_at', today())->where('RecordType', 'OoredooSim')->sum('total');
        $SIMactivationFees = lenddata::whereDate('created_at', today())->where('RecordType', 'OoredooSim')->sum('total');

        #Total Balance in to all platform
        $OoredooTotalIn = $balancsales_in->where('platform_name', 'Ooredoo')->sum('amount');
        $JawwalTotalIn = $balancsales_in->where('platform_name', 'Jawwal')->sum('amount');
        $JawwalPayTotalIn = $balancsales_in->where('platform_name', 'JawwalPay')->sum('amount');
        $OoredooBillsTotalIn = $balancsales_in->where('platform_name', 'OoredooBills')->sum('amount');
        $ElectricityTotalIn = $balancsales_in->where('platform_name', 'Electricity')->sum('amount');
        $BopTotalIn = $balancsales_in->where('platform_name', 'bankOfPalestine')->sum('amount');
        $BankQudsTotalIn = $balancsales_in->where('platform_name', 'bankquds')->sum('amount');

        #Total Balance Out to all platform
        $OoredooTotalOut = $balancsales_out->where('platform_name', 'Ooredoo')->sum('amount');
        $JawwalTotalOut = $balancsales_out->where('platform_name', 'Jawwal')->sum('amount');
        $JawwalPayTotalOut = $balancsales_out->where('platform_name', 'JawwalPay')->sum('amount');
        $OoredooBillsTotalOut = $balancsales_out->where('platform_name', 'OoredooBills')->sum('amount');
        $ElectricityTotalOut = $balancsales_out->where('platform_name', 'Electricity')->sum('amount');
        $BopTotalOut = $balancsales_out->where('platform_name', 'bankOfPalestine')->sum('amount');
        $BankQudsTotalOut = $balancsales_out->where('platform_name', 'bankquds')->sum('amount');


        #jawwalpay with merchant transaction
        $JawwalPayMerchantPay = $balancsales->where('record_type', 'مخرج')->where('platform_name', 'JawwalPay')->whereNotNull('merchantpay_foreign_id')->sum('amount');
        $JawwalPayCustPay = $balancsales->where('record_type', 'مدخل')->where('platform_name', 'JawwalPay')->whereNotNull('cuspay_foreign_id')->sum('amount');

        #special
        if ($closebalance and $openbalance) {
            if ($closebalance->OoredooBalance != null)
                $TotalOoredooBalanceCashSale = $openbalance->OoredooBalance + $TotalOoredooBalanceinDealer - $closebalance->OoredooBalance - $TotalOoredooBalanceLoans - $TotalOoredooBalanceCashOut;
            if ($closebalance->JawwalBalance != null)
                $TotalJawwalBalanceCashSale = $openbalance->JawwalBalance + $TotalJawwalBalanceinDealer - $closebalance->JawwalBalance - $TotalJawwalBalanceLoans - $TotalJawwalBalanceCashOut;
            if ($closebalance->JawwalPayBalance != null)
                $TotalJawwalPayBalanceCashSale = $openbalance->JawwalPayBalance + $TotalJawwalPayBalanceinDealer - $closebalance->JawwalPayBalance - $TotalJawwalPayBalanceLoans - $TotalJawwalPayBalanceCashOut - $JawwalPayMerchantPay + $JawwalPayCustPay;
            if ($closebalance->OoredooBillsBalance != null)
                $TotalOoredooBillsBalanceCashSale = $openbalance->OoredooBillsBalance + $TotalOoredooBillsBalanceinDealer - $closebalance->OoredooBillsBalance - $TotalOoredooBillsBalanceLoans - $TotalOoredooBillsBalanceCashOut;
            if ($closebalance->ElectricityBalance != null)
                $TotalElectricityBalanceCashSale = $openbalance->ElectricityBalance + $TotalElectricityBalanceinDealer - $closebalance->ElectricityBalance - $TotalElectricityBalanceLoans - $TotalElectricityBalanceCashOut;


            $OoredooEnd = $openbalance->OoredooBalance + $OoredooTotalIn - $OoredooTotalOut;
            $JawwalEnd = $openbalance->JawwalBalance + $JawwalTotalIn - $JawwalTotalOut;
            $JawwalPayEnd = $openbalance->JawwalPayBalance + $JawwalTotalIn - $JawwalPayTotalOut;
            $OoredooBillsEnd = $openbalance->OoredooBillsBalance + $JawwalTotalIn - $OoredooBillsTotalOut;
            $ElectricityEnd = $openbalance->ElectricityBalance + $JawwalTotalIn - $ElectricityTotalOut;

            $BopEnd = $openbalance->BankOfPalestineBalance + $BopTotalIn - $BopTotalOut;
            $BankQudsEnd = $openbalance->BankAlQudsBalance + $BankQudsTotalIn - $BankQudsTotalOut;


        }

//        $TotalOoredooBuyDealer=balance_sale::whereDate('created_at',today() )->where('RecordType','Ooredooin');
//        $TotalJawwalBuyDealer=balance_sale::whereDate('created_at',today() )->where('RecordType','Jawwalin');
//        $TotalJawwalPayBuyDealer=DealersBuy::whereDate('created_at',today() )->where('RecordType','JawwalPay')->sum('amount');
//        $TotalOoredooBillsBuyDealer=balance_sale::whereDate('created_at',today() )->where('RecordType','OoredooBillsin');
//        $TotalElectricityBuyDealer=balance_sale::whereDate('created_at',today() )->where('RecordType','Electricityin');
//        $TotalBankOfPalestine=balance_sale::whereDate('created_at',today() )->where('RecordType','OoredooBillsin');
//        $TotalBankQuds=balance_sale::whereDate('created_at',today() )->where('RecordType','Electricityin');

        $TotalCustPay = CustomerPay::whereDate('created_at', today())->sum('amount');
        $TotalSales = dailydata::whereDate('created_at', today())->sum('total');
        $TotalBuy = DealersBuy::whereDate('created_at', today())->sum('amount');

//
//        }else {
//           $OoredooEnd = $openbalance->OoredooBalance - $balancsales->ooredoo - $totalOoredooLoan + $Balancein->Ooredooin;
//            $OoredooBillsEnd = $openbalance->OoredooBillsBalance - $balancsales->ooredoobills - $totalOoredooBillsLoan + $Balancein->OoredooBillsin;
//            $JawwalEnd = $openbalance->JawwalBalance - $balancsales->jawwal - $totalJawwalLoan + $Balancein->Jawwalin;
//            $JawwalpayEnd = $openbalance->JawwalPayBalance - $balancsales->jawwalpay - $totalJawwalPayLoan + $Balancein->JawwalPayin;
//            $ElectricityEnd = $openbalance->ElectricityBalance - $balancsales->electricity - $totalElectricityLoan + $Balancein->Electricityin;
//            $BopEnd = $openbalance->BankOfPalestineBalance - $balancsales->bop+$Balancein->bopin;
//            $BankQudsEnd = $openbalance->BankAlQudsBalance - $balancsales->bankquds+$Balancein->bankqudsin;
//        }
        ################## Entire & Outs & Final #########################

        #Entire
        $DailySalesTotal = dailydata::whereDate('created_at', today())->sum('total');
        $CustomerPayTotal = CustomerPay::whereDate('created_at', today())->sum('amount');

        $dailyEntireTotal = $DailySalesTotal + $CustomerPayTotal;

        #Outs

        $OutsTotalGeneral = Outs::whereDate('created_at', today())->whereNull('service_number')->sum('amount');
        $OutsTotalCustomerPay = Outs::whereDate('created_at', today())->where('service_number', '2')->sum('amount');
        $OutsTotalCustomerPay2 = Outs::whereDate('created_at', today())->where('service_number', '4')->sum('amount');

        #Final
        $finalBalance = $dailyEntireTotal - $OutsTotalGeneral - $OutsTotalCustomerPay;


//        $dompdf = new Dompdf();
//
//
//        // قم بتعيين HTML الخاص بالصفحة التي ترغب في تحويلها إلى PDF
//        $html = view('print')->render();
//
//        // تحميل HTML إلى مكتبة dompdf
//        $dompdf->loadHtml(view('print'));
//
//        // إعداد المكتبة لتوليد PDF
//        $dompdf->setPaper('A4', 'portrait');
//
//        // توليد الصفحة PDF
//        $dompdf->render();
//       $filename='DailyData_' . Carbon::now()->format('Y-m-d') . '.pdf';
//
//        // حفظ الصفحة PDF على القرص
//        $dompdf->stream($filename, ["Attachment" => false]);
////
}
################################ daily notes ###################################
    public function DailyNotesShow(){
      $date=today()->format('Y-m-d');
        $note=note::whereDate('created_at',today())->get();
     return view('DailyNotesShow',get_defined_vars());
    }
    public function storenotes(Request $request){
      $date=today()->format('Y-m-d');
        $user_data = Auth::user();
      $dayNotes=note::create([
          'notes'=>$request->notes,
          'user_name'=>$user_data->name
      ]);
        return redirect()->back()->with(['success'=> 'تم الحفظ بنجاح']);
    }

    public function noteDelete(Request $request){
        $user_data = Auth::user();
        $note = note::find($request -> id);   // $Sales::where('id','') -> first();

        if (!$note){
            return redirect()->back()->with(['success' => 'لم يتم إيجاد الصف في قاعدة البيانات']);
        }else {
            $note->update([
                'deleted_by' => $user_data->name
            ]);
            $note->delete();

            return redirect()->back()->with(['success' => 'تم حذف البيان بنجاح ']);
        }
    }



    public function noteEdit(Request  $request)
    {
        $user_data = Auth::user();
        $note = note::find($request -> id);  // search in given table id only
        if (!$note)
            return redirect()->back()->with(['success' => 'لم يتم إيجاد الصنف في قاعدة بياناتنا ']);

        $note = note::select('id', 'notes','user_name')->find($request -> id);

        return view('Edit_Forms.EditDailyNotes', get_defined_vars());

    }

    public  function noteUpdate(Request $request){
        $user_data = Auth::user();
        $note= note::find($request -> id);
        if (!$note)
            return redirect()->back()->with(['success' => 'لم يتم إيجاد الصنف في قاعدة بياناتنا ']);


        //update data
        $note->update([
            'notes'=> $request->notes,
            'user_name'=> $user_data->name,
           'updated_By'=> $user_data->name
        ]);

        return redirect()->route('DailyNotes.show')->with(['success' => 'تم التحديث بنجاح']);

    }
}









