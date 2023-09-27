@extends('layouts.app')
@section('title','إضافة أرصدة المنصات')
@section('content')

<head>
    <title>إضافةأرصدة المحطات</title>
    <link rel="stylesheet" href="{{ asset('css/Forms.css') }}">
</head>
<body>
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
    <h1>إدخال أرصدة محطات الشحن</h1>
        <h6 class="text-danger"> <span style="font-size: 20px" class="required-label"> </span>   تشير إلى أن الحقل مطلوب</h6>

        <form action="{{route('PlatformBalance.store')}}" method="post">
        @csrf

        <div class="form-group custom-select">
            <label for="BalanceType"> نوع الإدخال</label>
            <select id="BalanceType" name="BalanceType" required>
                <option value="افتتاحي">افتتاحي</option>
                <option  value="نهائي">نهائي</option>
            </select>
        </div>

        <div class="form-group">
            <label for="OoredooBalance">رصيد أوريدوا:</label>
            <input  placeholder="أدخل رصيد المحطة الحالي" type="number" id="OoredooBalance" name="OoredooBalance" >
        </div>

        <div class="form-group">
            <label for="JawwalBalance">رصيد جوال:</label>
            <input  placeholder="أدخل رصيد المحطة الحالي" type="number" id="JawwalBalance" name="JawwalBalance" >
        </div>

        <div class="form-group">
            <label for="JawwalPayBalance">رصيد جوال باي:</label>
            <input  placeholder="أدخل رصيد المحطة الحالي" type="number" id="JawwalPayBalance" name="JawwalPayBalance" >
        </div>

        <div class="form-group">
            <label for="ElectricityBalance">رصيد الكهرباء:</label>
            <input  placeholder="أدخل إجمالي رصيد الديكسين والعادي" type="number" id="ElectricityBalance" name="ElectricityBalance" >
        </div>

        <div class="form-group">
            <label for="OoredooBillsBalance">رصيد أوريدوا الفواتير:</label>
            <input  placeholder="أدخل رصيد المحطة الحالي" type="number" id="OoredooBillsBalance" name="OoredooBillsBalance" >
        </div>

        <div class="form-group">
            <label for="BankOfPalestineBalance">رصيد بنك فلسطين:</label>
            <input  placeholder="أدخل رصيد البنك الحالي" type="number" id="BankOfPalestineBalance" name="BankOfPalestineBalance" >
        </div>

        <div class="form-group">
            <label for="BankAlQudsBalance">رصيد بنك القدس:</label>
            <input  placeholder="أدخل رصيد البنك الحالي" type="number" id="BankAlQudsBalance" name="BankAlQudsBalance" >
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
</body>


@endsection
