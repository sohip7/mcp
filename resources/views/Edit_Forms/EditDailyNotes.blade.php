@extends('layouts.app')
@section('title','تعديل ملاحظة يومية')
@section('content')

<head>
    <title>تعديل ملاحظة</title>
    <link rel="stylesheet" href="{{ asset('css/Forms.css') }}">
</head>
<body>
<div class="container">
    @if(Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    <h1>تعديل ملاحظة</h1>
        <h6 class="text-danger"> <span style="font-size: 20px" class="required-label"> </span>   تشير إلى أن الحقل مطلوب</h6>

        <form action="{{route('note.update',$note->id)}}" method="post">
        @csrf
        <div class="form-group">
            <label for="notes">الملاحظة:<span class="required-label"></span></label>
            <textarea  placeholder="اكتب ملاحظات إذا كان هناك أي ارشادات " id="notes" name="notes"> {{$note->notes}}</textarea>
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
