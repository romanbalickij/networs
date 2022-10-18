<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laravel 7 PDF Example</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vendor/mail/html/themes/default.css">
{{--    error  fopen(/home/roman/www/privateNetwork/storage/fonts//montserrat_normal_b74b751c20b429298921949795391ef1.ufm): Failed to open stream: No such file or directory--}}
        {{--    <link href="https://fonts.googleapis.com/css2?family=Montserrat&family=Roboto&display=swap" rel="stylesheet">--}}
        {{--    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">--}}
{{-- end error   --}}
    <style>
        :root {
            --blue: #EBF5FA;
            --primary: #0000ff;
            --secondary: #3d3d3d;
            --white: #fff;
        }

        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Montserrat', sans-serif;
        }

        body.invoice{
            background: var(--blue);
            padding: 50px;
            color: var(--secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .bold{
            font-weight: 900;
        }

        p {
            margin-bottom: 0;
        }

        .light{
            font-weight: 100;
        }

        .invoice .wrapper{
            background: var(--white);
            padding: 40px;
            max-width: 595px;
            width: 100%;
        }

        .row {
            margin: 0;
        }

        .invoice_wrapper .header .logo_address_wrap,
        .invoice_wrapper .header .invoice_sec_wrap{
            display: flex;
            justify-content: space-between;
            margin-bottom: 16px;
            /*padding: 30px;*/
        }

        .invoice_wrapper .header .logo_sec{
            display: flex;
            align-items: center;
        }

        .invoice_wrapper .header .logo_sec .title_wrap{
            margin-left: 5px;
        }

        .invoice_wrapper .header .logo_sec .title_wrap .title{
            text-transform: uppercase;
            font-size: 18px;
            color: var(--primary);
        }

        .invoice_wrapper .header .logo_sec .title_wrap .sub_title{
            font-size: 12px;
        }

        .invoice_wrapper .header .address {
            text-align: right;
        }

        .invoice_wrapper .header .address {
            display: flex;
            align-items: flex-end;
            max-width: 191px;
            font-weight: 400;
            font-size: 12px;
            line-height: 137%;
            color: #0D4680;
        }

        .invoice_wrapper .header .invoice_sec .invoice_no,
        .invoice_wrapper .header .invoice_sec .date{
            display: flex;
            width: 100%;
        }

        .invoice_wrapper .header .invoice_sec .invoice_no span:first-child,
        .invoice_wrapper .header .invoice_sec .date span:first-child{
            /*width: 70px;*/
            text-align: left;
        }

        .invoice_wrapper .header .invoice_sec .invoice_no span:last-child,
        .invoice_wrapper .header .invoice_sec .date span:last-child{
            width: calc(100% - 70px);
        }


        .invoice_wrapper .body .main_table .row{
            display: flex;
        }

        .invoice_wrapper .body .main_table .row:not(.total){
            border-bottom: 1px solid var(--secondary);
        }

        .invoice_wrapper .body .main_table .row .col{
            flex-basis: auto;
            padding: 10px;
        }
        .invoice_wrapper .body .main_table .row .col_no{width: 7%;}
        .invoice_wrapper .body .main_table .row .col_des{width: 45%;}
        .invoice_wrapper .body .main_table .row .col_price{width: 35%;}
        .invoice_wrapper .body .main_table .row .col_qty{width: 13%; text-align: right;}

        table.meta th { padding-right: 8px }
        table.meta {
            border-collapse: separate;
            border-spacing: 0 4px;
            font-weight: 700;
            font-size: 16px;
            line-height: 140%;
            color: #363D40;
            text-align: left;
        }
    </style>
</head>

<body class="invoice">
<div class="wrapper">
    <div class="invoice_wrapper">
        <div class="header">
            <div class="logo_address_wrap">
                <div class="logo_sec">
                    <img src="/img/Logo_new.svg" alt="logo">
                </div>
                <div class="address">
                    <p>London, UK, 10.10.2030 41 Devonshire Street  |  W1G 7AJ</p>
                </div>
            </div>
            <div class="invoice_sec_wrap">
                <table class="meta">
                    <tr>
                        <th><span>Invoice number:</span></th>
                        <td><span>2030-07-000000021</span></td>
                    </tr>
                    <tr>
                        <th><span>Type:</span></th>
                        <td><span>credit</span></td>
                    </tr>
                    <tr>
                        <th><span>Your user ID:</span></th>
                        <td><span id="prefix">$</span><span>14723</span></td>
                    </tr>
                    <tr>
                        <th><span>Date:</span></th>
                        <td><span>14 July 2030</span></td>
                    </tr>
                </table>
        </div>
        <div class="body">
            <div class="main_table">
                <div class="table_header">
                    <div class="row">
                        <div class="col col_no">#</div>
                        <div class="col col_des">Service</div>
                        <div class="col col_price">Time period</div>
                        <div class="col col_qty">Sum</div>
                    </div>
                </div>
                <div class="table_body">
                    <div class="row">
                        <div class="col col_no">
                            <p>01</p>
                        </div>
                        <div class="col col_des">
                            Subscription to Jane Doe: Basic #124718623
                        </div>
                        <div class="col col_price">
                            15 June 2030 - 14 July 2030
                        </div>
                        <div class="col col_qty">
                            <p>$700.00</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col_no">
                            <p>02</p>
                        </div>
                        <div class="col col_des">
                            Subscription to Jane Doe: Basic #124718623
                        </div>
                        <div class="col col_price">
                            15 June 2030 - 14 July 2030
                        </div>
                        <div class="col col_qty">
                            <p>$700.00</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col col_no">
                            <p>03</p>
                        </div>
                        <div class="col col_des">
                            Subscription to Jane Doe: Basic #124718623
                        </div>
                        <div class="col col_price">
                            15 June 2030 - 14 July 2030
                        </div>
                        <div class="col col_qty">
                            <p>$700.00</p>
                        </div>
                    </div>
                    <div class="row total">
                        <div class="col col_no">
                        </div>
                        <div class="col col_des">
                            <p class="bold">Total</p>
                        </div>
                        <div class="col col_price">
                        </div>
                        <div class="col col_qty">
                            <p>$700.00</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
{{--<body>--}}
{{--<div class="container mt-5">--}}
{{--    <h2 class="text-center mb-3">PDF Example</h2>--}}

{{--    <table class="table table-bordered mb-5">--}}
{{--        <thead>--}}
{{--        <tr class="table-danger">--}}
{{--            <th scope="col">#</th>--}}
{{--            <th scope="col">counterparty name </th>--}}
{{--            <th scope="col">type</th>--}}
{{--            <th scope="col">sum</th>--}}
{{--            <th scope="col">direction</th>--}}
{{--            <th scope="col">purpose</th>--}}
{{--            <th scope="col">commission</th>--}}
{{--        </tr>--}}
{{--        </thead>--}}
{{--        <tbody>--}}
{{--            <tr>--}}
{{--                <th scope="row">{{ $data->id }}</th>--}}
{{--                <td>{{ $data->user->fullName }}</td>--}}
{{--                <td>{{ $data->type }}</td>--}}
{{--                <td>{{ $data->sum }}</td>--}}
{{--                <td>{{ $data->direction }}</td>--}}
{{--                <td>{{ $data->purpose_string }}</td>--}}
{{--                <td>{{ $data->commission_sum }}</td>--}}
{{--            </tr>--}}
{{--        </tbody>--}}
{{--    </table>--}}

{{--</div>--}}
{{--</body>--}}

</html>
