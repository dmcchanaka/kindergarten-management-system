<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="csrf-token" value="{{ csrf_token() }}"/>
   <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
   <title>Portal</title>
   @vite('resources/css/app.css')
</head>
<body class="antialiased m-0 font-sans antialiased font-normal text-base leading-default bg-gray-50 text-slate-500">
   <div id="app"></div>
   @vite('resources/ts/app.ts')
</body>
</html>
