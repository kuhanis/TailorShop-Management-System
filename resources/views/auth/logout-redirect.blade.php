<!DOCTYPE html>
<html>
<head>
    <title>Logging Out...</title>
    <script>
        window.onload = function() {
            window.location.replace("{{ route('login') }}");
        }
        // Prevent back button
        window.history.forward();
        function noBack() {
            window.history.forward();
        }
    </script>
</head>
<body onload="noBack();" onpageshow="if (event.persisted) noBack();" onunload="">
    <p>Logging out...</p>
</body>
</html> 