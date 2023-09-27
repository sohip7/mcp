@extends('layouts.app')
@section('title','إضافة مبيعات')
@section('content')

<head>
    <title>إضافة بيع صنف جديد</title>
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

    <h1>إضافة بيع صنف جديد</h1>
        <h6 class="text-danger"> <span style="font-size: 20px" class="required-label"> </span>   تشير إلى أن الحقل مطلوب</h6>

        <form class="form-group" action="{{route('sales.store')}}"  method="post">
        @csrf
            <div   id="Instruction1" class="alert alert-info" role="alert">
                هام جداً جداً،إذا كنت تنوي تسجيل رصيد مباع في يوم غير هذا اليوم، فقم بتسجيله مع مراعاة تحديد نوع العملية إلى "مبيعات عامة" مع الابتعاد تماماً عن تحديد العملية إلى أرصدة
            </div>
        <div class="custom-select">
            نوع العملية:
            <select id="RecordType" name="RecordType" onchange="vh(this.value)" >

                <option value="General" selected>مبيعات عامة </option>
                <option value="OoredooSim" >شريحة أوريدوا </option>
                <option value="Ooredoo" >رصيد أوريدوا </option>
                <option value="Jawwal" >رصيد جوال </option>
                <option value="OoredooBills">تسديد فاتورة أوريدوا </option>
                <option value="JawwalPay">شحن جوال باي</option>
                <option value="Electricity"> رصيد كهرباء</option>
                <!-- يمكنك إضافة المزيد من الخيارات هنا -->
            </select>
        </div>
            <div class="custom-select">
            <!-- JPATS=> jawwal pay account type-->
            <div style="display: none" id="JPATS" class="custom-select">
                نوع الحساب الذي تم الايداع منه:
                <select id="JPAccountType" name="JPAccountType"  >

                    <option value="agent" selected>حساب الوكيل </option>
                    <option value="merchant" >حساب التاجر </option>

                </select>
            </div>
            </div>

            <div class="custom-select">
            <!-- OSSB=> Ooredoo Sim Seller Branch-->
            <div style="display: none" id="OSSB" class="custom-select">
                البيع في فرع النصر أو الشعف؟:
                <select id="OSSBselect" name="OSSBselect"  >

                    <option value="nasser" selected>فرع النصر</option>
                    <option value="shaaf" >فرع الشعف </option>

                </select>
            </div>
            </div>



        <div  class="form-group">
            <label for="item_name">اسم الصنف:<span class="required-label"></span></label>
            <input  placeholder="أدخل اسم الصنف الذي تم بيعه" type="text" id="item_name" name="item_name" required>
        </div>

        <div class="form-group">
            <label for="amount">المبلغ:<span class="required-label"></span></label>
            <input  placeholder="أدخل المبلغ" onchange="vi()" type="number" id="amount" name="amount" required>
        </div>

        <div id="test" class="form-group">
            <label for="quantity">الكمية:<span class="required-label"></span></label>
            <input type="number" id="quantity" name="quantity" value="1">
        </div>
            <!-- OSSW => Ooredoo sim Store Warning-->
            <div  style="display: none" id="OSSW" class="alert alert-info" role="alert">
                يرجى وضع رسوم التفعيل 0 إذا تم شحن الشريحة الجديدة في يوم غير هذا اليوم
            </div>
        <div id="actP" class="form-group" style="display: none" >
            <label for="ActivePrice">رسوم التفعيل:<span  id="RL" class="required-label"></span></label>
            <input type="number" id="ActivePrice" name="ActivePrice" value="0">
        </div>

        <div class="form-group">
            <label for="notes">ملاحظات:</label>
            <textarea  placeholder="اكتب ملاحظات إذا كان هناك أي ارشادات " id="notes" name="notes"></textarea>
        </div>
{{--        <label  for="UserConfirm">--}}
{{--            هل أنت {{$user_data->name}}--}}
{{--            <span class="required-label"></span>--}}
{{--            <input class="UserCheckBox" id="UserConfirm" type="checkbox" required>--}}
{{--        </label>--}}

        <button type="submit" >إضافة</button>
    </form>
{{--        <a href="{{ route('sales.show') }}" class="btn btn-primary">عرض حركات تسجيل الأصناف اليوم</a>--}}

</div>
<script>


    function vi() {
        const inputValue = document.getElementById("amount").value;
        const errorMessage = document.getElementById("error-message");

        if (inputValue < 0) {
            showErrorMessage('انتبه! انت تدخل قيمة سالبة')
        } else {
            errorMessage.textContent = "";
        }
    }


    function showErrorMessage(message) {
        const errorMessage = document.getElementById("error-message");
        errorMessage.textContent = message;
        errorMessage.style.display = "block";

        setTimeout(() => {
            errorMessage.style.display = "none";
        }, 7000); // Hide after 3 seconds (adjust as needed)
    }

    function vh(value){
        const actP=document.getElementById('actP');
        const OSSW = document.getElementById('OSSW')
        actP.style.display= value == 'OoredooSim' ? "block" : "none";
        document.getElementById('item_name').value= value=='OoredooSim' ? 'تفعيل شريحة' : '';
        OSSW.style.display= value=='OoredooSim' ? 'block' : 'none';
        document.getElementById('JPATS').style.display= value=='JawwalPay' ? 'block' : 'none';
        document.getElementById('OSSB').style.display= value=='OoredooSim' ? 'block' : 'none';
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

        }


    }

</script>



@endsection
