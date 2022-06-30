<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="250" height="250" viewBox="0 0 79 79"><defs><path id="82y7a" d="M429.82 131.586c-21.484.015-39.213-17.425-39.12-39.338.093-21.741 18-39.385 40.008-38.93 20.796.43 38.36 17.43 38.27 39.325-.088 21.639-17.68 38.97-39.158 38.943zm.01-4.02c19.115.072 35.08-15.421 35.138-34.985.058-19.665-15.752-34.878-34.367-35.254-19.717-.398-35.842 15.434-35.891 35.01-.05 19.726 15.992 35.294 35.12 35.229z"/><path id="82y7b" d="M427.861 87.151c-.27 0-.538.024-.795-.014-.088-.013-.187-.17-.227-.28-.459-1.296-.877-2.605-1.368-3.889-.48-1.26-1.089-2.462-2.02-3.467-1.102-1.192-2.491-1.614-4.075-1.54-.884.042-1.75.164-2.575.496-1.588.638-2.61 1.839-3.304 3.358-.608 1.329-.929 2.739-1.146 4.176-.412 2.706-.436 5.433-.372 8.158.056 2.384.21 4.764.816 7.087.332 1.27.801 2.48 1.674 3.491.89 1.03 2.05 1.552 3.371 1.736 1.15.16 2.306.2 3.426-.189 1.167-.407 2.117-1.086 2.594-2.27a3.12 3.12 0 0 0 .206-1.115c.018-2.538.01-5.076.009-7.613 0-.06-.007-.122-.012-.214h-4.448v-.93h10.869v.913h-1.835v.403c0 3.164-.005 6.328.006 9.492.001.274-.09.414-.326.54a16.305 16.305 0 0 1-5.28 1.738c-1.2.18-2.423.31-3.636.303-2.665-.015-5.185-.613-7.404-2.186-2.029-1.436-3.343-3.398-4.176-5.707-.74-2.052-1.03-4.183-1.114-6.355-.107-2.765.228-5.455 1.299-8.023 1.357-3.255 3.567-5.711 6.825-7.157 1.497-.664 3.073-.991 4.705-1.081 2.402-.132 4.695.34 6.925 1.19.416.158.829.32 1.235.5.081.036.178.16.178.242.006 2.708 0 5.415-.003 8.122 0 .01-.004.02-.022.085"/><path id="82y7c" d="M449.797 87.153c-.272 0-.537.018-.796-.011-.075-.008-.169-.139-.199-.232-.476-1.512-.941-3.028-1.413-4.541-.367-1.178-.878-2.272-1.818-3.117-.884-.793-1.949-1.142-3.099-1.252-1.47-.143-2.902.018-4.233.709-1.605.834-2.676 2.106-2.994 3.914-.31 1.767.161 3.254 1.715 4.297 1.143.768 2.414 1.27 3.681 1.781 2 .808 4.019 1.576 5.995 2.44 1.336.582 2.572 1.353 3.52 2.504.802.978 1.17 2.124 1.3 3.357.147 1.396.057 2.774-.41 4.108-.804 2.3-2.357 3.939-4.493 5.054-1.662.868-3.445 1.282-5.31 1.345-2.981.1-5.726-.727-8.304-2.18-.154-.086-.26-.18-.259-.39.008-2.951.005-5.903.006-8.856 0-.051.008-.102.015-.204.28 0 .553-.014.822.01.063.005.144.129.167.211.408 1.5.77 3.013 1.221 4.499.457 1.503 1.046 2.957 2.067 4.19.918 1.108 2.109 1.665 3.534 1.757 1.744.112 3.372-.239 4.834-1.224 2.298-1.55 3.225-4.519 2.185-6.98-.474-1.12-1.317-1.909-2.317-2.55-1.365-.874-2.88-1.423-4.383-1.994-1.672-.634-3.36-1.235-4.897-2.172-1.47-.894-2.687-2.032-3.244-3.702-1.149-3.445-.041-7.706 3.861-9.723 1.415-.731 2.927-1.096 4.513-1.176a18.722 18.722 0 0 1 8.57 1.581c.089.039.187.18.188.274.01 2.697.008 5.394.006 8.09 0 .051-.018.102-.031.183"/></defs><g><g transform="translate(-390 -53)"><g><use fill="#a28a5b" xlink:href="#82y7a"/></g><g><use fill="#a28a5b" xlink:href="#82y7b"/></g><g><use fill="#a28a5b" xlink:href="#82y7c"/></g></g></g></svg>
                </div>
            </div>
        </div>
    </body>
</html>