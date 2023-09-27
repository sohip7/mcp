@extends('layouts.app')
    @section('title',' ملاحظات اليومية')
    @section('content')
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> عرض الملاحظات</title>
    <link rel="stylesheet" href="{{asset('css/Show.css')}}">
</head>
<body>
<div  class="container">
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

    <form action="{{ route('notesWithDate.show') }}" method="post">
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

    <h1>ملاحظات يوم:{{ $date }}</h1>

<table dir="rtl">
    <thead>
    <tr>
        <th>الرقم</th>
        <th>الملاحظة</th>
        <th>وقت التسجيل</th>
        <th>سُجلت بواسطة المستخدم</th>
        <th>وقت التعديل</th>
        <th>عُدلت بواسطة المستخدم</th>
        <th>الاجراءات</th>
    </tr>
    </thead>
    <tbody>

    @foreach($note as $notes)
        <tr>

            <th scope="row">{{$notes -> id}}</th>
            <td>{{$notes-> notes}}</td>
            <td>{{$notes-> created_at}}</td>
            <td>{{$notes-> user_name}}</td>
            @if(!$notes ->updated_at or $notes->updated_at == $notes->created_at)
                <td class="text-success fw-bold">غير معدلة</td>
            @else
                <td class="text-danger fw-bold"> {{$notes -> updated_at}} </td>
            @endif
            @if(!$notes -> updated_By)
                <td class="text-success fw-bold">غير معدلة</td>
            @else
                <td class="text-danger fw-bold"> {{$notes -> updated_By}} </td>
            @endif
            <td style="display: flex " >
                <a  href="{{route('note.edit',$notes->id)}}" class="btn btn-success">تعديل الملاحظة</a>
                <a onclick="confirmDelete('{{route('note.Delete',$notes->id)}}')" class="btn btn-danger"> حذف الملاحظة</a>
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
    <div  class="text-box">
        <a href="{{ route('DailyNotesForm') }}" class="btn btn-primary">إضافة ملاحظة جديدة</a>
    </div>

<script >

    const notesForm = document.getElementById('notesForm');
    const notesList = document.getElementById('notesList');

    notesForm.addEventListener('submit', function (event) {
        event.preventDefault();

        const noteInput = document.getElementById('noteInput');
        const noteText = noteInput.value.trim();

        if (noteText !== '') {
            createNoteElement(noteText);
            noteInput.value = '';
        }
    });

    function createNoteElement(noteText) {
        const noteElement = document.createElement('div');
        noteElement.classList.add('note');
        noteElement.textContent = noteText;
        notesList.appendChild(noteElement);
    }

</script>
</div>
</body>
</html>
@endsection
