<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=400"/>
    <title>@yield('title')</title>
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
        }</style>
</head>
<body cellpadding="0" cellspacing="0" bgcolor="#ffffff"
      style="padding: 0; margin: 0; border: 0; width:100%; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; background-color: #ffffff;">
<table id="allrecords" class="t-records" cellpadding="0" cellspacing="0"
       style="width:100%; border-collapse:collapse; border-spacing:0; padding:0; margin:0; border:0;">
    <tr>
        <td style="background-color: #ffffff; ">
            @include('catalog::layouts.payment-parts.header')
            @yield('content')
            @include('catalog::layouts.payment-parts.footer')
        </td>
    </tr>
</table>

</body>
</html>