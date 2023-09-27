@extends('layouts.app')
@section('title','إضافة دفعة من زيون')
@section('content')

<head>
    <title>إضافة دفعة من زبون</title>
    <link rel="stylesheet" href="{{ asset('css/Forms.css') }}">
</head>
<div class="container">
    @if(Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    <h1>إضافة دفعة من زبون</h1>
        <h6 class="text-danger"> <span style="font-size: 20px" class="required-label"> </span>   تشير إلى أن الحقل مطلوب</h6>

        <form action="{{route('CustomerPay.store')}}" method="post">
        @csrf
        <div class="custom-select">
            طريقة الدفع:
            <select id="PayMethod" name="PayMethod">
                <option value="Cash" selected>كاش </option>
                <option value="bankOfPalestine">بنك فلسطين </option>
                <option value="bankquds">بنك القدس</option>
                <option value="JawwalPay">جوال باي </option>
            </select>
        </div>
        <div class="form-group">
            <label for="CustomerName">اسم الزبون:<span class="required-label"></span></label>
            <input  placeholder="أدخل اسم الزبون الذي دفع" type="text" id="CustomerName" name="CustomerName" required>
        </div>

        <div class="form-group">
            <label for="amount">المبلغ:<span class="required-label"></span></label>
            <input  placeholder="أدخل المبلغ" type="number" id="amount" name="amount" required>
        </div>

        <div class="form-group">
            <label for="notes">ملاحظات:</label>
            <textarea  placeholder="اكتب ملاحظات إذا كان مثلا هناك طلب لتعديل موعد الدفعات الشهرية " id="notes" name="notes"></textarea>
        </div>
{{--            <label  for="UserConfirm">--}}
{{--                هل أنت {{$user_data->name}}--}}
{{--                <span class="required-label"></span>--}}
{{--                <input  class="UserCheckBox" id="UserConfirm" type="checkbox" required>--}}
{{--            </label>--}}

        <button type="submit">إضافة</button>
    </form>
</div>


@endsection
