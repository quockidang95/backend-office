<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <base href="{{ asset('') }}">
    <title>OFFICE COFFEE</title>
    <link rel="shortcut icon" href="{{asset('img/AMEN.jpg')}}" />
    <!-- Custom fonts for this template-->
    <link href="{{asset('vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">


    <!-- Custom styles for this template-->
    <link href="{{asset('css/sb-admin-2.min.css')}}" rel="stylesheet">
    @yield('css')


    <!-- Custom scripts for all pages-->
    <script src="{{asset('js/sb-admin-2.min.js')}}"></script>
    <!-- Toast notification-->
    
   
    <style>
        #drop {
            height: 100px;
            overflow-y: scroll;
        }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        <?php
            $store_code = Auth::user()->store_code;
            echo '<input id="store_code" type="text" hidden value="'. $store_code .'">'
        ?>

        <!-- Sidebar -->
        <ul style="background-color: #32312f" class="navbar-nav  sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" style="background-color: white"
                href="{{URL::to('/order/index')}}">

                    <img src="{{asset('img/office_logo.png')}}" width="90%">

            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">


            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Quản trị viên</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Danh mục:</h6>
                        <a class="collapse-item" href="{{URL::to('/admin/index')}}">Quản lí tài khoảng</a>
                        <a class="collapse-item" href="{{route('store.index')}}">Cửa Hàng</a>
                        <a class="collapse-item" href="{{route('category.index')}}">Danh mục Sản phẩm</a>
                        <a class="collapse-item" href="{{route('product.index')}}">Sản Phẩm</a>
                        <a class="collapse-item" href="{{route('recipe.index')}}">Công thức</a>
                        <a class="collapse-item" href="{{route('customer.index')}}">Khách Hàng</a>
                        <a class="collapse-item" href="{{route('feedback.index')}}">Phản Hồi</a>
                        <a class="collapse-item" href="{{route('setting.index')}}">Cài đặt</a>
                    </div>
                </div>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">
            <!-- Nav Item - Utilities Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Thống kê</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Tùy chọn thống kê:</h6>
                        <a class="collapse-item" href="{{ route('doanh.thu.theo.ngay') }}">Doanh thutrong ngày</a>
                        <a class="collapse-item" href="{{ route('doanh.thu.theo.thang') }}">Doanh thu trong tháng</a>
                        
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <li class="nav-item">
                <a class="nav-link collapsed" href="{{route('customer.index')}}" aria-expanded="true"
                    aria-controls="collapseUtilities">
                    <i class="fas fa-user-friends fa-fw"></i>
                    <span class="font-weight-bold">Khách hàng</span>
                </a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider">

            <li class="nav-item">
                <a class="nav-link collapsed" href="{{route('order.byday')}}" aria-expanded="true"
                    aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-clipboard-list"></i>
                    <span class="font-weight-bold">Đơn hàng</span>
                </a>
            </li>
            <hr class="sidebar-divider">

            <li class="nav-item">
                <a class="nav-link collapsed" href="{{route('order.revenue')}}" aria-expanded="true"
                    aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-list"></i>
                    <span class="font-weight-bold">Doanh thu trong ngày</span>
                </a>
            </li>

            <hr class="sidebar-divider">

            <li class="nav-item">
                <a class="nav-link collapsed" href="{{route('rechage')}}" aria-expanded="true"
                    aria-controls="collapseUtilities">
                    <i class="fas fa-dollar-sign fa-fw"></i>
                    <span class="font-weight-bold">Tổng tiền nạp trong ngày</span>
                </a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <li class="nav-item">
                <a class="nav-link collapsed" href="{{route('notification.sendall')}}" aria-expanded="true"
                    aria-controls="collapseUtilities">
                    <i class="fas fa-bell"></i>
                    <span class="font-weight-bold">Gửi thông báo đến khách hàng</span>
                </a>
            </li>
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>


                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">



                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1 dropdown-notifications" data-spy="scroll"
                            data-target=".dropdown-item" data-offset="50">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i data-count="0" class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter notif-count ">0</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in "
                                aria-labelledby="alertsDropdown" id="drop">


                            </div>
                        </li>


                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span
                                    class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name . ' - ' . Auth::user()->store_code }}</span>
                                <img class="img-profile rounded-circle"
                                    src="https://source.unsplash.com/QAB-WJcbgJk/60x60">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                              document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>

                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    @yield('content')

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>@author : Dang Quoc Ki  </span> <br>
                        <span>@company : Kingbao Media </span> <br>
                        <span>Support: 0348704901 or fb: fb.com/quocki.dang</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>



    {{-- <script src="AdminLTE/bower_components/ckeditor/ckeditor.js"></script> --}}

    <!-- Bootstrap core JavaScript-->
    <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{asset('vendor/jquery-easing/jquery.easing.min.js')}}"></script>
    <script src="{{asset('js/sb-admin-2.min.js')}}"></script>
    <script src="{{asset('ckeditor/ckeditor.js')}}"></script>
    <script src="{{asset('ckfinder/ckfinder.js')}}"></script>
    <script src="{{asset('vendor/toastr-master/toastr.js')}}"></script>
    <link href="{{asset('vendor/toastr-master/build/toastr.css')}}" rel="stylesheet">
    <script>
        $(function(){
           var message = $('.success').val();
           if(message){
           toastr.success(message, 'Hệ thống thông báo: ', {timeOut: 5000});
           }
           });
           $(function(){
           var message = $('.error').val();
           if(message){
               toastr.error(message, 'Hệ thống thông báo: ', {timeOut: 5000})
           }
           });
    </script>
    <script src="https://js.pusher.com/4.3/pusher.min.js"></script>
    <script type="text/javascript">
        var notificationsWrapper   = $('.dropdown-notifications');
    var notificationsToggle    = notificationsWrapper.find('a[data-toggle]');
    var notificationsCountElem = notificationsToggle.find('i[data-count]');
    var notificationsCount     = parseInt(notificationsCountElem.data('count'));
    var notifications          = notificationsWrapper.find('div.dropdown-list');
    var store_code = $('#store_code').val();
    
    // Enable pusher logging - don't include this in production
     Pusher.logToConsole = true;

    var pusher = new Pusher('{{env('PUSHER_APP_KEY')}}', {
        cluster: 'ap1',
        encrypted: true
    });

    // Subscribe to the channel we specified in our Laravel Event
    var channel = pusher.subscribe('Notify');

    // Bind a function to a Event (the full Laravel class)
    channel.bind('send-message', function(data) {
        if(data.store_code === store_code){
            var existingNotifications = notifications.html();
        var avatar = Math.floor(Math.random() * (71 - 20 + 1)) + 20;
        var newNotificationHtml = `
           <a class="dropdown-item d-flex align-items-center" href="{{env('APP_URL')}}/order/details/` + data.id + `">
            <audio src="{{asset('audio.mp3')}}" autoplay></audio>
                                    <div class="mr-3">
                                      <div class="icon-circle bg-primary">
                                        <i class="fas fa-file-alt text-white"></i>
                                      </div>
                                    </div>
                                    <div>

                                      <span class="font-weight-bold">Một khách hàng  vừa order tại bàn số ` + data.table + `</span>
                                    </div>

                                  </a>
        `;
        notifications.html(newNotificationHtml + existingNotifications);

        notificationsCount += 1;
        notificationsCountElem.attr('data-count', notificationsCount);
        notificationsWrapper.find('.notif-count').text(notificationsCount);
        notificationsWrapper.show();
        }

    });
    </script>
    @yield('script')
</body>

</html>
