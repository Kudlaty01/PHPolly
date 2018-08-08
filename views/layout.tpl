<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Polling application - {{ title }}</title>
    <link rel="stylesheet" href="/public/css/main.css">
    <link rel="stylesheet" href="/public/js/components/bootstrap-css-only/css/bootstrap.css">
    <script type="text/javascript" src="/public/js/ajax.js"></script>
    <script type="text/javascript" src="/public/js/components/knockout/build/output/knockout-latest.js"></script>
    {{ controllerScripts }}
</head>
<body>
<h1>{{ title }}</h1>
<nav>
    <ul id="menu">{{ navigation }}</ul>
</nav>
{{ templateData }}
</body>
</html>