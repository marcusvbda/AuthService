<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; background-color: #f0f0f0; padding: 20px;">
    <table cellpadding="0" cellspacing="0" border="0" align="center" width="600"
        style="border-collapse: collapse; background-color: #ffffff;">
        <tr>
            <td style="padding: 20px;">
                <h1 style="color: #007bff;">@yield('title')</h1>
                @yield('content')
            </td>
        </tr>
    </table>
</body>

</html>
