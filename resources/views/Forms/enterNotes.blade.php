@extends('layouts.app')
@section('title','إضافة ملاحظة يومية')
@section('content')

<head>
    <title>إضافة ملاحظة جديدة</title>
    <link rel="stylesheet" href="{{ asset('css/Forms.css') }}">
</head>
<body>
<div class="container">
    @if(Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    <h1>إضافة ملاحظة جديدة</h1>
    <form action="{{route('notes.store')}}" method="post">
        @csrf
        <div class="form-group">
            <label for="notes" >الملاحظة:<span  id="RL" class="required-label"></span></label>
            <textarea  placeholder="اكتب ملاحظات إذا كان هناك أي ارشادات " id="notes" name="notes" required></textarea>
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
