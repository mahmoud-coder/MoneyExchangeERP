<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF | Transaction</title>
   <style>
    body{
        font-family: "Public Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", "Oxygen", "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue", sans-serif;
    }
    hr{
        margin: 1rem 0;
        color: #dbdade;
        border: 0;
        border-top: 1px solid;
    }
    table{
        width:100%;
        border-collapse: collapse;
       
    }
    tr{
        border-bottom: 1px solid gray;
    }
    td, th{
        padding:15px;
        text-align: center;
    }
    .pdf-row::after{
        content: '';
        display:block;
        clear: both;
    }
    .pdf-col-9{
        width:66%;
        float:left;
    }
    .pdf-col-6{
        width:50%;
        float: left;
    }
    .pdf-col-3{
        width:33%;
        float:left;
    }
    .pdf-float-right{
        float:right;
    }
    .ms-4 {
        margin-left: 1.5rem !important;
    }
    
   </style>
</head>
<body>
@include('admin.transactions.invoice')
</body>
</html>