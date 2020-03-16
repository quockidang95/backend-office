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

<body style="padding:0px; width:55mm; margin:auto; font-size:11px;">
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

            <h3 style="text-align:center; display: block; font-size:20px; margin-bottom:0px; font-family:'Times New Roman', Times, serif; font-weight:bold">OFFICE COFFEE</h3>
                </div>
            <div style="text-align:center; margin-bottom:8px;"> {{{$order->store_code}}} <br />

            </div>

            <div class="info" style="margin-bottom:8px;">
                <H4 style="font-weight:bold; text-align:center; font-size:13px;">HÓA ĐƠN THANH TOÁN</H4>
            </div>

            <div class="product">

                <table class="tb2">
                    <tr>
                        <td>Tên món</td>
                        <td align="right" style="padding:5px;">Size </td>
                        <td align="right" style="padding:5px;">Giá </td>
                        <td align="right" style="padding:5px;">Sl </td>
                        <td align="right" style="padding:5px;">Ghi chú</td>
                        <td align="right" style="padding:5px;">TT </td>
                    </tr>

                    @foreach ($orderItems as $item)
                    <tr>
                        <td><b>{{$item->product_id->name}}</b></td>
                        <td align="right" style="padding:5px;">{{$item->size}}</td>
                        <td align="right" style="padding:5px;">{{$item->price}}</td>
                        <td align="right" style="padding:5px;">{{$item->quantity}}</td>
                        <td align="right" style="padding:5px;">{{$item->recipe}}</td>
                        <td align="right" style="padding:5px;">{{$item->quantity * $item->price}}</td>

                        </tr>
                    @endforeach

                    <tr>
                        <td colspan="4">Tạm tính: </td>
                        <td>{{number_format($order->total_price)}}</td>
                    </tr>
                   
                    <tr >
                        <td colspan="4">Tông cộng: </td>
                        <td>{{number_format($order->price)}}</td>
                    </tr>
                </table>
            </div>

           <br />



        </div>
        <div style="margin-top:18px;">
            <div style="text-align: center;"><b>GÓP Ý </b></br></><i>Phục vụ - Chất lượng đồ uống</i></br>
                Facebook.com/officecoffeevietnam
                <br />
                <b style="font-size:13px;">0967 123 123</b>
            </div>



        </div>
        <div class="conment" style="text-align:center;">
        </div>
        <b style="display:block; text-align:center;">---o0o---</b>
        <i style=" display:block;font-size:12px; text-align:center;">Hẹn gặp lại Quý khách!</i>


    </div>
</body>

</html>
