

<?php
use Barryvdh\DomPDF\PDF
use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('defaultFont', 'Arabic');
$options->set('chroot', realpath(''));
$dompdf = new Dompdf($options);
$Arabic = new ArPHP\I18N\Arabic();

$html = "صهيب";
$p = $Arabic->arIdentify($html);
for ($i = count($p)-1; $i >= 0; $i-=2) {
    $utf8ar = $Arabic->utf8Glyphs(substr($html, $p[$i-1], $p[$i] - $p[$i-1]));
    $html   = substr_replace($html, $utf8ar, $p[$i-1], $p[$i] - $p[$i-1]);
}
$dompdf->loadHtml('
<!DOCTYPE html>
<head><style>
body{
	font-size: 100px;
	text-align:right;
}

</style></head>
<body>
 '.$html.'
</body></html>
	');
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf -> stream("NEXAMPLE", array("Attachment" => false));
?>
{{--<!DOCTYPE html>--}}
{{--<html>--}}
{{--<head>--}}
{{--    <title>صفحة HTML لتحويلها إلى PDF</title>--}}
{{--</head>--}}
{{--<body>--}}
{{--<table style="width:50%; float:left;">--}}
{{--    <tr>--}}
{{--        <th>جدول 1</th>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <td>بيانات جدول 1 - صف 1</td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <td>بيانات جدول 1 - صف 2</td>--}}
{{--    </tr>--}}
{{--</table>--}}

{{--<table style="width:50%; float:right;">--}}
{{--    <tr>--}}
{{--        <th>جدول 2</th>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <td>بيانات جدول 2 - صف 1</td>--}}
{{--    </tr>--}}
{{--    <tr>--}}
{{--        <td>بيانات جدول 2 - صف 2</td>--}}
{{--    </tr>--}}
{{--</table>--}}
{{--</body>--}}
{{--</html>--}}

