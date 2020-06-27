<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="{{asset('img/AMEN.jpg')}}" />
    <title>OFFICE COFFEE - Admin</title>

    <!-- Custom fonts for this template-->
    <link href="{{asset('vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{asset('css/sb-admin-2.min.css')}}" rel="stylesheet">
    <style>
        #bg-office {
            background-image: url("{{ asset('img/office.jpg')}}");
        }
    </style>
</head>

<body  id="bg-office">

    @yield('content')
    <!-- Bootstrap core JavaScript-->
    <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{asset('vendor/jquery-easing/jquery.easing.min.js')}}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{asset('js/sb-admin-2.min.js')}}"></script>
    <script src="https://www.gstatic.com/firebasejs/6.3.3/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/6.3.3/firebase-auth.js"></script>
    <script>
        // Paste the config your copied earlier
       var firebaseConfig = {
        apiKey: "AIzaSyD8tLaWIpk-nh43RlF_1So_8CMYY3SAOPI",
        authDomain: "officecoffee-72b42.firebaseapp.com",
        databaseURL: "https://officecoffee-72b42.firebaseio.com",
        projectId: "officecoffee-72b42",
        storageBucket: "officecoffee-72b42.appspot.com",
        messagingSenderId: "488630368524",
        appId: "1:861030597967:android:ea94a3d7677de536a9313a"
      };

      firebase.initializeApp(firebaseConfig);
      firebase.auth().languageCode = 'vi';

      window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container');

      function submitPhoneNumberAuth(){
        var phoneNumber = $('#phoneNumber').val();
        phoneNumber = '+84' + phoneNumber;
        var appVerifier = window.recaptchaVerifier;
        firebase.auth().signInWithPhoneNumber(phoneNumber, appVerifier)
            .then(function (confirmationResult) {
            // SMS sent. Prompt user to type the code from the message, then sign the
            // user in with confirmationResult.confirm(code).
            window.confirmationResult = confirmationResult;
            }).catch(function (error) {
            console.log(error)
            });
            $('#frmLogin').css('display', 'block');
            $('#frmPhone').css('display', 'none');
        }

        function codeverify(){
            var code = $('#veryficationCode').val();
            confirmationResult.confirm(code).then(function (result) {
            // User signed in successfully.
            $('#frmPhone').submit();
            console.log('success')
            }).catch(function (error) {
                console.log(error);
            });
        }
    </script>

@yield('script')
</body>

</html>
