@extends('layouts.app')
{{--route('SalesForm'--}}
@section('title','الصفحة الرئيسية')
@section('content')

<script src="{{asset('js/home.js')}}"></script>
    <div  class="greeting">

        <script>
            const currentHour = new Date().getHours();

            if (currentHour >= 0 && currentHour < 12) {
                document.write("صباح الخير ");
            } else {
                document.write("مساء الخير ");
            }
        </script>
        <div  style="display: inline" class="text-success">
            {{  explode(' ', $user_data->name)[0] }}
        </div>

    </div>
>

        <link rel="stylesheet" type="text/css" href="{{asset('css/Home.css')}}">

                    <div class="d-flex justify-content-center align-items-center" >
                        <div class="my-auto">
                            <button style="display: none" id="showTable" onclick="showDailyOutsTable()" >عرض بيانات الترحيل اليومية</button>
                        </div>
                    </div>

    <div id="tableContainer" style="display: none;">
        <button id="close-button" onclick="closeDailyOutsTable()" >إخفاء الجدول</button>

        <table id="dailyOutTable">
            <table>
                <tr>
                    <th>#</th>
                    <th>التاريخ</th>
                    <th>مبلغ الترحيل "دولار"</th>
                    <th>مبلغ الترحيل "شيكل"</th>
                    <th>مبلغ الترحيل "دينار"</th>
                    <th>مبلغ الترحيل إلى الصندوق</th>
                    <th>سجلت بواسطة</th>
                    <th>وقت التسجيل</th>
                    <th>عُدلت بواسطة</th>
                    <th>وقت التعديل</th>
                </tr>
                <tr>
                    <td class="h1 text-danger " colspan="5">قيد التطوير</td>
                </tr>
{{--                @foreach()--}}
{{--                    <tr>--}}
{{--                        <td>{{ $data['column1'] }}</td>--}}
{{--                        <td>{{ $data['column2'] }}</td>--}}
{{--                        <td>{{ $data['column3'] }}</td>--}}
{{--                    </tr>--}}
{{--                @endforeach--}}
            </table>
        </table>
    </div>

            <!-- النموذج العائم -->

            <div class="floating-form-container" id="floating-form">
                <h2> إدخال بيانات ترحيل اليومية</h2>
                <div class="alert alert-danger" role="alert">
                    فعالة، لكن غير مكتملة !!
                </div>
             <form action="{{route('EndDaily.store')}}" method="post" >
                 @csrf
                    <div>
                        <label for="amount_usd"> المبلغ "دولار":</label>
                        <input type="number" id="amount_usd" name="amount_usd">
                    </div>
                    <div>
                        <div>
                        <label for="amount_jod"> المبلغ "دينار":</label>
                        <input type="number" id="amount_jod" name="amount_jod">
                    </div>
                    <div>
                        <label for="amount_ils"> المبلغ "شيكل":</label>
                        <input type="number" id="amount_ils" name="amount_ils">
                    </div>
                        <div>
                        <label for="amount_daily"> المبلغ المرحل إلى اليومية الجديدة:</label>
                        <input type="number" id="amount_daily" name="amount_daily">
                    </div>

                    <button type="submit">إرسال</button>

                </div>

            </form>
                <button onclick="hideForm()">إخفاء النموذج</button>
            </div>








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
        @if(Session::has('v'))
            <div class="alert alert-success" role="alert">
                {{ Session::get('v') }}
            </div>
        @endif
        <h1>قائمة العمليات</h1>
        <div style=" align-content: center" class="button-list">
            <button id="show-form-button"  class="btn" onclick="showForm()">إدخال مبالغ ترحيل اليومية</button>
            <a href="{{route('SalesForm')}}" class="btn">إدخال المبيعات اليومية </a>
            <a href="{{route('OutsForm')}}" class="btn">إدخال مًخرج جديد</a>
            <a href="{{route('CustomersPaymentForm')}}" class="btn">إدخال دفعة من زبون </a>
            <a href="{{route('LendForm')}}" class="btn">إدخال دَين جديد</a>
            <a href="{{route('DealersBuyForm')}}" class="btn">إدخال مشتريات </a>
            <a  href="{{route('PlatformBalanceForm')}}" class="btn">إدخال أرصدة محطات الشحن </a>
            <a  href="{{route('payToMerchantForm')}}" class="btn">إدخال دفعة إلكترونية إلى تاجر  </a>
            <a  href="{{route('DailyNotesForm')}}" class="btn">إدخال ملاحظة عامة لليومية  </a>



        </div>
    </div>





@endsection
