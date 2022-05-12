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
            <img alt="Sahara" title= "Sahara" width="125" height="40" src="https://account.elangmerah.com/assets/images/logo.png">
        </p>
        <p>{{account.name}}, selamat datang. Akun Sahara Anda telah berhasil di daftarkan</p>
        <p>Berikut informasi akun anda</p>

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
                <td class="label">Password</td>
                <td class="value">{{password}}</td>
            </tr>
            <tr>
                <td class="label">Email</td>
                <td class="value">{{email}} - <i>belum diverifikasi</i></td>
            </tr>
        </table>

        <p>&nbsp;</p>
        <p><b><i>Disarankan untuk mengganti password setelah berhasil login pertama kali.</i></b></p>

        <p>Terima kasih</p>
    </body>
</html>