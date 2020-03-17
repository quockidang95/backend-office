<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <title></title>
    <base href="" />
    <link href="view/javascript/bootstrap/css/bootstrap.css" rel="stylesheet" media="all" />
    <script type="text/javascript" src="view/javascript/jquery/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
    <link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
    <link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="all" />
</head>

<body style="padding:0px; width:60mm; margin:auto; font-size:11px;">
    <style>
        .tb {
            display: block;
            width: 100%;
        }

        .tb2 {
            display: block;
            width: 100%;
        }

        tbody {
            display: block;
            width: 100%;
        }

        .tb tr {
            display: block;
            width: 100%;
        }

        .tb tr td {
            display: inline-block;
            width: 49%;
        }
    </style>
    <div class="container" style="margin:0px; padding:0px; width:100%;">

        <div id="mydiv" style="page-break-after: always;">

        <p style="text-align:center; display: block; font-size:20px; margin-bottom:0px; font-family:'Times New Roman', Times, serif; font-weight:bold">OFFICE COFFEE <br />
        
            {{$shift->name_shift}} <br />
            {{$shift->name_admin}} <br />
        </p>
        <p style="text-align:left; display: block; font-size:18px; margin-bottom:20px; font-family:'Times New Roman', Times, serif; font-weight:bold">
            {{'Mở: ' . $shift->shift_open}} <br />
            {{'Đóng ' . $shift->shift_close}} <br />
        </p>

            <div>
                <table class="tb2" style="font-size:20px;">
                    <tr>
                        <td align="left" style="margin-left: 10px;" >Số dư đầu</td>
                        <td align="right">{{ number_format($shift->surplus_box)}}</td>
                    </tr>
                    <tr>
                        <td align="left">Tổng tiền bán</td>
                        <td align="right">{{ number_format($shift->total_revenue)}}</td>
                    </tr>
                    <tr>
                        <td align="left">Tiền mặt(VND)</td>
                        <td align="right">{{ number_format($shift->revenue_cash)}}</td>
                    </tr>

                    <tr>
                        <td align="left">Online(VND)</td>
                        <td align="right">{{ number_format($shift->revenue_online)}}</td>
                    </tr>
                    <tr>
                        <td align="left">Dư cuối ca</td>
                        <td align="right">{{ number_format($shift->end_balance_shift)}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
