@extends('layouts.app')
@section('title','إضافة مُخرج')
@section('content')

<head>
    <title> إضافة مُخرج جديد</title>
    <link rel="stylesheet" href="{{ asset('css/Forms.css') }}">
</head>
<body>
<div class="container">
    @if(Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    <h1>إضافة مُخرج جديد</h1>
        <h6 class="text-danger"> <span style="font-size: 20px" class="required-label"> </span>   تشير إلى أن الحقل مطلوب</h6>

        <form action="{{route('Outs.store')}}" method="post">
        @csrf
        <div class="custom-select">
            نوع العملية:
            <select id="RecordType" name="RecordType" onchange="vh(this.value)" >

                <option  value="Cash" >مخرجات عامة نقداً </option>
                <option  value="bankOfPalestine" >بنك فلسطين </option>
                <option value="bankquds" > بنك القدس </option>
                <option value="JawwalPay" > جوال باي </option>

            </select>
        </div>
        <div class="form-group">
            <label for="item_name">البيان:<span class="required-label"></span></label>
            <input  placeholder="ما هو الذي تم إخراجه ؟" type="text" id="item_name" name="item_name" required>
        </div>



        <div class="form-group">
            <label for="amount">المبلغ:<span class="required-label"></span></label>
            <input  placeholder="أدخل المبلغ" type="number" id="amount" name="amount" required>
        </div>


            <div id="O1" style="display: none">

                <pre >    هل هذه عملية إخراج مبنية على عملية إدخال "المفترض ان يتم إنقاصها من مدخلات دفعات أو ما شابه ذلك" أم هي عملية إخراج إلى المنصة فقط
             إذا اخترت إخراج مبني على إدخال سابق فإنه سيتم طرح المبلغ من المدخلات
             في حين إذا اخترت عملية إخراج فقط فإنه لن يتم طرح المبلغ من المدخلات</pre>
            <div   class="custom-select">


                <select id="RecordType2" name="RecordType2"  >
                    <option  value="OutFromIn" >إخراج مبني على إدخال سابق  </option>
                    <option  value="OutOnly" selected >إخراج فقط </option>
                </select>
            </div>
            </div>




        <div class="form-group">
            <label for="beneficiary">مُخرج إلى:<span style="display: none" id="requiredStar" class="required-label"></span></label>
            <input  placeholder="إلى من تم إخراج الأموال؟ "  type="text" id="beneficiary" name="beneficiary" >
        </div>

        <div class="form-group">
            <label for="notes">ملاحظات:</label>
            <textarea  placeholder="اكتب ملاحظات إذا كان هناك أي ارشادات " id="notes" name="notes"></textarea>
        </div>
{{--        <label for="UserConfirm">--}}
{{--            هل أنت {{$user_data->name}}--}}
{{--            <span class="required-label"></span>--}}
{{--            <input class="UserCheckBox" id="UserConfirm" type="checkbox" required>--}}
{{--        </label>--}}

        <button type="submit">إضافة</button>
    </form>
        <script>
            function vh(value){
                const item_name=document.getElementById('item_name');
                const O1=document.getElementById('O1');
                const requiredStar=document.getElementById('requiredStar');
                if(value=== "bankOfPalestine"){
                    item_name.value = "بنك فلسطين";
                    requiredStar.style.display='inline';
                    O1.style.display='block';
                }else if(value=== "bankquds"){
                    item_name.value = "بنك القدس";
                    requiredStar.style.display='inline';
                    O1.style.display='block';
                }  else if(value=== "JawwalPay"){
                    item_name.value = "جوال باي";
                    requiredStar.style.display='inline';
                    O1.style.display='block';

                }
                else if(value=== "Cash"){
                    requiredStar.style.display='none';
                    O1.style.display='none';
                }


            }
        </script>
</div>
</body>


@endsection
