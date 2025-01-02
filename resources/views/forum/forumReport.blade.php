<!-- resources/views/forum/forumReport.blade.php -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-light">
    <div class="container my-5">
        <h1 class="text-center mb-4">Report Dashboard</h1>

        <div class="text-center mb-4">
            <button id="load-most-report" class="btn btn-primary mr-2">Load Most Used Tags Report</button>
            <button id="load-top-report" class="btn btn-secondary">Load Top Posts Report</button>
        </div>

        <!-- The div where the report will be displayed -->
        <div id="report-container" class="p-3 bg-white border rounded shadow-sm"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            console.log("hi")
            $('#load-most-report').click(function() {
                console.log("hi")
                // Send an AJAX request to get the transformed XML as HTML
                $.ajax({
                    url: '/most-used-tags',
                    method: 'GET',
                    success: function(response) {
                        // Insert the transformed XML (HTML) into the report-container div
                        $('#report-container').html(response);
                    },
                    error: function(xhr, status, error) {
                        alert('Error loading the report: ' + error);
                    }
                });
            });

            $('#load-top-report').click(function() {
                console.log("hi")
                // Send an AJAX request to get the transformed XML as HTML
                $.ajax({
                    url: '/top-posts',
                    method: 'GET',
                    success: function(response) {
                        // Insert the transformed XML (HTML) into the report-container div
                        $('#report-container').html(response);
                    },
                    error: function(xhr, status, error) {
                        alert('Error loading the report: ' + error);
                    }
                });
            });
        });
    </script>

</body>

</html>