<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Polling application - {{ title }}</title>
    <link rel="stylesheet" href="/public/css/main.css">
    <script type="text/javascript" src="/public/js/ajax.js"></script>
    <script type="text/javascript" src="/public/js/components/knockout/build/output/knockout-latest.js"></script>
    {{ controllerScripts }}
</head>
<body>
<h1>{{ title }}</h1>
<div>
    <ul id="menu">{{ navigation }}</ul>
</div>
{{ templateData }}
</body>
</html>