@extends('layouts.app')
@section('title','تعديل مُخرج')
@section('content')

<head>
    <title> تعديل مُخرج</title>
    <link rel="stylesheet" href="{{ asset('css/Forms.css') }}">
</head>
<body>
<div class="container">
    @if(Session::has('success'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif
    <h1>تعديل مُخرج</h1>
        <h6 class="text-danger"> <span style="font-size: 20px" class="required-label"> </span>   تشير إلى أن الحقل مطلوب</h6>

        <form action="{{route('Outs.Update',$Outs->id)}}" method="post">
        @csrf

        <div class="custom-select">
            نوع العملية:
            <select id="RecordType" name="RecordType" onchange="vh(this.value)" >

                <option @if($Outs->RecordType === 'Cash') selected @endif value="Cash" >مخرجات عامة نقداً </option>
                <option @if($Outs->RecordType === 'bankOfPalestine') selected @endif value="bankOfPalestine" >بنك فلسطين </option>
                <option @if($Outs->RecordType === 'bankquds') selected @endif value="bankquds" > بنك القدس </option>
                <option @if($Outs->RecordType === 'JawwalPay') selected @endif value="JawwalPay" > جوال باي </option>

            </select>
        </div>

        <div class="form-group">
            <label for="item_name">البيان:<span class="required-label"></span></label>
            <input  placeholder="ما هو الذي تم إخراجه ؟" type="text" id="item_name" name="item_name" required value="{{$Outs->item}}">
        </div>

        <div class="form-group">
            <label for="amount">المبلغ:<span class="required-label"></span></label>
            <input  placeholder="أدخل المبلغ" type="number" id="amount" name="amount" required value="{{$Outs->amount}}">
        </div>
            <div id="01" style="display: @if($Outs->RecordType != 'Cash') block @else none @endif">

                <pre >    هل هذه عملية إخراج مبنية على عملية إدخال "المفترض ان يتم إنقاصها من مدخلات دفعات أو ما شابه ذلك" أم هي عملية إخراج إلى المنصة فقط
             إذا اخترت إخراج مبني على إدخال سابق فإنه سيتم طرح المبلغ من المدخلات
             في حين إذا اخترت عملية إخراج فقط فإنه لن يتم طرح المبلغ من المدخلات</pre>
                <div   class="custom-select">


                    <select id="RecordType2" name="RecordType2"  >
                        <option @if($Outs->service_number == 4) selected @endif  value="OutFromIn" >إخراج مبني على إدخال سابق  </option>
                        <option @if($Outs->service_number == 3) selected @endif value="OutOnly"  >إخراج فقط </option>
                    </select>
                </div>
            </div>
        <div class="form-group">
            <label for="beneficiary">مُخرج إلى:<span style="display: none" id="requiredStar" class="required-label"></span></label>
            <input  placeholder="إلى من تم إخراج الأموال؟ "  type="text" id="beneficiary" name="beneficiary" value="{{$Outs->beneficiary}}" >
        </div>

        <div class="form-group">
            <label for="notes">ملاحظات:</label>
            <textarea  placeholder="اكتب ملاحظات إذا كان هناك أي ارشادات " id="notes" name="notes" >{{$Outs->notes}}</textarea>
        </div>
        <label for="UserConfirm">
            هل أنت {{$user_data->name}}
            <span class="required-label"></span>
            <input class="UserCheckBox" id="UserConfirm" type="checkbox" required>
        </label>

        <button type="submit">حفظ</button>
    </form>
</div>
</body>
<script>
    function vh(value){
        const item_name=document.getElementById('item_name');
        const O1=document.getElementById('01');
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


@endsection
