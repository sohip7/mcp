@extends('errors.layout')

@php
  $error_number = 404;
@endphp

@section('title')
صفحة غير موجودة
@endsection

@section('description')
  @php
    $default_error_message = "لم يتم إيجاد الصفحة المطلوبة، إذا كنت تعتقد أن هذا خطأ غريب ; فلا تتردد بالتواصل معنا";
  @endphp
  {!! isset($exception)? ($exception->getMessage()?e($exception->getMessage()):$default_error_message): $default_error_message !!}
@endsection
