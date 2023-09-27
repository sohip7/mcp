@extends('layouts.app')
@section('title','إضافة دفعة إلى تاجر')
@section('content')
<head>
    <title>دفع إلى تاجر</title>
    <link rel="stylesheet" href="{{ asset('css/Forms.css') }}">
</head>
<main>
<div class="container">
    @if(Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    <h1>دفع إلى تاجر</h1>
        <h6 class="text-danger"> <span style="font-size: 20px" class="required-label"> </span>   تشير إلى أن الحقل مطلوب</h6>

        <form action="{{route('payToMerchant.store')}}" method="post">
        @csrf

        <div class="custom-select">
            طريقة الدفع:
            <select id="PayMethod" name="PayMethod">
                <option value="Cash" selected>كاش</option>
                <option value="bankOfPalestine">بنك فلسطين </option>
                <option value="bankquds">بنك القدس </option>
                <option value="JawwalPay">جوال باي</option>
                <option value="under">من الخزنة</option>
                <option value="check">شيك </option>
            </select>


        </div>

        <div class="form-group">
            <label for="amount">المبلغ:<span class="required-label"></span></label>
            <input  placeholder="أدخل المبلغ" type="number" id="amount" name="amount" required>
        </div>


        <div class="form-group">
            <label for="merchant_name">اسم التاجر:<span class="required-label"></span></label>
            <input  placeholder="أدخل اسم التاجر" type="text" id="merchant_name" name="merchant_name" required>
        </div>

        <div class="form-group">
            <label for="notes">ملاحظات:</label>
            <textarea  placeholder="اكتب ملاحظات إذا كان هناك أي ارشادات " id="notes" name="notes"></textarea>
        </div>

{{--        <label for="UserConfirm">--}}
{{--            هل أنت {{$user_data->name}}--}}
{{--            <span class="required-label"></span>--}}
{{--            <input class="UserCheckBox" id="UserConfirm" type="checkbox" required>--}}
{{--        </label>--}}

        <button type="submit">إضافة</button>


    </form>

</div>
</main>


@endsection
