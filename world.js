$(document).ready(function () {
    function sanitizeInput(input) {
        const temp = document.createElement('div');
        temp.textContent = input;
        return temp.innerHTML;
    }

    $('#lookup-country').on('click', function() {
        const lookupButton = sanitizeInput($('#country').val().trim());
        const resultContainer = $('#result');

        // Clear previous results
        resultContainer.html('<hr>');

        // If no country is entered, display a message
        if (!lookupButton) {
            resultContainer.append('<p>No country entered</p>');
            return;
        }

        let url = 'http://localhost/info2180-lab5/world.php';
        console.log(lookupButton);

        $.ajax({
            url: url,
            type: 'GET',
            data: { query: lookupButton },  // Send the sanitized input as the query
            dataType: 'html',               // Expect HTML response from PHP
            success: function(data) {
                console.log(data);        
                resultContainer.html(data); // Insert the HTML table returned by PHP
            },
            error: function(xhr, status, error) {
                console.error('XHR Status: ', status);
                console.error('XHR Response Text: ', xhr.responseText);
                resultContainer.html('<p>Error: ' + error + '</p>');
            }
        });        
    });
});
