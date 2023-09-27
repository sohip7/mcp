@extends('layouts.app')
@section('title','تعليمات حساب ملخص اليومية')

    @section('content')
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="{{asset('css/instractionPage.css')}}">
            <title>Professional Guidelines</title>
        </head>
        <body>
        <header>
            <h1>صفحة الارشادات</h1>
        </header>
        <main>
            <section class="guide">
                <h2>صفحة ملخص اليومية</h2>
                <p>صفحة اليومية عبارة عن صفحة تعرض لك بيانات الارصدة الخاصة بالمحطات بشكل تفصيلي بالإضافة إلى إجمالي المدخلات
                والمشستريات وإجمالي الديون بالإضافة إلى إجمالي رصيد الصندوق</p>
                <ol>
                    <li>Sign up for an account.</li>
                    <li>Complete your profile information.</li>
                    <li>Explore the available resources and tools.</li>
                </ol>
            </section>
            <section class="guide">
                <h2>Best Practices</h2>
                <p>Follow these best practices to excel in your professional journey:</p>
                <ul>
                    <li>Stay updated with the latest industry trends.</li>
                    <li>Network with other professionals in your field.</li>
                    <li>Continuously improve your skills through learning and training.</li>
                </ul>
            </section>
        </main>
        <footer>
            <p> جميع الحقوق محفوظة - MC-System {{now()->year}} &copy; </p>
        </footer>
        </body>
@endsection
