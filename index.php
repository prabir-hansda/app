<?php
// --- Contact Data ---
// This associative array stores our contact names and their corresponding phone numbers.
// In a real-world application, this data would likely come from a database.
$contacts = [
    "Ram Sharma" => "9876543210",
    "Priya Singh" => "8765432109",
    "Amit Kumar" => "7654321098",
    "Neha Gupta" => "6543210987",
    "Sunil Verma" => "5432109876"
];

// Check if a specific contact number is being requested via AJAX
if (isset($_GET['action']) && $_GET['action'] === 'getContact' && isset($_GET['name'])) {
    header('Content-Type: application/json'); // Tell the browser we're sending JSON
    $contactName = $_GET['name'];
    
    if (array_key_exists($contactName, $contacts)) {
        echo json_encode(["name" => $contactName, "number" => $contacts[$contactName]]);
    } else {
        http_response_code(404); // Set HTTP status code to 404 (Not Found)
        echo json_encode(["error" => "Contact not found"]);
    }
    exit; // Stop further script execution after sending JSON
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact List (PHP)</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
            color: #333;
        }
        h1 {
            color: #0056b3;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            background-color: #fff;
            margin-bottom: 10px;
            padding: 10px 15px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        #contact-details {
            margin-top: 20px;
            padding: 15px;
            background-color: #e9f7ef;
            border-left: 5px solid #28a745;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <h1>Our Contacts</h1>
    <p>Click on a name to see their contact number.</p>

    <ul id="contact-list">
        <?php
        // Loop through the contacts array and generate list items
        foreach ($contacts as $name => $number) {
            echo "<li><a href=\"#\" onclick=\"getContactNumber('" . htmlspecialchars($name) . "'); return false;\">" . htmlspecialchars($name) . "</a></li>";
        }
        ?>
    </ul>

    <div id="contact-details">
        Select a contact to view details.
    </div>

    <script>
        function getContactNumber(name) {
            // Makes an AJAX call to the same PHP script, but with specific parameters
            fetch(`?action=getContact&name=${encodeURIComponent(name)}`)
                .then(response => {
                    if (!response.ok) { // Check if HTTP status is not 2xx
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    const contactDetailsDiv = document.getElementById('contact-details');
                    if (data.number) {
                        contactDetailsDiv.innerHTML = `<strong>Name:</strong> ${data.name}<br><strong>Number:</strong> ${data.number}`;
                    } else {
                        // This block might not be strictly necessary if backend handles 404 well
                        contactDetailsDiv.innerHTML = `Error: ${data.error || 'Unknown error'}`;
                    }
                })
                .catch(error => {
                    console.error('Error fetching contact number:', error);
                    document.getElementById('contact-details').innerHTML = 'An error occurred while fetching details.';
                });
        }
    </script>
</body>
</html>
