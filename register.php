<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the database connection file
include 'userdb.php';

$register_error = '';
$register_success = '';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $phonenumber = trim($_POST['phonenumber']);
    $password = trim($_POST['password']);

    // Basic validation
    if (empty($firstname) || empty($lastname) || empty($email) || empty($phonenumber) || empty($password)) {
        $register_error = "All fields are required.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT email FROM user WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $register_error = "Email already registered.";
        } else {
            // Prepare the SQL statement to prevent SQL injection
            $stmt = $conn->prepare("INSERT INTO user (firstname, lastname, email, phonenumber, pwd) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $firstname, $lastname, $email, $phonenumber, $password);

            // Execute the query
            if ($stmt->execute()) {
                $register_success = "Registration successful! Redirecting to login page...";
                header("refresh:2; url=login.php");
            } else {
                $register_error = "Error: " . $stmt->error;
            }
        }

        // Close the statement
        $stmt->close();
    }

    // Close the connection
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <div class="bg-gray-100 flex justify-center items-center h-screen">
        <div class="w-1/2 h-screen hidden lg:block ">
            <img src="https://cre.moodysanalytics.com//app/uploads/2024/01/AdobeStock_619590612.jpg"
                alt="Placeholder Image" class="object-cover w-full h-full">
        </div>

        <div class="lg:p-36 md:p-52 sm:20 p-8 w-full lg:w-1/2">
            <div>
                <form class="bg-white p-10 rounded-lg shadow-lg min-w-full" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <h1 class="text-5xl font-semibold mb-10 mx-auto text-center">Register Form</h1>

                    <!-- Display success message -->
                    <?php if (!empty($register_success)): ?>
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                            <span class="block sm:inline"><?php echo $register_success; ?></span>
                        </div>
                    <?php endif; ?>

                    <!-- Display error message -->
                    <?php if (!empty($register_error)): ?>
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                            <span class="block sm:inline"><?php echo $register_error; ?></span>
                        </div>
                    <?php endif; ?>

                    <div>
                        <label class="text-gray-800 font-semibold block my-3 text-md" for="firstname">First Name</label>
                        <input class="w-full bg-gray-100 px-2 py-2 rounded-lg focus:outline-none" type="text" name="firstname" id="firstname" placeholder="First Name" required>
                    </div>

                    <div>
                        <label class="text-gray-800 font-semibold block my-3 text-md" for="lastname">Last Name</label>
                        <input class="w-full bg-gray-100 px-2 py-2 rounded-lg focus:outline-none" type="text" name="lastname" id="lastname" placeholder="Last Name" required>
                    </div>

                    <div>
                        <label class="text-gray-800 font-semibold block my-3 text-md" for="email">Email</label>
                        <input class="w-full bg-gray-100 px-2 py-2 rounded-lg focus:outline-none" type="email" name="email" id="email" placeholder="Email" required>
                    </div>

                    <div>
                        <label class="text-gray-800 font-semibold block my-3 text-md" for="password">Password</label>
                        <input class="w-full bg-gray-100 px-2 py-2 rounded-lg focus:outline-none" type="password" name="password" id="password" placeholder="Password" required>
                    </div>

                    <div>
                        <label class="text-gray-800 font-semibold block my-3 text-md" for="phonenumber">Phone Number</label>
                        <input class="w-full bg-gray-100 px-2 py-2 rounded-lg focus:outline-none" type="text" name="phonenumber" id="phonenumber" placeholder="Phone Number" required>
                    </div>

                    <button type="submit" class="bg-blue-900 hover:bg-blue-600 text-white font-semibold rounded-md py-2 px-4 w-full mt-5">
                        Register
                    </button>

                    <div class="mt-6 text-blue-900 text-center">
                        <span>Already have an account?</span> <a href="login.php" class="hover:underline">Login Here</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
