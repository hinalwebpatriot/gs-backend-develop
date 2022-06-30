<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style type="text/css">
        .ExternalClass {
            width: 100%;
        }

        img {
            border: 0 none;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
            -ms-interpolation-mode: bicubic;
        }

        a img {
            border: 0 none;
        }

        #outlook a {
            padding: 0;
        }

        #allrecords {
            height: 100% !important;
            margin: 0;
            padding: 0;
            width: 100% !important;
            -webkit-font-smoothing: antialiased;
            line-height: 1.45;
        }

        #allrecords td {
            margin: 0;
            padding: 0;
        }

        #allrecords ul {
            -webkit-padding-start: 30px;
        }

        #allrecords a {
            color: #000000;
            text-decoration: none;
        }

        .t-records ol, .t-records ul {
            padding-left: 20px;
            margin-top: 0px;
            margin-bottom: 10px;
        }

        @media only screen and (max-width: 600px) {
            .r {
                width: 100% !important;
                min-width: 400px !important;
            }
        }

        @media only screen and (max-width: 480px) {
            .t-emailBlock {
                display: block !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
                width: 100% !important;
            }

            .t-emailBlockPadding {
                padding-top: 15px !important;
            }

            .t-emailBlockPadding30 {
                padding-top: 30px !important;
            }

            .t-emailAlignLeft {
                text-align: left !important;
                margin-left: 0 !important;
            }

            .t-emailAlignCenter {
                text-align: center !important;
                margin-left: auto !important;
                margin-right: auto !important;
            }
        }

        table {
            border-collapse: collapse;
            border-spacing: 1px;
            border: 0;
        }
    </style>
</head>
<body>
@include('catalog::layouts.payment-invoice-parts.header')
@yield('content')
@include('catalog::layouts.payment-invoice-parts.footer')
</body>
</html>