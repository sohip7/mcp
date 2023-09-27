@extends('layouts.app')
@section('title','إضافة دَين')
@section('content')

<head>
    <title>إضافة دَين جديد</title>
    <link rel="stylesheet" href="{{ asset('css/Forms.css') }}">
</head>
<body>
<div class="container">
    @if(Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    <h1>إضافة دَين جديد</h1>
        <h6 class="text-danger"> <span style="font-size: 20px" class="required-label"> </span>   تشير إلى أن الحقل مطلوب</h6>
    <form action="{{route('Lends.store')}}" method="post">
        @csrf

        <div class="custom-select">
            نوع العملية:
            <select id="RecordType" name="RecordType" onchange="vh(this.value)">
                <option value="General" selected>مبيعات عامة </option>
                <option value="Ooredoo">رصيد أوريدوا </option>
                <option value="Jawwal">رصيد جوال </option>
                <option value="OoredooBills">تسديد فاتورة أوريدوا </option>
                <option value="JawwalPay">شحن جوال باي</option>
                <option value="Electricity"> رصيد كهرباء</option>
                <option value="installment_transaction"> معاملة تقسيط</option>
            </select>



        </div>
        <script>
            function vh(value){
                const actP=document.getElementById('FirstPayInput');
                const item_name=document.getElementById('item_name');
                actP.style.display= value == 'installment_transaction' ? "block" : "none";
                document.getElementById('item_name').value= value=='installment_transaction' ? 'معاملة تقسيط' : '';
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
                }


            }
        </script>


        <div class="form-group">
            <label for="item_name">اسم الصنف:<span class="required-label"></span></label>
            <input  placeholder="أدخل اسم الصنف الذي تم إدانته" type="text" id="item_name" name="item_name" required>
        </div>

        <div class="form-group">
            <label for="amount">المبلغ:<span class="required-label"></span></label>
            <input  placeholder="أدخل المبلغ" type="number" id="amount" name="amount" required>
        </div>

        <div  id="quantity" class="form-group">
            <label  for="quantity">الكمية: <span class="required-label"></span></label>
            <input type="number" id="quantity" name="quantity" value="1">
        </div>

        <div style="display: none"  id="FirstPayInput" class="form-group">
            <label  for="FirstPay">الدفعة الاولى:<span class="required-label"></span></label>
            <input type="number" id="FirstPay" name="FirstPay" placeholder="أدخل الدفعة الأولى ..">
        </div>

        <div class="form-group">
            <label for="debtor_name"  >اسم المدين:<span class="required-label"></span></label>

            <input  placeholder="أدخل الشخص المدين" type="text" id="debtor_name" name="debtor_name" required>
        </div>

        <div class="form-group">
            <label for="notes">ملاحظات:</label>
            <textarea  placeholder="اكتب ملاحظات إذا كان هناك أي ارشادات " id="notes" name="notes"></textarea>
        </div>

{{--        <label for="UserConfirm">--}}
{{--            هل أنت {{$user_data->name}}--}}
{{--            <span class="required-label"></span>--}}
{{--            <input  class="UserCheckBox" id="UserConfirm" type="checkbox" required>--}}
{{--        </label>--}}

        <button type="submit">إضافة</button>


    </form>

</div>
</body>


@endsection
