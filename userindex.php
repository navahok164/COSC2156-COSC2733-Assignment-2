<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();  // Start the session

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // If not logged in, show a 404 Not Found error
    header("HTTP/1.0 404 Not Found");
    exit("404 Not Found");
}

// Database connection
$servername = "localhost";
$username = "root"; 
$password = ""; 

// Determine which page to load
$page = isset($_GET['page']) ? $_GET['page'] : 'users';

if ($page == 'users') {
    $dbname = "eastateDB";
} elseif ($page == 'estates') {
    $dbname = "eastateDB";
} else {
    die("Invalid page requested.");
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function fetchEstates($conn) {
    $sql = "SELECT * FROM estateInfo";
    return $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management System</title>
    <link rel="stylesheet" href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css">
    <style>
        /* Custom styles for the modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 50;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
            border-radius: 10px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="antialiased sans-serif bg-gray-200 h-screen">
    <!-- Navigation Bar -->
    <nav class="bg-gray-800 p-4">
        <div class="container mx-auto flex justify-between items-center">
            <div>
                <!-- Logout Button -->
                <form action="logout.php" method="POST">
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>
    <div class="antialiased sans-serif bg-gray-200 h-screen">
        <!-- Navigation Bar -->
        <div class="container mx-auto py-10">
            <div>
                <div class="py-3 border-b bg-gray-700 pl-16 font-semibold text-white flex justify-between">
                    <h1 class="text-3xl">View Estate Info</h1>
                </div>
            </div>
            
            <!-- Modal to view estate info -->
            <div id="viewEstateModal" class="modal">
                <div class="modal-content">
                    <span class="close" id="closeViewModalButton">&times;</span>
                    <h2 class="text-2xl mb-4">Estate Information</h2>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Owner ID</label>
                        <p id="view_owner_id" class="mt-1 p-2 w-full border border-gray-300 rounded-md"></p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Address</label>
                        <p id="view_address" class="mt-1 p-2 w-full border border-gray-300 rounded-md"></p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Price</label>
                        <p id="view_price" class="mt-1 p-2 w-full border border-gray-300 rounded-md"></p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <p id="view_description" class="mt-1 p-2 w-full border border-gray-300 rounded-md"></p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <p id="view_status" class="mt-1 p-2 w-full border border-gray-300 rounded-md"></p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative" style="height: 800px;">
                <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                    <thead>
                        <tr class="text-left">
                            <th class="py-2 px-3 sticky top-0 border-b border-gray-200 bg-gray-100"></th>
                            <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-sm">Address</th>
                            <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-sm">Price</th>
                            <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-sm"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = fetchEstates($conn);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr class='hover:bg-gray-300'>
                                    <td class='border-dashed border-t border-gray-200 px-3'></td>
                                    <td class='border-dashed border-t border-gray-200 lastName'><span class='text-gray-700 px-6 py-3 flex items-center'>{$row['address']}</span></td>
                                    <td class='border-dashed border-t border-gray-200 emailAddress'><span class='text-gray-700 px-6 py-3 flex items-center'>{$row['price']}$</span></td>
                                    <td class='border-dashed border-t border-gray-200'>
                                        <button onclick='openViewModal({$row['estate_id']}, {$row['owner_id']}, \"{$row['address']}\", {$row['price']}, \"{$row['description']}\", \"{$row['status']}\")'
                                            class='flex items-center middle none center mr-4 rounded-lg bg-blue-500 py-2 px-4 font-sans text-xs font-bold uppercase text-white shadow-md shadow-blue-500/20 transition-all hover:shadow-lg hover:shadow-blue-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none'>
                                            View 
                                        </button>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9' class='text-center text-gray-700 px-6 py-3'>No estate information found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Get the modal element
        var viewModal = document.getElementById("viewEstateModal");

        // Get the <span> element that closes the modal
        var closeViewModalButton = document.getElementById("closeViewModalButton");

        // When the user clicks on <span> (x), close the view modal
        closeViewModalButton.onclick = function() {
            viewModal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == viewModal) {
                viewModal.style.display = "none";
            }
        }

        // Function to open the view modal with estate information
        function openViewModal(estate_id, owner_id, address, price, description, status) {
            document.getElementById('view_owner_id').textContent = owner_id;
            document.getElementById('view_address').textContent = address;
            document.getElementById('view_price').textContent = price + "$";
            document.getElementById('view_description').textContent = description;
            document.getElementById('view_status').textContent = status;
            viewModal.style.display = 'block';
        }
    </script>
</body>
</html>
