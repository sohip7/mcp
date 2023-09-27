@extends('layouts.app')
@section('title','خطأ خاص')

@section('content')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error Page</title>
    <link rel="stylesheet" href="{{ asset('css/ErrorPage.css') }}">
</head>
<body>
<div class="error-container">
    <h1>هناك خطأ </h1>
    <p id="error-message"> {{$Msg}}</p>
    <a href="{{route($SUrl)}}">انقر هنا للذهاب لصفحة الادخال</a>
</div>
<script src="scripts.js"></script>
</body>
@endsection
