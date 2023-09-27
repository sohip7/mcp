@extends('layouts.app')
@section('title','ملخص اليومية')

@section('content')

    <head>
        <title>ملخص اليومية</title>
        <link rel="stylesheet" type="text/css" href="{{ asset('css/SummaryPage.css') }}">
    </head>
<body>
    <h1 style="direction: rtl; text-align: center;">بيانات اليومية النهائية ليوم </h1>
    <h5 class="text-info" dir="rtl" style="margin: 50px; text-align: center">⚠️: تعني لا توجد قيمة بسبب عدم إدخالك لأرصدة المنصات الافتتاحي أو النهائي، توجه للادخال ومن ثم حدث الصفحة</h5>
<div style="text-align: center">
        <a  onclick="showAlert(event)" href="{{route('DailyData.print')}}" class="btn btn-info" type="button">طباعة اليومية</a>
{{--    onclick="showAlert(event)"--}}
    <div style="display: none" class="floating-alert" id="soonlabel">
        قريباً، ميزة طباعة اليومية ♥
    </div>
</div>
    <script>
        function showAlert(event) {
            event.preventDefault();
            const soonlabel = document.getElementById("soonlabel");
            soonlabel.style.display = "block";
            setTimeout(function () {
                soonlabel.style.opacity = "0";
                setTimeout(function () {
                    soonlabel.style.display = "none";
                    soonlabel.style.opacity = "1";
                }, 500); // Delay for transition
            }, 6000);
        }
    </script>


    <div class="container">

    <div class="card text-white bg-secondary mb-3" style="max-width: 18rem;">
        <div class="card-header">رصيد أوريدوا النهائي  </div>
        <div class="card-body">
            @if(isset($OoredooEnd) and $OoredooEnd)
                <h5 class="card-title text-info">{{$OoredooEnd}} شيكل</h5>
            @else
                <h5 class="card-title text-danger">⚠️</h5>
            @endif
            <p class="card-text"> التفاصيل: <br>
                 <span> الرصيد الافتتاحي : {{ $openbalance->OoredooBalance }} <br> </span>
                 <span>الرصيد المشترى : {{ $TotalOoredooBalanceinDealer }} <br> </span>
                <span> الرصيد المباع نقداً: {{ $TotalOoredooBalanceCashOut }}<br> </span>
             <span>  ديون أرصدة أوريدوا:    {{ $TotalOoredooBalanceLoans }} </span>
               <span> @if(isset($TotalOoredooBalanceCashSale) and $TotalOoredooBalanceCashSale)
                    <br>  الرصيد النهائي المدخل:    {{ $closebalance->OoredooBalance }}
                    <br>  رصيد صندوق البيع الفوري :   {{ $TotalOoredooBalanceCashSale }}
                @else
                    <br>  الرصيد النهائي المدخل :    <p >⚠️</p>
                    <br>  رصيد صندوق البيع الفوري : <p >⚠️</p>
            @endif
                    </span>
                   <br>
                   <span class="text-bg-info"> تفاصيل إضافية قد تهمك </span>
                <br>
                <span> مدخلات بيع الشرائح : {{ $totalOoredooSimActiveCashIn }}<br> </span>


                   <span>  تكلفة تفعيل شرائح أوريدوا:    {{ $SIMactivationFees }}</span>


            </p>
        </div>
    </div>
    <div class="card text-white bg-secondary mb-3" style="max-width: 18rem;">
        <div class="card-header">رصيد جوال النهائي</div>
        <div class="card-body">
            @if(isset($JawwalEnd) and $JawwalEnd)
                <h5 class="card-title text-info">{{$JawwalEnd}} شيكل</h5>
            @else
                <h5 class="card-title text-danger">⚠️</h5>
            @endif
            <p class="card-text"> التفاصيل: <br>
                الرصيد الافتتاحي : {{ $openbalance->JawwalBalance }} <br>
                الرصيد المشترى : {{ $TotalJawwalBalanceinDealer }} <br>
                الرصيد المباع نقداً: {{ $TotalJawwalBalanceCashOut }}
                <br>  ديون أرصدة جوال:    {{ $TotalJawwalBalanceLoans }}
                <span> @if(isset($TotalJawwalBalanceCashSale) and $TotalJawwalBalanceCashSale)
                        <br>  الرصيد النهائي المدخل:    {{ $closebalance->JawwalBalance }}
                        <br>  رصيد صندوق البيع الفوري :   {{ $TotalJawwalBalanceCashSale }}
                    @else
                        <br>  الرصيد النهائي المدخل :    <p >⚠️</p>
                        <br>  رصيد صندوق البيع الفوري : <p >⚠️</p>
                    @endif
                    </span>
                <br>
            </p>
        </div>
    </div>
    <div class="card text-white bg-secondary mb-3" style="max-width: 18rem;">
        <div class="card-header">رصبد جوال باي النهائي</div>
        <div class="card-body">
            @if(isset($JawwalPayEnd))
                <h5 class="card-title text-info">{{$JawwalPayEnd}} شيكل</h5>
            @else
                <h5 class="card-title text-danger">⚠️</h5>
            @endif
            <p class="card-text"> التفاصيل: <br>
                الرصيد الافتتاحي : {{ $openbalance->JawwalPayBalance }} <br>
                الرصيد المشترى   : {{ $TotalJawwalPayBalanceinDealer }} <br>
                الرصيد المباع نقداً: {{ $TotalJawwalPayBalanceCashOut }}
                <br>  ديون أرصدة جوال باي:    {{ $TotalJawwalPayBalanceLoans }}
                <span> @if(isset($TotalJawwalPayBalanceCashSale))
                <br>  الرصيد النهائي المدخل:    {{ $closebalance->JawwalPayBalance }}
                <br>  رصيد صندوق البيع الفوري :   {{ $TotalJawwalPayBalanceCashSale }}
                @else
                    <br>  الرصيد النهائي المدخل :    <p >⚠️</p>
            <br>  رصيد صندوق البيع الفوري : <p >⚠️</p>
            @endif
            </span>
                <br>
                <span class="text-bg-info"> تفاصيل إضافية قد تهمك </span>
                <br>
                <span>  إجمالي دفعات لتجار تمت عبر جوال باي : {{ $JawwalPayMerchantPay }} </span>
                <span>  إجمالي دفعات زبائن تمت عبر جوال باي : {{ $JawwalPayCustPay }} </span>


            </p>
        </div>
    </div>
    <div class="card text-white bg-secondary mb-3" style="max-width: 18rem;">
        <div class="card-header">رصيد فواتير أوريدوا النهائي</div>
        <div class="card-body">
            @if(isset($OoredooBillsEnd))
                <h5 class="card-title text-info">{{$OoredooBillsEnd}} شيكل</h5>
            @else
                <h5 class="card-title text-danger">⚠️</h5>
            @endif
            <p class="card-text"> التفاصيل: <br>
                الرصيد الافتتاحي : {{ $openbalance->OoredooBillsBalance }} <br>
                الرصيد المشترى : {{ $TotalOoredooBillsBalanceinDealer  }} <br>
                الرصيد المباع نقداً: {{ $TotalOoredooBillsBalanceCashOut }}
                <br>  ديون فواتير أوريدوا:    {{ $TotalOoredooBillsBalanceLoans }}
                <span> @if(isset($TotalOoredooBillsBalanceCashSale))
                        <br>  الرصيد النهائي المدخل:    {{ $closebalance->OoredooBillsBalance }}
                        <br>  رصيد صندوق البيع الفوري :   {{ $TotalOoredooBillsBalanceCashSale }}
                    @else
                        <br>  الرصيد النهائي المدخل :    <p >⚠️</p>
                        <br>  رصيد صندوق البيع الفوري : <p >⚠️</p>
                    @endif
            </span>
        </div>
    </div>
    <div class="card text-white bg-secondary mb-3" style="max-width: 18rem;">
        <div class="card-header">رصيد الكهرباء النهائي</div>
        <div class="card-body">
            @if(isset($ElectricityEnd))
                <h5 class="card-title text-info">{{$ElectricityEnd}} شيكل</h5>
            @else
                <h5 class="card-title text-danger">⚠️</h5>
            @endif
            <p class="card-text"> التفاصيل: <br>
                الرصيد الافتتاحي : {{ $openbalance->ElectricityBalance }} <br>
                الرصيد المشترى : {{ $TotalElectricityBalanceinDealer }} <br>
                الرصيد المباع نقداً: {{ $TotalElectricityBalanceCashOut }}
                <br>  ديون أرصدة الكهرباء:    {{ $TotalElectricityBalanceLoans }}
                <span> @if(isset($TotalElectricityBalanceCashSale))
                        <br>  الرصيد النهائي المدخل:    {{ $closebalance->ElectricityBalance }}
                        <br>  رصيد صندوق البيع الفوري :   {{ ceil($TotalElectricityBalanceCashSale) }}
                    @else
                        <br>  الرصيد النهائي المدخل :    <p >⚠️</p>
                        <br>  رصيد صندوق البيع الفوري : <p >⚠️</p>
                    @endif
            </span>
            </p>
        </div>
    </div>
    <div class="card text-white bg-secondary mb-3" style="max-width: 18rem;">
        <div class="card-header">رصيد بنك فلسطين النهائي</div>
        <div class="card-body">
            @if(isset($BopEnd))
                <h5 class="card-title text-info">{{$BopEnd}} شيكل</h5>
            @else
                <h5 class="card-title text-danger">⚠️</h5>
            @endif            <p class="card-text">التفاصيل: <br>
                الرصيد الافتتاحي : {{ $openbalance->BankOfPalestineBalance }}<br>
                        الرصيد الداخل : {{ $BopTotalIn }}<br>
                الرصيد الخارج: {{ $BopTotalOut }}
            </p>
        </div>
    </div>
    <div class="card text-white bg-secondary mb-3" style="max-width: 18rem;">
        <div class="card-header">رصيد بنك القدس</div>
        <div class="card-body ">
            @if(isset($BankQudsEnd))
                <h5 class="card-title text-info">{{$BankQudsEnd}} شيكل</h5>
            @else
                <h5 class="card-title text-danger">⚠️</h5>
            @endif            <p class="card-text">التفاصيل: <br>
                الرصيد الافتتاحي : {{ $openbalance->BankAlQudsBalance }}<br>
                الرصيد الداخل : {{ $BankQudsTotalIn }}<br>
                الرصيد المُخرج: {{ $BankQudsTotalOut }}
            </p>
        </div>
    </div>

