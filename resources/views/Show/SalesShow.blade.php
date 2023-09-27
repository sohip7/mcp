@extends('layouts.app')
@section('title','عرض المبيعات')
@section('content')

<head>
    <title>جدول بيانات مبيعات اليومية</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/Show.css') }}">
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

    <!-- رابط مكتبة jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- رابط مكتبة daterangepicker -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css">
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>

    <form action="{{ route('SalesShow.apply.dates') }}" method="post">
        @csrf
        <div dir="ltr" class="date-range-container">
            <button class="apply-dates-btn" type="submit">تطبيق التواريخ</button>

            <input type="text" id="date" name="date" value="{{$date}}" placeholder="من" readonly>

            <div class="date-label">
                <label for="date-from"> حدد اليوم: </label>
            </div>



        </div>
    </form>

    <script>
        $(function() {
            // تفعيل مربع اختيار التاريخ "من"
            $('#date').daterangepicker({
                singleDatePicker: true,
                locale: {
                    format: 'YYYY-MM-DD',
                    applyLabel: 'تطبيق',
                    cancelLabel: 'إلغاء',
                }
            });

        });
    </script>
        <input dir="rtl" type="text" id="myInput" onkeyup="myFunction()" placeholder="ابحث عن نوع العملية..">

    <h1>سجل المبيعات اليومية {{$date}} </h1>
    <table id="dataTable" dir="rtl">
        <thead>
        <tr>
            <th>الرقم</th>
            <th>نوع العملية</th>
            <th>الصنف</th>
            <th>المبلغ</th>
            <th>الكمية</th>
            <th>إجمالي</th>
            <th>رسوم تفعيل</th>
            <th>الملاحظات</th>
            <th>وقت التسجيل</th>
            <th>سُجلت بواسطة المستخدم</th>
            <th>وقت التعديل</th>
            <th>عُدلت بواسطة المستخدم</th>
            <th>الاجراءات</th>

        </tr>
        </thead>
        <tbody>

        @foreach($sales as $sale)
            <tr>

                <th scope="row">{{$sale -> id}}</th>
                <td>{{ $sale-> RecordType}}</td>
                <td>{{ $sale-> item}}</td>
                <td>{{$sale -> amount}}₪</td>
                <td>{{$sale -> quantity}}</td>
                <td>{{$sale -> total }}₪</td>
                @if($sale -> osap)
                <td>{{$sale -> osap}}₪</td>
                @else
                    <td>0</td>
                @endif
                <td>{{$sale -> notes}}</td>
                <td>{{$sale -> created_at}}</td>
                <td>{{$sale -> user_name}}</td>
                @if(!$sale -> updated_at or $sale -> updated_at==$sale -> created_at )
                    <td class="text-success fw-bold">غير معدلة</td>
                @else
                    <td class="text-danger fw-bold"> {{$sale -> updated_at}} </td>
                @endif
                @if(!$sale -> updated_By)
                    <td class="text-success fw-bold">غير معدلة</td>
                @else
                    <td class="text-danger fw-bold"> {{$sale -> updated_By}} </td>
                @endif

                <!--   <td><img  style="width: 90px; height: 90px;" src=""></td>-->
                @if($sale-> RecordType=='دفعة أولى')
                    <td class="text-secondary bold">ممنوع الحذف والتعديل من هنا</td>
                @else
                    <td style="display: flex " >
                        <a  href="{{route('sales.edit',$sale->id)}}" class="btn btn-success">تعديل الصف</a>
                        <a onclick="confirmDelete('{{route('Sales.Delete',$sale->id)}}')" class="btn btn-danger"> حذف الصف</a>
                    </td>
                @endif

            </tr>
        @endforeach

        <script>
            function confirmDelete(deleteUrl) {
                if (confirm("هل أنت متأكد من رغبتك في حذف الصف؟")) {
                    window.location.href = deleteUrl;
                } else {
                }
            }
        </script>

        </tbody>
    </table>

        <div  class="text-box">
        <p> إجمالي مبيعات اليومية "أصناف" هو  <p dir="ltr" class="total-sales">  ₪{{ $todayTotal }} </p>
        <a href="{{ route('SalesForm') }}" class="btn btn-primary">إضافة جديد</a>
    </div>


</div>
<script>
    function myFunction() {
        // Declare variables
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("dataTable");
        tr = table.getElementsByTagName("tr");

        // Loop through all table rows, and hide those who don't match the search query
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[0];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";

                }
            }
        }
    }
</script>
</body>


@endsection
