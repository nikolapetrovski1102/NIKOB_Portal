<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite([ 'resources/js/app.js','resources/sass/app.scss'])
</head>
<body>
<div class="container">
    <p>{{ __("Корисничко барање за претплата на месечна е-фактура, со следните податоци: ") }}</p>
    <table>
        <thead>
        <tr>
            <th>
                {{ __('Име на корисник/Назив ') }}
            </th>
            <th>
                {{ __('Адреса') }}
            </th>
            <th>
                {{ __('Телефонски број') }}
            </th>
            <th>
                {{ __('Е-пошта') }}
            </th>
            <th>
                {{ __('Број на клиент') }}
            </th>
            <th>
                {{ __('Број на фактура') }}
            </th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                {{ $data['name'] }}
            </td>
            <td>
                {{ $data['address'] }}
            </td>
            <td>
                {{ $data['phonePrefix'] . $data['phone'] }}
            </td>
            <td>
                {{ $data['email'] }}
            </td>
            <td>
                {{ $data['accountNumber'] }}
            </td>
            <td>
                {{ $data['invoiceNumber'] }}
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
