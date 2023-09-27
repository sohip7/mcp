@extends('layouts.app')
@section('title','تعديل مشتريات')
@section('content')
    <script>
        function vh(value){
            const item_name=document.getElementById('item_name');
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
<head>
    <title> تعديل مشتريات</title>

    <link rel="stylesheet" href="{{ asset('css/Forms.css') }}">
</head>
<body>
<div class="container">
    @if(Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif

    <h1>تعديل مشتريات </h1>
        <h6 class="text-danger"> <span style="font-size: 20px" class="required-label"> </span>   تشير إلى أن الحقل مطلوب</h6>

        <form action="{{route('Purchases.Update',$Purchases->id)}}" method="post">
        @csrf

        <div class="custom-select">
            نوع العملية:
            <select id="RecordType" name="RecordType" onchange="vh(this.value)">
                <option @if($Purchases->RecordType === 'General') selected @endif value="General">مشتريات عامة </option>
                <option @if($Purchases->RecordType === 'Ooredoo') selected @endif value="Ooredoo">رصيد أوريدوا </option>
                <option @if($Purchases->RecordType === 'Jawwal') selected @endif value="Jawwal">رصيد جوال </option>
                <option @if($Purchases->RecordType === 'OoredooBills') selected @endif value="OoredooBills"> فواتير أوريدوا </option>
                <option @if($Purchases->RecordType === 'JawwalPay') selected @endif value="JawwalPay"> جوال باي</option>
                <option @if($Purchases->RecordType === 'Electricity') selected @endif value="Electricity"> كهرباء</option>
            </select>
        </div>


        <div class="form-group">
            <label for="item_name">البيان:<span class="required-label"></span></label>
            <input  placeholder="ما هو الذي تم شراءه ؟" type="text" id="item_name" name="item_name" required value="{{$Purchases->item}}">
        </div>

        <div class="form-group">
            <label for="amount">المبلغ:<span class="required-label"></span></label>
            <input  placeholder="أدخل المبلغ" type="number" id="amount" name="amount" required value="{{$Purchases->amount}}" >
        </div>

        <div class="form-group">
            <label for="DealerName">اسم التاجر:<span class="required-label"></span></label>
            <input  placeholder="ما هو اسم التاجر او الشخص الذي تم الشراء منها ؟" type="text" id="DealerName" name="DealerName" required value="{{$Purchases->SellerName}}">
        </div>

        <div class="form-group">
            <label for="notes">ملاحظات:</label>
            <textarea  placeholder="اكتب ملاحظات إذا كان هناك أي ارشادات " type="text" id="notes" name="notes" >{{$Purchases->notes}}</textarea>
        </div>


        <label for="UserConfirm">
            هل أنت {{$user_data->name}}
            <span class="required-label"></span>
            <input class="UserCheckBox" id="UserConfirm" type="checkbox" required>
        </label>

        <button type="submit">حفظ</button>
    </form>
</div>
</body>


@endsection
