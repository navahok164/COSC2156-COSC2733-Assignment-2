<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
      

        <div class="container mx-auto py-10">
           
                <div>
                    <div class="py-3 border-b bg-gray-700 pl-16 font-semibold text-white flex justify-between">
                        <h1 class="text-3xl">View Estate Info</h1>
                        
                    </div>
                </div>

                <div class="overflow-x-auto bg-white rounded-lg shadow overflow-y-auto relative" style="height: 800px;">
                    <table class="border-collapse table-auto w-full whitespace-no-wrap bg-white table-striped relative">
                        <thead>
                            <tr class="text-left">
                                <th class="py-2 px-3 sticky top-0 border-b border-gray-200 bg-gray-100"></th>
                                <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-sm">ID</th>
                                <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-sm">Owner ID</th>
                                <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-sm">Address</th>
                                <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-sm">Price</th>
                                <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-sm">Description</th>
                                <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-sm">Status</th>
                                <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-sm">Created At</th>
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
                                        <td class='border-dashed border-t border-gray-200 userId'><span class='text-gray-700 px-6 py-3 flex items-center'>{$row['estate_id']}</span></td>
                                        <td class='border-dashed border-t border-gray-200 firstName'><span class='text-gray-700 px-6 py-3 flex items-center'>{$row['owner_id']}</span></td>
                                        <td class='border-dashed border-t border-gray-200 lastName'><span class='text-gray-700 px-6 py-3 flex items-center'>{$row['address']}</span></td>
                                        <td class='border-dashed border-t border-gray-200 emailAddress'><span class='text-gray-700 px-6 py-3 flex items-center'>{$row['price']}</span></td>
                                        <td class='border-dashed border-t border-gray-200 phoneNumber'><span class='text-gray-700 px-6 py-3 flex items-center'>{$row['description']}</span></td>
                                        <td class='border-dashed border-t border-gray-200 phoneNumber'><span class='text-gray-700 px-6 py-3 flex items-center'>{$row['status']}</span></td>
                                        <td class='border-dashed border-t border-gray-200 phoneNumber'><span class='text-gray-700 px-6 py-3 flex items-center'>{$row['created_at']}</span></td>
                                        <td class='border-dashed border-t border-gray-200 '>
                                          
                                            
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
        // Get the modal elements
        var addModal = document.getElementById("<?php echo $page == 'users' ? 'addUserModal' : 'addEstateModal'; ?>");
        var editModal = document.getElementById("<?php echo $page == 'users' ? 'editUserModal' : 'editEstateModal'; ?>");

        // Get the buttons that open the modals
        var openModalButton = document.getElementById("openModalButton");

        // Get the <span> elements that close the modals
        var closeAddModalButton = document.getElementById("closeModalButton");
        var closeEditModalButton = document.getElementById("closeEditModalButton");

        // When the user clicks the button, open the add modal
        openModalButton.onclick = function() {
            addModal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the add modal
        closeAddModalButton.onclick = function() {
            addModal.style.display = "none";
        }

        // When the user clicks on <span> (x), close the edit modal
        closeEditModalButton.onclick = function() {
            editModal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == addModal) {
                addModal.style.display = "none";
            }
            if (event.target == editModal) {
                editModal.style.display = "none";
            }
        }

        // Function to open the edit modal with pre-filled data
        function openEditModal(<?php echo $page == 'users' ? 'usrID, firstname, lastname, email, phonenumber' : 'estate_id, owner_id, address, price, description, status'; ?>) {
            <?php if ($page == 'users'): ?>
                document.getElementById('edit_usrID').value = usrID;
                document.getElementById('edit_firstname').value = firstname;
                document.getElementById('edit_lastname').value = lastname;
                document.getElementById('edit_email').value = email;
                document.getElementById('edit_phonenumber').value = phonenumber;
            <?php else: ?>
                document.getElementById('edit_estate_id').value = estate_id;
                document.getElementById('edit_owner_id').value = owner_id;
                document.getElementById('edit_address').value = address;
                document.getElementById('edit_price').value = price;
                document.getElementById('edit_description').value = description;
                document.getElementById('edit_status').value = status;
            <?php endif; ?>
            editModal.style.display = 'block';
        }
    </script>
</body>
</html>