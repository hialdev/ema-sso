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
        {%block email_header%}
        <p style="text-align:center;">
            <img alt="Elang Merah API" title= "Elang Merah API" width="80" src="https://account.elangmerah.com/assets/images/logo.png">
        </p>
        {%endblock%}

        {%block email_body%}
        {%endblock%}

        {%block email_footer%}
        {%endblock%}
    </body>
</html>