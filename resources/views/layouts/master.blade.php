
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'Liquid CMS')</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="icon" type="image/png" href="{{ asset(getSetting('site_favicon')) }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.9.55/css/materialdesignicons.min.css"/>
    <link rel="stylesheet" href="{{ asset('vendor/liquid-lite/coreui/css/coreui.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/liquid-lite/css/liquid.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .h-3 {
            height: .75rem;
        }
        .w-3 {
            width: .75rem;
        }
    </style>
    @stack('scopedCss')
</head>
<body class="c-app">
    @include('liquid-lite::includes.sidebar')
    <div class="c-wrapper c-fixed-components">
        @include('liquid-lite::includes.header')
        <div class="c-body">
            <main class="c-main pt-3">
                @yield('content')
            </main>
            @include('liquid-lite::includes.footer')
        </div>
    </div>
    <script>
        var BASE_URL = "{{ url('/') }}";
        var BASE_PATH = "{{ base_path() }}";
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.5.2/perfect-scrollbar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@coreui/coreui-pro@3.4.0/dist/js/coreui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('vendor/liquid-lite/js/liquid.js') }}"></script>
    <script>
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
        }
    </script>
    @if(session('success'))
        <script>
            toastr.success("{{ session('success') }}")
        </script>
    @endif
    @if(session('error'))
        <script>
            toastr.error("{{ session('error') }}")
        </script>
    @endif

    <script>
        $('.select2').each(function () {
            $(this).select2();
        });
    </script>
    @stack('scopedJs')
</body>
</html>
