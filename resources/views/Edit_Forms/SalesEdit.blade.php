@extends('layouts.app')
@section('title','تعديل مبيعات يومية')
@section('content')


<head>
    <title>تعديل بيان مبيعات</title>
    <link rel="stylesheet" href="{{ asset('css/Forms.css') }}">
</head>

<div class="container">
    @if(Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
        @if(Session::has('Error'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('Error') }}
        </div>
    @endif
    <h1>تعديل صنف مبيعات</h1>
        <h6 class="text-danger"> <span style="font-size: 20px" class="required-label"> </span>   تشير إلى أن الحقل مطلوب</h6>

        <form class="form-group" action="{{route('Sales.Update',$Sales->id)}}" method="post">
        @csrf

        <div class="custom-select">
            نوع العملية:
            <select id="RecordType" name="RecordType" onchange="vh(this.value)" >

                <option @if($Sales->RecordType === 'General') selected @endif value="General" >مبيعات عامة </option>
                <option @if($Sales->RecordType === 'OoredooSim') selected @endif value="OoredooSim" >شريحة أوريدوا </option>
                <option @if($Sales->RecordType === 'Ooredoo') selected @endif value="Ooredoo" >رصيد أوريدوا </option>
                <option @if($Sales->RecordType === 'Jawwal') selected @endif value="Jawwal" >رصيد جوال </option>
                <option @if($Sales->RecordType === 'OoredooBills') selected @endif value="OoredooBills">تسديد فاتورة أوريدوا </option>
                <option @if($Sales->RecordType === 'JawwalPay') selected @endif value="JawwalPay">شحن جوال باي</option>
                <option @if($Sales->RecordType === 'Electricity') selected @endif value="Electricity"> رصيد كهرباء</option>
                <!-- يمكنك إضافة المزيد من الخيارات هنا -->
            </select>
        </div>
            @if($Sales->RecordType === 'JawwalPay')
            <div  id class="custom-select">
                <!-- JPATS=> jawwal pay account type-->
                <div  id="JPATS" class="custom-select">
                    نوع الحساب الذي تم الايداع منه:
                    <select id="JPAccountType" name="JPAccountType"  >
                        <option  value="merchant"  >حساب التاجر </option>
                        <option @if($JPCbalance_inout) @if($JPCbalance_inout->jawwalpay_account_type == 'agent') selected @endif @endif value="agent" >حساب الوكيل </option>
                        <!-- يمكنك إضافة المزيد من الخيارات هنا -->
                    </select>
                </div>
            </div>
            @endif
        @if($Sales->RecordType === 'OoredooSim')
                <div class="custom-select">
                    <!-- OSSB=> Ooredoo Sim Seller Branch-->
                    <div  id="OSSB" class="custom-select">
                        البيع في فرع النصر أو الشعف؟:
                        <select id="OSSBselect" name="OSSBselect"  >
                            <option @if( $Sales->sim_place_of_sale=="nasser") selected @endif value="nasser" >فرع النصر</option>
                            <option @if( $Sales->sim_place_of_sale=="shaaf") selected  @endif value="shaaf" >فرع الشعف </option>

                        </select>
                    </div>
                </div>
            @endif


        <div class="form-group">
            <label for="item_name">اسم الصنف:</label>
            <input  placeholder="أدخل اسم الصنف الذي تم بيعه" type="text" id="item_name" name="item_name" required value="{{$Sales->item}}">
        </div>

        <div class="form-group">
            <label for="amount">المبلغ:</label>
            <input  placeholder="أدخل المبلغ" type="number" id="amount" name="amount" required value="{{$Sales->amount}}">
        </div>

        <div class="form-group">
            <label for="quantity">الكمية:</label>
            <input type="number" id="quantity" name="quantity" value="{{$Sales->quantity}}" >
        </div>

            <!-- OSSW => Ooredoo sim Store Warning-->
            <div  style="display: none" id="OSSW" class="alert alert-info" role="alert">
                يرجى وضع رسوم التفعيل 0 إذا تم شحن الشريحة الجديدة في يوم غير هذا اليوم
            </div>


        <div @if(isset($loans_ooredooSim ) and $loans_ooredooSim!=null) style="display: block"  @endif id="actP" class="form-group" style="display: none" >
            <label for="ActivePrice">رسوم التفعيل:</label>
            <input type="number" id="ActivePrice" name="ActivePrice" @if(isset($loans_ooredooSim ) and $loans_ooredooSim!=null) value="{{$Sales->osap}}" @endif value="0" >
        </div>



        <div class="form-group">
            <label for="notes">ملاحظات:</label>
            <textarea  placeholder="اكتب ملاحظات إذا كان هناك أي ارشادات " id="notes" name="notes"  >{{$Sales->notes}}</textarea>
        </div>
        <label class="label" for="UserConfirm">
            هل أنت {{$user_data->name}}
            <input  class="UserCheckBox" id="UserConfirm" type="checkbox" required>
        </label>

        <button type="submit">حفظ</button>
    </form>


</div>
<script>
    function vh(value){
        const item_name=document.getElementById('item_name');
        const OSSW = document.getElementById('OSSW')
        document.getElementById('OSSB').style.display= value=='OoredooSim' ? 'block' : 'none';
        OSSW.style.display= value=='OoredooSim' ? 'block' : 'none';
        if(value=== "Ooredoo"){
            item_name.value = "رصيد أوريدوا";
        }else if(value=== "Jawwal"){
            item_name.value = "رصيد جوال";
        } else if(value=== "OoredooBills"){
            item_name.value = "فواتير أوريدوا";
        } else if(value=== "JawwalPay"){
            item_name.value = "جوال باي";
        } else if(value=== "Electricity"){
            item_name.value = "رصيد كهرباء";
        } else if(value=== "OoredooSim"){
            item_name.value = "شريحة أوريدوا";
            document.getElementById('OSSW').style.display= "block";
            document.getElementById('actP').style.display= "block";

        }


    }
</script>


@endsection
