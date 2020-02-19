<!doctype html>
<!-- The DOCTYPE declaration above will set the     -->
<!-- browser's rendering engine into                -->
<!-- "Standards Mode". Replacing this declaration   -->
<!-- with a "Quirks Mode" doctype is not supported. -->

<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Janusz Białobrzewski" />
    <!--                                                               -->
    <!-- Consider inlining CSS to reduce the number of requested files -->
    <!--                                                               -->


    <title>OFFICE COFFEE</title>
    <link rel="shortcut icon" href="{{asset('img/AMEN.jpg')}}" />

    <!--                                           -->
    <!-- This script loads your compiled module.   -->

    <style>
        .qrscanner video {
            max-width: 100%;
            max-height: 75%;
        }

        .row-element-set {
            display: flex;
            flex-direction: column;
        }

        .row-element {
            padding: .2em 0em;
        }

        .row-element-set-QRScanner {
            max-width: 30em;
            display: flex;
            flex-direction: column;
        }

        body {
            display: flex;
            justify-content: center;
        }

        .form-field-caption {
            font-weight: bold;
        }

        .form-field-input {
            width: 100%;
        }
    </style>
    <!-- Bootstrap core CSS -->
    <link href="{{asset('css/sb-admin-2.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/offcanvas.css')}}" rel="stylesheet">
    <link href="{{asset('vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">

</head>

<body>

    <!-- RECOMMENDED if your web app will not function without JavaScript enabled -->
    <noscript>
        <div
            style="width: 22em; position: absolute; left: 50%; margin-left: -11em; color: red; background-color: white; border: 1px solid red; padding: 4px; font-family: sans-serif">
            Your web browser must have JavaScript enabled
            in order for this application to display correctly.
        </div>
    </noscript>
    <div>
        <nav class="navbar navbar-expand-lg fixed-top navbar-white bg-white">
            <a class="navbar-brand mr-auto mr-lg-0" href="#">
                <img src="{{asset('img/AMEN.jpg')}}" width="40" height="40" class="rounded-circle">
            </a>
        </nav>

        <br>
        <div class="row-element">
            <div class="qrscanner" id="scanner">
            </div>
        </div>
        <div class="text-center">
            <h3 class="h3 mb-3 font-weight-normal">Di chuyển camera đến mã QR code để tiếp tục</h3>
        </div>
    </div>
    <script type="text/javascript" src="{{asset('js/jsqrscanner.nocache.js')}}"></script>
    <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
    <script type="text/javascript">
        function onQRCodeScanned(scannedText)
        {
            console.log(scannedText);

            $.ajax({
                type: 'get',
                url: '{{ URL::to('gettable') }}',
                data: {
                    'data': scannedText,
                },
                success: function (data) {
                    window.location.href = data;
                },
                error: function(error){
                    console.log(error);
                }
            });
            $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
            
        }

        function provideVideo()
        {
            var n = navigator;
            if (n.mediaDevices && n.mediaDevices.getUserMedia)
            {
            return n.mediaDevices.getUserMedia({
                video: {
                facingMode: "environment"
                },
                audio: false
            });
            }

            return Promise.reject('Your browser does not support getUserMedia');
        }
        function provideVideoQQ()
        {
            return navigator.mediaDevices.enumerateDevices()
            .then(function(devices) {
                var exCameras = [];
                devices.forEach(function(device) {
                if (device.kind === 'videoinput') {
                exCameras.push(device.deviceId)
                }
            });

                return Promise.resolve(exCameras);
            }).then(function(ids){
                if(ids.length === 0)
                {
                return Promise.reject('Could not find a webcam');
                }

                return navigator.mediaDevices.getUserMedia({
                    video: {
                    'optional': [{
                        'sourceId': ids.length === 1 ? ids[0] : ids[1]//this way QQ browser opens the rear camera
                        }]
                    }
                });
            });
        }

    //this function will be called when JsQRScanner is ready to use
        function JsQRScannerReady()
        {
            //create a new scanner passing to it a callback function that will be invoked when
            //the scanner succesfully scan a QR code
            var jbScanner = new JsQRScanner(onQRCodeScanned);
            //var jbScanner = new JsQRScanner(onQRCodeScanned, provideVideo);
            //reduce the size of analyzed image to increase performance on mobile devices
            jbScanner.setSnapImageMaxSize(300);
            var scannerParentElement = document.getElementById("scanner");
            if(scannerParentElement)
            {
                //append the jbScanner to an existing DOM element
                jbScanner.appendTo(scannerParentElement);
            }
        }
</script>
</body>

</html>
