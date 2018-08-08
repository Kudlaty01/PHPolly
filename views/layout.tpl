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
<div class="container">
<h1>{{ title }}</h1>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <ul class="navbar-nav mr-auto" id="menu">{{ navigation }}</ul>
</nav>
{{ templateData }}
</div>
</body>
</html>