<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password (usually empty)
$dbname = "userDB";

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
                createUser($conn);
                break;
            case 'update':
                updateUser($conn);
                break;
            case 'delete':
                deleteUser($conn);
                break;
        }
    }
}

// Function to create a new user
function createUser($conn) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phonenumber = $_POST['phonenumber'];

    if (empty($firstname) || empty($lastname) || empty($email) || empty($phonenumber)) {
        echo "All fields are required.";
        return;
    }

    $stmt = $conn->prepare("INSERT INTO user (firstname, lastname, email, phonenumber) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $firstname, $lastname, $email, $phonenumber);

    if ($stmt->execute()) {
        header("Location: user_management.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Function to update an existing user
function updateUser($conn) {
    $usrID = $_POST['usrID'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $phonenumber = $_POST['phonenumber'];

    if (empty($firstname) || empty($lastname) || empty($email) || empty($phonenumber)) {
        echo "All fields are required.";
        return;
    }

    $stmt = $conn->prepare("UPDATE user SET firstname = ?, lastname = ?, email = ?, phonenumber = ? WHERE usrID = ?");
    $stmt->bind_param("ssssi", $firstname, $lastname, $email, $phonenumber, $usrID);

    if ($stmt->execute()) {
        header("Location: user_management.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Function to delete a user
function deleteUser($conn) {
    $usrID = $_POST['usrID'];

    $stmt = $conn->prepare("DELETE FROM user WHERE usrID = ?");
    $stmt->bind_param("i", $usrID);

    if ($stmt->execute()) {
        header("Location: user_management.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Function to fetch all users
function fetchUsers($conn) {
    $sql = "SELECT * FROM user";
    return $conn->query($sql);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
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
                    <h1 class="text-3xl">Manage User</h1>
                    <button id="openModalButton"
                        class="center mr-16 rounded-lg bg-green-500 px-6 font-sans text-xs font-bold uppercase text-white shadow-md shadow-green-500/20 transition-all hover:shadow-lg hover:shadow-green-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                        data-ripple-light="true">
                        Add User
                    </button>
                </div>
            </div>

            <!-- Modal for adding user -->
            <div id="addUserModal" class="modal">
                <div class="modal-content">
                    <span class="close" id="closeModalButton">&times;</span>
                    <h2 class="text-2xl mb-4">Add New User</h2>
                    <form id="addUserForm" action="user_management.php" method="POST">
                        <input type="hidden" name="action" value="create">
                        <div class="mb-4">
                            <label for="firstname" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" name="firstname" id="firstname" required
                                class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                        </div>
                        <div class="mb-4">
                            <label for="lastname" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" name="lastname" id="lastname" required
                                class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" required
                                class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                        </div>
                        <div class="mb-4">
                            <label for="phonenumber" class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <input type="text" name="phonenumber" id="phonenumber" required
                                class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                        </div>
                        <div class="text-right">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Add User
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal for editing user -->
            <div id="editUserModal" class="modal">
                <div class="modal-content">
                    <span class="close" id="closeEditModalButton">&times;</span>
                    <h2 class="text-2xl mb-4">Edit User</h2>
                    <form id="editUserForm" action="user_management.php" method="POST">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="usrID" id="edit_usrID">
                        <div class="mb-4">
                            <label for="edit_firstname" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" name="firstname" id="edit_firstname" required
                                class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                        </div>
                        <div class="mb-4">
                            <label for="edit_lastname" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" name="lastname" id="edit_lastname" required
                                class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                        </div>
                        <div class="mb-4">
                            <label for="edit_email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="edit_email" required
                                class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                        </div>
                        <div class="mb-4">
                            <label for="edit_phonenumber" class="block text-sm font-medium text-gray-700">Phone Number</label>
                            <input type="text" name="phonenumber" id="edit_phonenumber" required
                                class="mt-1 p-2 w-full border border-gray-300 rounded-md">
                        </div>
                        <div class="text-right">
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update User
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
                            <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-sm">User ID</th>
                            <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-sm">First Name</th>
                            <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-sm">Last Name</th>
                            <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-sm">Email</th>
                            <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-sm">Phone Number</th>
                            <th class="bg-gray-100 sticky top-0 border-b border-gray-200 px-6 py-2 text-gray-600 font-bold tracking-wider uppercase text-sm"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = fetchUsers($conn);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr class='hover:bg-gray-300'>
                                    <td class='border-dashed border-t border-gray-200 px-3'></td>
                                    <td class='border-dashed border-t border-gray-200 userId'><span class='text-gray-700 px-6 py-3 flex items-center'>{$row['usrID']}</span></td>
                                    <td class='border-dashed border-t border-gray-200 firstName'><span class='text-gray-700 px-6 py-3 flex items-center'>{$row['firstname']}</span></td>
                                    <td class='border-dashed border-t border-gray-200 lastName'><span class='text-gray-700 px-6 py-3 flex items-center'>{$row['lastname']}</span></td>
                                    <td class='border-dashed border-t border-gray-200 emailAddress'><span class='text-gray-700 px-6 py-3 flex items-center'>{$row['email']}</span></td>
                                    <td class='border-dashed border-t border-gray-200 phoneNumber'><span class='text-gray-700 px-6 py-3 flex items-center'>{$row['phonenumber']}</span></td>
                                    <td class='border-dashed border-t border-gray-200'>
                                        <button onclick='openEditModal({$row['usrID']}, \"{$row['firstname']}\", \"{$row['lastname']}\", \"{$row['email']}\", \"{$row['phonenumber']}\")'
                                                class='flex items-center middle none center mr-4 rounded-lg bg-yellow-500 py-2 px-4 font-sans text-xs font-bold uppercase text-white shadow-md shadow-yellow-500/20 transition-all hover:shadow-lg hover:shadow-yellow-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none'>
                                            Edit
                                        </button>
                                        <form action='user_management.php' method='POST' style='display:inline;'>
                                            <input type='hidden' name='action' value='delete'>
                                            <input type='hidden' name='usrID' value='{$row['usrID']}'>
                                            <button type='submit'
                                                    class='flex items-center middle none center mr-4 rounded-lg bg-red-500 py-2 px-4 font-sans text-xs font-bold uppercase text-white shadow-md shadow-red-500/20 transition-all hover:shadow-lg hover:shadow-red-500/40 focus:opacity-[0.85] focus:shadow-none active:opacity-[0.85] active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none'>
                                                Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center text-gray-700 px-6 py-3'>No user information found.</td></tr>";
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
        var addModal = document.getElementById("addUserModal");
        var editModal = document.getElementById("editUserModal");

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
        function openEditModal(usrID, firstname, lastname, email, phonenumber) {
            document.getElementById('edit_usrID').value = usrID;
            document.getElementById('edit_firstname').value = firstname;
            document.getElementById('edit_lastname').value = lastname;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_phonenumber').value = phonenumber;
            editModal.style.display = 'block';
        }
    </script>
</body>
</html>
