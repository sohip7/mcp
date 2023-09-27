@extends('layouts.app')
@section('title','تحديث رقم الإصدار')
@section('content')
    <head>
        <link rel="stylesheet" type="text/css" href="{{ asset('css/changeVersion.css') }}">
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
        <h1>إدخال رقم الإصدار</h1>
        <form action="{{route('NewVersion.apply')}}" method="post">
            @csrf
            <label for="version">رقم الإصدار:</label>
            <input type="text" id="versionNumber" name="versionNumber" placeholder="أدخل رقم الإصدار">
            <button type="submit">حفظ</button>
        </form>
    </div>
    </body>
@endsection
