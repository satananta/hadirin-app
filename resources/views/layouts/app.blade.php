<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    @yield('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link rel='stylesheet' href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' />
    @vite('resources/css/app.css')
</head>

<body class="overflow-y-auto h-screen">
    <div class="flex flex-col justify-between" id="content">
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    @yield('javascript')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function() {
            const myInput = $("input[type='text']");
            const myInputPassword = $("input[type='password']");
            const myFooter = $("footer");

            // Tambahkan event listener untuk mendeteksi fokus pada input
            myInput.on('focus', function() {
                // Jika input mendapat fokus, sembunyikan footer
                myFooter.hide();
            });

            // Tambahkan event listener untuk mendeteksi kehilangan fokus pada input
            myInput.on('blur', function() {
                // Jika input kehilangan fokus, tampilkan kembali footer
                myFooter.show();
            });

            // Tambahkan event listener untuk mendeteksi fokus pada input
            myInputPassword.on('focus', function() {
                // Jika input mendapat fokus, sembunyikan footer
                myFooter.hide();
            });

            // Tambahkan event listener untuk mendeteksi kehilangan fokus pada input
            myInputPassword.on('blur', function() {
                // Jika input kehilangan fokus, tampilkan kembali footer
                myFooter.show();
            });

            $("textarea").on('focus', function() {
                myFooter.hide();
            })

             $("textarea").on('blur', function() {
                myFooter.show();
            })

        })
    </script>
</body>

</html>
