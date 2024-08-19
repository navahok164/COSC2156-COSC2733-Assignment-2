<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password (usually empty)
$dbname = "eastateDB";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle POST requests for Create, Update, and Delete operations
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                createEstate($conn);
                break;
            case 'update':
                updateEstate($conn);
                break;
            case 'delete':
                deleteEstate($conn);
                break;
        }
    }
}

// Function to create a new estate
function createEstate($conn) {
    $owner_id = trim($_POST['owner_id']);
    $address = trim($_POST['address']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);
    $status = trim($_POST['status']);

    if (empty($owner_id) || empty($address) || empty($price) || empty($description) || empty($status)) {
        echo "All fields are required.";
        return;
    }

    $stmt = $conn->prepare("INSERT INTO estateInfo (owner_id, address, price, description, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isdss", $owner_id, $address, $price, $description, $status);

    if ($stmt->execute()) {
        header("Location: estate_management.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Function to update an existing estate
function updateEstate($conn) {
    $estate_id = $_POST['estate_id'];
    $owner_id = trim($_POST['owner_id']);
    $address = trim($_POST['address']);
    $price = trim($_POST['price']);
    $description = trim($_POST['description']);
    $status = trim($_POST['status']);

    if (empty($owner_id) || empty($address) || empty($price) || empty($description) || empty($status)) {
        echo "All fields are required.";
        return;
    }

    $stmt = $conn->prepare("UPDATE estateInfo SET owner_id = ?, address = ?, price = ?, description = ?, status = ? WHERE estate_id = ?");
    $stmt->bind_param("isdssi", $owner_id, $address, $price, $description, $status, $estate_id);

    if ($stmt->execute()) {
        header("Location: estate_management.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Function to delete an estate
function deleteEstate($conn) {
    $estate_id = $_POST['estate_id'];

    $stmt = $conn->prepare("DELETE FROM estateInfo WHERE estate_id = ?");
    $stmt->bind_param("i", $estate_id);

    if ($stmt->execute()) {
        header("Location: estate_management.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Function to fetch all estates
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
    <title>Estate Management</title>
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
        <div class="container mx-auto py-10">
            <div>
                <div class="py-3 border-b bg-gray-700 pl-16 font-semibold text-white flex justify-between">
                    <h1 class="text-3xl">Manage Estate</h1>
                    <button id="openModalButton"
                        class="center mr-16 rounded-lg bg-green-500 px-6 font-sans text-xs font-bold uppercase text-white shadow-md shadow-green-500/20 transition-all hover:shadow-lg hover:shadow-green-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                        data-ripple-light="true">
                        Add Estate
                    </button>
                </div>
            </div>

            <!-- Modal for adding estate -->
            <div id="addEstateModal" class="modal">
                <div class="modal-content">
                    <span class="close" id="closeModalButton">&times;</span>
                    <h2 class="text-2xl mb-4">Add New Estate</h2>
                    <form id="addEstateForm" action="estate_management.php" method="POST">
                        <input type="hidden" name="action" value="create">
                        <div class="mb-4">
                            <label for="owner_id" class="block text-sm font-medium text-gray-700">Owner ID</label>
                            <input type="number" name="owner_id" id="owner_id" required class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                        </div>
                        <div class="mb-4">
                            <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                            <input type="text" name="address" id="address" required class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                        </div>
                        <div class="mb-4">
                            <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
                            <input type="number" step="0.01" name="price" id="price" required class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" required class="mt-1 p-2 w-full border border-gray-300 rounded-md"></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" required class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                                <option value="Available">Available</option>
                                <option value="Sold">Sold</option>
                                <option value="Pending">Pending</option>
                            </select>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Add Estate
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal for editing estate -->
            <div id="editEstateModal" class="modal">
                <div class="modal-content">
                    <span class="close" id="closeEditModalButton">&times;</span>
                    <h2 class="text-2xl mb-4">Edit Estate</h2>
                    <form id="editEstateForm" action="estate_management.php" method="POST">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="estate_id" id="edit_estate_id">
                        <div class="mb-4">
                            <label for="edit_owner_id" class="block text-sm font-medium text-gray-700">Owner ID</label>
                            <input type="number" name="owner_id" id="edit_owner_id" required class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                        </div>
                        <div class="mb-4">
                            <label for="edit_address" class="block text-sm font-medium text-gray-700">Address</label>
                            <input type="text" name="address" id="edit_address" required class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                        </div>
                        <div class="mb-4">
                            <label for="edit_price" class="block text-sm font-medium text-gray-700">Price</label>
                            <input type="number" step="0.01" name="price" id="edit_price" required class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                        </div>
                        <div class="mb-4">
                            <label for="edit_description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="edit_description" required class="mt-1 p-2 w-full border border-gray-300 rounded-md"></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="edit_status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="edit_status" required class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                                <option value="Available">Available</option>
                                <option value="Sold">Sold</option>
                                <option value="Pending">Pending</option>
                            </select>
                        </div>
                        <div class="text-right">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Estate
                            </button>
                        </div>
                    </form>
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
                                        <button onclick='openEditModal({$row['estate_id']}, {$row['owner_id']}, \"{$row['address']}\", {$row['price']}, \"{$row['description']}\", \"{$row['status']}\")'
                                                class='flex items-center middle none center mr-4 rounded-lg bg-yellow-500 py-2 px-4 font-sans text-xs font-bold uppercase text-white shadow-md shadow-yellow-500/20 transition-all hover:shadow-lg hover:shadow-yellow-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none'>
                                            Edit
                                        </button>
                                        <form action='estate_management.php' method='POST' style='display:inline;'>
                                            <input type='hidden' name='action' value='delete'>
                                            <input type='hidden' name='estate_id' value='{$row['estate_id']}'>
                                            <button type='submit'
                                                    class='flex items-center middle none center mr-4 rounded-lg bg-red-500 py-2 px-4 font-sans text-xs font-bold uppercase text-white shadow-md shadow-red-500/20 transition-all hover:shadow-lg hover:shadow-red-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none'>
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='9' class='text-center text-gray-700 px-6 py-3'>No estate information found.</td></tr>";
                        }

                        // Close the database connection
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Get the modal elements
        var addModal = document.getElementById("addEstateModal");
        var editModal = document.getElementById("editEstateModal");

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
        function openEditModal(estate_id, owner_id, address, price, description, status) {
            document.getElementById('edit_estate_id').value = estate_id;
            document.getElementById('edit_owner_id').value = owner_id;
            document.getElementById('edit_address').value = address;
            document.getElementById('edit_price').value = price;
            document.getElementById('edit_description').value = description;
            document.getElementById('edit_status').value = status;
            editModal.style.display = 'block';
        }
    </script>
</body>
</html>
