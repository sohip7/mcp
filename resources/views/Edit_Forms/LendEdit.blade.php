@extends('layouts.app')
@section('title','تعديل دَين')
@section('content')

<head>
    <title>تعديل دَين</title>
    <link rel="stylesheet" href="{{ asset('css/Forms.css') }}">
</head>
@if(Session::has('info'))
    <div class="alert alert-info" role="alert">
        {{ Session::get('info') }}
    </div>
@endif
<body onload="OnLVH()">

<div class="container">
    @if(Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif

        <h1>تعديل دَين</h1>
        <h6 class="text-danger"> <span style="font-size: 20px" class="required-label"> </span>   تشير إلى أن الحقل مطلوب</h6>

        <form action="{{route('Loans.Update',$loans->id)}}" method="post">
        @csrf
        <div class="custom-select">
            نوع العملية:
            <select @if($loans->RecordType === 'OoredooSim') disabled  @endif onchange="vh(this.value)" id="RecordType" name="RecordType" >

                <option @if($loans->RecordType === 'General') selected @endif value="General" >مبيعات عامة </option>
                <option @if($loans->RecordType === 'Ooredoo') selected @endif value="Ooredoo" >رصيد أوريدوا </option>
                <option @if($loans->RecordType === 'Jawwal') selected @endif value="Jawwal" >رصيد جوال </option>
                <option @if($loans->RecordType === 'OoredooBills') selected @endif value="OoredooBills">تسديد فاتورة أوريدوا </option>
                <option @if($loans->RecordType === 'JawwalPay') selected @endif value="JawwalPay">شحن جوال باي</option>
                <option @if($loans->RecordType === 'Electricity') selected @endif value="Electricity"> رصيد كهرباء</option>
                <option @if($loans->RecordType === 'installment_transaction') selected @endif value="installment_transaction"> معاملة تقسيط</option>
            </select>
        </div>
        @if($loans->RecordType === 'OoredooSim')
        <input hidden id="RecordType" name="RecordType" value="OoredooSim">
        @endif

        <div class="form-group">
            <label for="item_name">اسم الصنف:<span class="required-label"></span></label>
            <input  @if($loans->RecordType === 'OoredooSim')  readonly @endif type="text" id="item_name" name="item_name" required  value="{{$loans->item}}">
        </div>

        <div class="form-group">
            <label for="amount">المبلغ:<span class="required-label"></span></label>
            <input  placeholder="أدخل المبلغ" type="number" id="amount" name="amount" required value="{{$loans->amount}}">
        </div>

        <div class="form-group">
            <label for="quantity">الكمية:<span class="required-label"></span></label>
            <input type="number" placeholder="ادخل الكمية" id="quantity" name="quantity" required value="{{$loans->quantity}}">
        </div>


        <div  style="display: none" @if($loans->RecordType === 'OoredooSim')  hidden @endif  id="FirstPayInput" class="form-group">
            <label  for="FirstPay">الدفعة الاولى:<span class="required-label"></span></label>
            <input placeholder="أدخل قيمة الدفعة الاولى.." type="number" id="FirstPay" name="FirstPay" value="{{$loans->FirstPay}}">
        </div>


        <div @if($loans->RecordType === 'OoredooSim')  hidden @endif class="form-group">
            <label for="debtor_name">اسم المدين:<span class="required-label"></span></label>
            <input  placeholder="أدخل الشخص المدين" type="text" id="debtor_name" name="debtor_name" required value="{{$loans->debtorName}}">
        </div>

        <div class="form-group">
            <label for="notes">ملاحظات:</label>
            <textarea  placeholder="اكتب ملاحظات إذا كان هناك أي ارشادات " id="notes" name="notes" >{{$loans->notes}}</textarea>
        </div>
        <label for="UserConfirm">
            هل أنت {{$user_data->name}}
            <span class="required-label"></span>
            <input class="UserCheckBox" id="UserConfirm" type="checkbox" required>
        </label>

        <button type="submit">حفظ</button>
    </form>
        <script>
            function OnLVH(value){
                var valueFromRequest = "{{ $loans->RecordType }}";
                const elementToHide=document.getElementById('FirstPayInput');
                elementToHide.style.display= value == 'installment_transaction' ? "block" : "none";
                elementToHide.style.display= valueFromRequest == 'installment_transaction' ? "block" : "none";

            }
        </script>
        <script>
            function vh(value){
                const elementToHide=document.getElementById('FirstPayInput');
                elementToHide.style.display= value == 'installment_transaction' ? "block" : "none";
            }
        </script>
</div>
</body>


@endsection
