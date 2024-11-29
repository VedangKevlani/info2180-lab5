// Function to display the result
function displayResult(data) {
    const resultDiv = document.getElementById('result');
    if (data.error) {
        resultDiv.innerHTML = `<p>${data.error}</p>`;
    } else if (Array.isArray(data)) {
        // Build a table for the results
        let table = '<table><thead><tr><th>Name</th><th>Continent</th><th>Independence</th><th>Head of State</th></tr></thead><tbody>';
        data.forEach(row => {
            table += `<tr>
                        <td>${row.name}</td>
                        <td>${row.continent}</td>
                        <td>${row.independence_year}</td>
                        <td>${row.head_of_state}</td>
                      </tr>`;
        });
        table += '</tbody></table>';
        resultDiv.innerHTML = table;
    } else {
        resultDiv.innerHTML = '<p>No results found.</p>';
    }
}

$(document).ready(function() {
    $('#lookup-country').click(function() {
        var country = $('#country').val().trim();

        if (country !== '') {
            $.ajax({
                url: 'world.php',  // Adjust to your PHP file path
                type: 'GET',
                data: { country: country, lookup: 'country' },
                success: function(response) {
                    // Display the response (table data)
                    $('#result').html(response);
                },
                error: function(xhr, status, error) {
                    console.log('AJAX error: ' + status + ' ' + error);
                }
            });
        } else {
            $('#result').html('<p>Please enter a country or city.</p>');
        }
    });
});

$(document).ready(function() {
    $('#lookup-cities').click(function() {
        var country = $('#country').val().trim();

        if (country !== '') {
            $.ajax({
                url: 'world.php',  // Adjust to your PHP file path
                type: 'GET',
                data: { country: country, lookup: 'city' },
                success: function(response) {
                    console.log('Response from PHP:', response); // Log the raw HTML response
                    $('#result').html(response); // Insert the response into the result div
                },                
                error: function(xhr, status, error) {
                    console.log('AJAX error: ' + status + ' ' + error);
                }
            });
        } else {
            $('#result').html('<p>Please enter a country or city.</p>');
        }
    });
});


