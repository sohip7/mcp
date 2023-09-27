@extends('layouts.app')
@section('title','عرض دفعات الزبائن')
@section('content')
    <!DOCTYPE html>
<html>
<head>
    <title>جدول بيانات</title>
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

        <form action="{{ route('CustomerPayWithDate.show') }}" method="post">
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
            <input dir="rtl" type="text" id="myInput" onkeyup="myFunction()" placeholder="ابحث عن طريقة دفع">

        <h1>سجل دفعات من الزبائن ليوم {{$date}}</h1>
        <table id="dataTable" dir="rtl">
            <thead>
            <tr>
                <th>الرقم</th>
                <th>اسم الشخص</th>
                <th>طريقة الدفع</th>
                <th>المبلغ</th>
                <th>الملاحظات</th>
                <th>وقت التسجيل</th>
                <th>سُجلت بواسطة المستخدم</th>
                <th> وقت التحديث</th>
                <th>تم التحديث بواسطة</th>
                <th> الاجراءات</th>
            </tr>
            </thead>
            <tbody>
            @foreach($CusPays as $CusPay)
                <tr>

                    <th scope="row">{{$CusPay -> id}}</th>
                    <td>{{ $CusPay-> CustomerName}}</td>
                    <td>{{ $CusPay-> PayMethod}}</td>
                    <td>{{$CusPay -> amount}}₪</td>
                    <td>{{$CusPay -> notes}}</td>
                    <td>{{$CusPay -> created_at}}</td>
                    <td>{{$CusPay -> userName}}</td>
                    @if(!$CusPay -> updated_at)
                        <td class="text-success fw-bold">غير معدلة</td>
                    @else
                        <td class="text-danger fw-bold">{{$CusPay -> updated_at}}</td>
                    @endif
                    @if(!$CusPay -> updated_By)
                        <td class="text-success fw-bold">غير معدلة</td>
                    @else
                        <td class="text-danger fw-bold"> {{$CusPay -> updated_By}} </td>
                    @endif


                    <td  >
                        <a href="{{route('CustomerPayment.edit',$CusPay->id)}}" class="btn btn-success">تعديل الصف</a>
                        <a onclick="confirmDelete('{{route('CustomerPayment.Delete',$CusPay->id)}}')" class="btn btn-danger"> حذف الصف</a>
                    </td>

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
            <h4 class="text-bg-info fw-bold title" id ="nodata"style="display: none"> لا بيانات بهذا الاسم</h4>

            <div class="text-box">
            <p> "إجمالي دفعات الزبائن هو  <p dir="ltr" class="total-sales">  ₪{{ $todayTotal }} </p>
            <a href="{{ route('CustomersPaymentForm') }}" class="btn btn-primary">إضافة جديد</a>

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
            td = tr[i].getElementsByTagName("td")[1];
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
</html>

@endsection