<!-- General Info-->
        <div class="card text-white bg-secondary mb-3" style="max-width: 18rem;">
            <div class="card-header">إجمالي دفعات من الزبائن</div>
            <div class="card-body">
               <h5  class="card-title text-info ">{{$TotalCustPay}} شيكل </h5>
                <p class="card-text"> التفاصيل: <br>

                </p>
            </div>
        </div>
        <div class="card text-white bg-secondary mb-3" style="max-width: 18rem;">
            <div class="card-header">إجمالي مبيعات اليوم</div>
            <div class="card-body">
            <h5 class="card-title text-info">{{$TotalSales}} شيكل </h5>
                <p class="card-text">التفاصيل: <br>

                </p>
            </div>
        </div>
        <div class="card text-white bg-secondary mb-3" style="max-width: 18rem;">
            <div class="card-header">إجمالي مشتريات اليوم</div>
            <div class="card-body ">
                <h5 class="card-title text-info">{{$TotalBuy}} شيكل </h5>
                <p class="card-text">التفاصيل: <br>

                </p>
            </div>
        </div>

        </div>

    <hr >
    <div class="centered-content">
    <div style="text-align: center ">
    <div class="card text-white bg-secondary mb-3" style=" margin-left: 40%; direction: rtl; max-width: 18rem;">
        <div class="card-header">المُدخل</div>
        <div class="card-body">
            <h5 class="card-title text-warning">{{$dailyEntireTotal}} شيكل </h5>
            <p class="card-text">هنا يظهر لك إجمالي المدخلات</p>
        </div>
        <h1>-</h1>
    </div>

    <div class="card text-white bg-secondary mb-3" style=" margin-left: 40%; direction: rtl; max-width: 18rem;">
        <div class="card-header">الخارج</div>
        <div class="card-body">
            <h5 class="card-title text-warning">{{$OutsTotalGeneral+$OutsTotalCustomerPay+$OutsTotalCustomerPay2}} شيكل </h5>
            <p class="card-text">هنا يظهر لك إجمالي المخرجات</p>
        </div>
        <h1>=</h1>
    </div>


    <div  class="card text-white bg-primary mb-3" style=" margin-left: 40%; direction: rtl; max-width: 18rem;">
        <div class="card-header">الرصيد النهائي</div>
        <div class="card-body">
            <h5 class="card-title ">{{$finalBalance}} شيكل </h5>
            <p class="card-text">هنا يظهر لك الرصيد بعد طرح الخارج من المُدخل</p>
        </div>
    </div>
    </div>
    </div>
</body>

@endsection
