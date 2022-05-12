<html>
    <head></head>
    <style>
        body, p, table {
            font-family: Verdana, Geneva, Tahoma, sans-serif;
            font-size: 9pt;
        }
        td.label {
            width: 150px;
            font-style: italic;
        }
    </style>
    <body>
        <p style="text-align:center;">
            <img alt="Elang Merah API" title= "Elang Merah API" width="125" height="40" src="https://account.elangmerah.com/assets/images/logo.png">
        </p>
        <p>Verifikasi alamat email <i>{{account.email}}</i> berhasil</p>

        <table>
            <tr>
                <td class="label">Nama</td>
                <td class="value">{{account.name}}</td>
            </tr>
            <tr>
                <td class="label">Username</td>
                <td class="value">{{account.username}}</td>
            </tr>
            <tr>
                <td class="label">Email</td>
                <td class="value">{{account.email}}</td>
            </tr>
        </table>
    </body>
</html>