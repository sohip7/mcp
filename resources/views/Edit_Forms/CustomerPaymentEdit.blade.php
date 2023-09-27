@extends('layouts.app')
@section('title','تعديل دفعة زيون')
@section('content')

<head>
    <title>تعديل دفعات أحد الزبائن</title>
    <link rel="stylesheet" href="{{ asset('css/Forms.css') }}">
</head>

<div class="container">
    @if(Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    <h1>تعديل دفعات أحد الزبائن</h1>
        <h6 class="text-danger"> <span style="font-size: 20px" class="required-label"> </span>   تشير إلى أن الحقل مطلوب</h6>

        <form class="form-group" action="{{route('CustomerPayment.Update',$CusPay->id)}}" method="post">
        @csrf

        <div class="custom-select">
            طريقة الدفع:
            <select id="PayMethod" name="PayMethod">
                <option @if($CusPay->PayMethod === 'Cash') selected @endif value="Cash" >كاش </option>
                <option @if($CusPay->PayMethod === 'bankOfPalestine') selected @endif value="bankOfPalestine">بنك فلسطين </option>
                <option @if($CusPay->PayMethod === 'bankquds') selected @endif value="bankquds">بنك القدس</option>
                <option @if($CusPay->PayMethod === 'JawwalPay') selected @endif value="JawwalPay">جوال باي </option>
            </select>
        </div>
        <div class="form-group">
            <label for="CustomerName">اسم الزبون:<span class="required-label"></span></label>
            <input  placeholder="أدخل اسم الزبون الذي دفع" type="text" id="CustomerName" name="CustomerName" required value="{{$CusPay->CustomerName}}">
        </div>

        <div class="form-group">
            <label for="amount">المبلغ:<span class="required-label"></span></label>
            <input  placeholder="أدخل المبلغ" type="number" id="amount" name="amount" required value="{{$CusPay->amount}}">
        </div>

        <div class="form-group">
            <label for="notes">ملاحظات:</label>
            <textarea  placeholder="اكتب ملاحظات إذا كان مثلا هناك طلب لتعديل موعد الدفعات الشهرية " id="notes" name="notes" >{{$CusPay->notes}}</textarea>
        </div>

        <label class="label" for="UserConfirm">
            هل أنت {{$user_data->name}}
            <span class="required-label"></span>
            <input class="UserCheckBox" id="UserConfirm" type="checkbox" required>
        </label>

        <button type="submit">حفظ</button>
    </form>

</div>



@endsection
