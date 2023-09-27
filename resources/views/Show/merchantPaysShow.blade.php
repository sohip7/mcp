@extends('layouts.app')
@section('title','عرض دفعات إلى التجار')
@section('content')
    <!DOCTYPE html>
<html>
<head>
    <title>جدول دفعات غير كاش للتجار </title>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/Show.css') }}">
</head>
<body>

<div class="container">

     @if(Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
      @endif

        @if(Session::has('info'))
        <div class="alert alert-info" role="alert">
            {{ Session::get('info') }}
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
{{--        {{ route('MerchantPaysWithDate.show') }}--}}
    <form action="{{ route('MerchantPaysWithDate.show') }}" method="post">
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

    <h1>سجل دفعات للتجار "مش كاش" ليوم {{$date}} </h1>
    <table id="dataTable" dir="rtl">
        <thead>
        <tr>
            <th>اسم التاجر</th>
            <th>المبلغ</th>
            <th>طريقة الدفع</th>
            <th>وقت التسجيل</th>
            <th>سُجلت بواسطة المستخدم</th>
            <th>وقت التحديث</th>
            <th>حُدثت بواسطة المستخدم</th>
            <th>الاجراءات</th>

        </tr>
        </thead>
        <tbody>

        @foreach($MerchPaysOuts as $MerchPaysOut)

                <tr>

                <td>{{ $MerchPaysOut-> beneficiary}}</td>
                <td>{{ $MerchPaysOut-> amount}}₪</td>
                <td>{{$MerchPaysOut -> RecordType}}</td>
                <td>{{$MerchPaysOut -> created_at}}</td>
                <td>{{$MerchPaysOut -> userName}}</td>
                    @if(!$MerchPaysOut -> updated_at)
                        <td class="text-success fw-bold">غير معدلة</td>
                    @else
                        <td class="text-danger fw-bold">{{$MerchPaysOut -> updated_at}}</td>
                    @endif
                    @if(!$MerchPaysOut -> updated_By)
                        <td class="text-success fw-bold">غير معدلة</td>
                    @else
                        <td class="text-danger fw-bold" >{{$MerchPaysOut -> updated_By}}</td>
                    @endif

                <!--   <td><img  style="width: 90px; height: 90px;" src=""></td>-->

                <td >
{{--                    @if(in_array($out->RecordType ,['bankquds'] )) hidden="hidden" @endif--}}
                    <a  href="{{route('PayMerchant.edit',$MerchPaysOut->id)}}" class="btn btn-success">تعديل الصف</a>
                   <a onclick="confirmDelete('{{route('PayMerchant.Delete',$MerchPaysOut->id)}}')" class="btn btn-danger"> حذف الصف</a>
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

        <div class="text-box">
       <p> :إجمالي المخرجات  هو  <p dir="ltr" class="total-sales"> {{$todayTotal}} ₪ </p>
       <a href="{{ route('payToMerchantForm') }}" class="btn btn-primary">إضافة جديد</a>

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
            td = tr[i].getElementsByTagName("td")[2];
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
