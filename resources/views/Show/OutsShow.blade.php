@extends('layouts.app')
@section('title','عرض مخرجات')
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

    <form action="{{ route('OutsShowWithDate.show') }}" method="post">
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
        <input dir="rtl" type="text" id="myInput" onkeyup="myFunction()" placeholder="ابحث عن نوع المخرج..">

    <h1>سجل المخرجات ليوم {{$date}} </h1>
    <table id="dataTable" dir="rtl">
        <thead>
        <tr>
            <th>الرقم</th>
            <th>نوع المخرج</th>
            <th>الصنف</th>
            <th>المبلغ</th>
            <th>مُخرج إلى</th>
            <th>الملاحظات</th>
            <th>وقت التسجيل</th>
            <th>سُجلت بواسطة المستخدم</th>
            <th>وقت التعديل</th>
            <th>عُدلت بواسطة المستخدم</th>
            <th>الاجراءات</th>

        </tr>
        </thead>
        <tbody>

        @foreach($Outs as $out)
            <tr>

                <th scope="row">{{$out -> id}}</th>
                <td>{{ $out-> RecordType}}</td>
                <td>{{ $out-> item}}</td>
                <td>{{$out -> amount}}₪</td>
                <td>{{$out -> beneficiary}}</td>
                <td>{{$out -> notes}}</td>
                <td>{{$out -> created_at}}</td>
                <td>{{$out -> userName}}</td>
                @if(!$out -> updated_at or $out->updated_at == $out->created_at)
                    <td class="text-success fw-bold">غير معدلة</td>
                @else
                    <td class="text-danger fw-bold">{{$out -> updated_at}}</td>

                @endif
                @if(!$out -> updated_By)
                    <td class="text-success fw-bold">غير معدلة</td>
                @else
                    <td class="text-danger fw-bold"> {{$out -> updated_By}} </td>
                @endif

                <!--   <td><img  style="width: 90px; height: 90px;" src=""></td>-->
                @if(!$out->service_number or $out->service_number==3 or $out->service_number==4)
                    <td style="display: flex " >
                        {{--                    @if(in_array($out->RecordType ,['bankquds'] )) hidden="hidden" @endif--}}
                        <a  href="{{route('Outs.edit',$out->id)}}" class="btn btn-success">تعديل الصف</a>
                        <a onclick="confirmDelete('{{route('Outs.Delete',$out->id)}}')" class="btn btn-danger"> حذف الصف</a>
                    </td>
                @else
                    <td>ممنوع الحذف والتعديل من هنا</td>

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
        <h4 class="text-bg-info fw-bold title" id ="nodata"style="display: none"> لا بيانات بهذا الاسم</h4>

        <div class="text-box">
        <p> :إجمالي المخرجات  هو  <p dir="ltr" class="total-sales">  ₪{{ $todayTotal }} </p>
        <a href="{{ route('OutsForm') }}" class="btn btn-primary">إضافة جديد</a>

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
            td = tr[i].getElementsByTagName("td")[1];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                    nodata.style.display = "block";

                }
            }
        }
    }
</script>
</body>
</html>

@endsection
