<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice | Print</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{!! asset('/assets/img/favicon/favicon.png')!!}" />
    <link rel="stylesheet" href="{!! asset('/assets/vendor/fonts/tabler-icons.css') !!}" />
    <!-- Core CSS -->
    <link rel="stylesheet" href="{!! asset('/assets/vendor/css/core.css') !!}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{!! asset('/assets/vendor/css/theme-default.css') !!}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{!! asset('/assets/css/demo.css') !!}" />
</head>
<body>
@include('admin.transactions.invoice')
<script>
    window.print();
</script>
</body>
</html>