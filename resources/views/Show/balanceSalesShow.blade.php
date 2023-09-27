@extends('layouts.app')
@section('title','عرض مبيعات الأرصدة')
@section('content')

<head>

    <title>جدول بيانات</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/Show.css') }}">
</head>
<body>
<div class="container">

    <!-- رابط مكتبة jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- رابط مكتبة daterangepicker -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.css">
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker@3.1.0/daterangepicker.min.js"></script>

    <form action="{{ route('balanceSalesShowWithDate.show') }}" method="post">
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


    <h1>بيانات العمليات على أرصدة المنصات خلال يوم <br> {{ $date }}</h1>

    <input dir="rtl" type="text" id="myInput" onkeyup="myFunction()" placeholder="ابحث عن منصة..">
    <table  id="dataTable" dir="rtl">
        <thead>
        <tr>
            <th>الرقم</th>
            <th>نوع العملية</th>
            <th>اسم المنصة</th>
            <th>المبلغ</th>
            <th>الملاحظات</th>
            <th>وقت التسجيل</th>
            <th>سجلت بواسطة:</th>
            <th>اخر تحديث </th>
            <th>تم التحديث بواسطة: </th>
        </tr>
        </thead>
        <tbody>
        @foreach($balancesales as $balancesale)
            <tr>

                <th scope="row">{{$balancesale -> id}}</th>
                <td>{{$balancesale-> record_type}}</td>
                <td>{{$balancesale-> platform_name}}</td>
                <td>{{$balancesale -> amount}}</td>
                <td>{{$balancesale -> notes}}</td>
                <td>{{$balancesale -> created_at}}</td>
                <td>{{$balancesale -> created_by}}</td>
                @if(!$balancesale -> updated_at or $balancesale->updated_at == $balancesale->created_at)
                    <td class="text-success fw-bold">غير معدلة</td>
                @else
                    <td class="text-danger fw-bold"> {{$balancesale -> updated_at}} </td>
                @endif
                @if(!$balancesale -> updated_By)
                    <td class="text-success fw-bold">غير معدلة</td>
                @else
                    <td class="text-danger fw-bold"> {{$balancesale -> updated_By}} </td>
                @endif


            </tr>
        @endforeach

        </tbody>
    </table>
    <h4 class="text-bg-info fw-bold title" id ="nodata"style="display: none"> لا بيانات بهذا الاسم</h4>

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
</div>

</body>


@endsection
