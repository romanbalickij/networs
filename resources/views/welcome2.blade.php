<link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@latest/swagger-ui.css">
<script src="https://unpkg.com/swagger-ui-dist@4.5.0/swagger-ui-bundle.js" crossorigin></script>

<div id="swagger-ui"></div>

<script>
    window.onload = function () {
        window.ui = SwaggerUIBundle({
            url: 'http://127.0.0.1:8000/api-docs.json',

            dom_id: '#swagger-ui',
        });
    };
</script>


