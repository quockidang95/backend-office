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

<body style="padding:0px; width:55mm; margin:auto; font-size:13px;">
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
    <div class="container" style="margin:0px; padding:0px; width:100%; margin:auto;">

        <div id="mydiv" style="page-break-after: always;">
            <h3 style="text-align:center; font-size:20px; margin-bottom:0px; font-family:'Times New Roman', Times, serif; font-weight:bold"></h3>
                </div>


            <div style="text-align:center; margin-bottom:8px;" class="font-weight-bold"> Số Bàn - {{$order->table}} <br />
                <div style="text-align:center; margin-bottom:8px;" class="font-weight-bold"> {{$name}} <br />
            </div>

            <div class="info" style="margin-bottom:8px;">
            <H4 style="font-weight:bold; text-align:center; font-size:13px;">{{Carbon\Carbon::now('Asia/Ho_Chi_Minh')}}</H4>
            </div>

            <div class="product">

                <table class="tb2">
                    <tr>
                        <td>Tên món</td>
                        <td>Size</td>
                        <td>Ghi chú</td>
                        <td align="right" style="padding:5px;">Số lượng</td>
                    </tr>
                    @foreach ($orderItems as $item)
                    <tr>
                        <td><b>{{$item->product_id->name}}</b></td>
                        <td align="right" style="padding:4px;">{{$item->size}}</td>
                        <td align="right" style="padding:4px;">{{$item->recipe}}</td>
                        <td align="right" style="padding:4px;">{{$item->quantity}}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
           <br />
           @if($order->note)
           <div style="text-align:center; margin-bottom:8px;" class="font-weight-bold">
            {{'Lưu ý: ' . $order->note}}
        </div>
        @endif
           <?php
                if($order->payment_method == 1 || $order->is_pay == 1){
                 echo '   <div style="text-align:center; margin-bottom:8px; font-size:14px;" class="font-weight-bold"> ĐÃ THANH TOÁN(' . number_format($order->price) .' VNĐ)<br />';
                }else{
                    echo ' <div style="text-align:center; margin-bottom:8px; font-size:14px;" class="font-weight-bold"> CHƯA THANH TOÁN(' . number_format($order->price) .' VNĐ) <br />';
                }
           ?>
        </div>
    </div>
    <!--
    <script type="text/javascript">
        window.onload = function(){
            window.print()
        }
    </script>
-->
</body>

</html>
