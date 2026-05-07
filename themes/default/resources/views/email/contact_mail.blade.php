<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <p>First Name: {{ $data['firstName'] }}</p>
    <p>Last Name: {{ $data['lastName'] }}</p>
    <p>Business Name: {{ $data['businessName'] }}</p>
    <p>Business Type: {{ $data['businessType'] }}</p>
    <p>Email: {{ $data['email'] }}</p>
    <p>Subject: {{ $data['subject'] }}</p>
    <article>{{ $data['message'] }}</article>
</body>
</html>