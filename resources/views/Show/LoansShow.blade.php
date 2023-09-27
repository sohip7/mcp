@extends('layouts.app')
@section('title','عرض الديون')
@section('content')
    <!DOCTYPE html>
<html>
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

    <form action="{{ route('LoansShowWithDate.show') }}" method="post">
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

    <h1>سجل الديون ليوم {{$date}} </h1>
    <table id="dataTable" dir="rtl">
        <thead>
        <tr>
            <th>الرقم</th>
            <th>نوع العملية</th>
            <th>الصنف</th>
            <th>المبلغ</th>
            <th>الكمية</th>
            <th>إجمالي</th>
            <th>دفعة أولى</th>
            <th>الملاحظات</th>
            <th>اسم الدائن</th>
            <th>وقت التسجيل</th>
            <th>سُجلت بواسطة المستخدم</th>
            <th>وقت التحديث</th>
            <th>عُدلت بواسطة المستخدم</th>
            <th>الاجراءات</th>

        </tr>
        </thead>
        <tbody>

        @foreach($Loans as $Loan)
            <tr>

                <th scope="row">{{$Loan -> id}}</th>
                <td>{{ $Loan-> RecordType}}</td>
                <td>{{ $Loan-> item}}</td>
                <td>{{$Loan -> amount}}₪</td>
                <td>{{$Loan -> quantity}}</td>
                <td>{{$Loan -> total}}₪</td>
                <td>{{$Loan -> FirstPay}}₪</td>
                <td>{{$Loan -> notes}}</td>
                <td>{{$Loan -> debtorName}}</td>
                <td>{{$Loan -> created_at}}</td>
                <td>{{$Loan -> UserName}}</td>
                @if(!$Loan -> updated_at)
                    <td class="text-success fw-bold">غير معدلة</td>
                @else
                    <td class="text-danger fw-bold">{{$Loan -> updated_at}}</td>
                @endif
            @if(!$Loan -> updated_By)
                    <td class="text-success fw-bold">غير معدلة</td>
                @else
                    <td class="text-danger fw-bold"> {{$Loan -> updated_By}} </td>
                @endif
                @if($Loan -> RecordType == 'OoredooSim')
                    <td class="text-secondary fw-bold">ممنوع الحذف او التعديل من هنا</td>
                @else
                    <td style="display: flex " >
                        <a  href="{{route('Loans.edit',$Loan->id)}}" class="btn btn-success">تعديل الصف</a>
                        <a onclick="confirmDelete('{{route('Loans.Delete',$Loan->id)}}')" class="btn btn-danger"> حذف الصف</a>
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

        <div class="text-box">
        <p> : إجمالي ديون اليوم  <p dir="ltr" class="total-sales">  ₪{{ $todayTotal }} </p>
        <a href="{{ route('LendForm') }}" class="btn btn-primary">إضافة جديد</a>

    </div>
</div>
<script>
    function myFunction() {
        // Declare variables
        var input, filter, table, tr, td, i, txtValue, nodata;
        input = document.getElementById("myInput");
        nodata = document.getElementById("nodata");
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
</html>

@endsection
