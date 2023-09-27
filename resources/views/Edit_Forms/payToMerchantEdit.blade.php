@extends('layouts.app')
@section('title','تعديل دفعة إلى تاجر')
@section('content')

<head>
    <title>تعديل دفعة إلى تاجر</title>
    <link rel="stylesheet" href="{{ asset('css/Forms.css') }}">
</head>
<body>
<div class="container">
    @if(Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    <h1>تعديل دفعة إلى تاجر</h1>
        <h6 class="text-danger"> <span style="font-size: 20px" class="required-label"> </span>   تشير إلى أن الحقل مطلوب</h6>

        {{--        {{route('PayMerchant.Update')}}--}}
    <form action="{{route('PayMerchant.Update',$outs->id)}}" method="post">
        @csrf

        <div class="custom-select">
            طريقة الدفع:
            <select id="PayMethod" name="PayMethod">
                <option @if($outs->RecordType === 'Cash') selected @endif value="Cash" >كاش</option>
                <option @if($outs->RecordType === 'bankOfPalestine') selected @endif  value="bankOfPalestine">بنك فلسطين </option>
                <option @if($outs->RecordType === 'bankquds') selected @endif value="bankquds">بنك القدس </option>
                <option @if($outs->RecordType === 'JawwalPay') selected @endif  value="JawwalPay">جوال باي</option>
            </select>


        </div>

        <div class="form-group">
            <label for="amount">المبلغ:<span class="required-label"></span></label>
            <input  placeholder="أدخل المبلغ" type="number" id="amount" name="amount" value="{{$outs->amount}}" required>
        </div>


        <div class="form-group">
            <label for="merchant_name">اسم التاجر:<span class="required-label"></span></label>
            <input  placeholder="أدخل اسم التاجر" type="text" id="merchant_name" name="merchant_name" value="{{$outs->beneficiary}}" required>
        </div>

        <div class="form-group">
            <label for="notes">ملاحظات:</label>
            <textarea  placeholder="اكتب ملاحظات إذا كان هناك أي ارشادات " id="notes" name="notes" ></textarea>
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
