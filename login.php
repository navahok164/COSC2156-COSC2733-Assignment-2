<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();  // Start a session

include 'userdb.php';

$login_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = trim($_POST['email']);
  $password = trim($_POST['password']);

  if (empty($email)) {
      $login_error = "Please enter your email.";
  } else if ($email === 'admin@hotmail.com' && $password === 'unixsystem') {
      // If email is admin@hotmail.com and password is unixsystem, login to combined_management.php
      $_SESSION['loggedin'] = true;
      $_SESSION['user_email'] = $email;
      header("Location: combined_management.php");
      exit();
  } else {
      // Prepare SQL statement to check if email and password match any user in the database
      $stmt = $conn->prepare("SELECT * FROM user WHERE email = ? AND pwd = ?");
      $stmt->bind_param("ss", $email, $password);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
          // Email and password exist, set session variables and redirect to the userindex.php page
          $_SESSION['loggedin'] = true;
          $_SESSION['user_email'] = $email;
          header("Location: userindex.php");
          exit();
      } else {
          // Login failed, either email or password is incorrect
          $login_error = "Login failed. Incorrect email or password.";
      }

      $stmt->close();
  }

  $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Page</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
  <div class="bg-gray-100 flex justify-center items-center h-screen">
    <div class="w-1/2 h-screen hidden lg:block">
      <img src="https://cre.moodysanalytics.com//app/uploads/2024/01/AdobeStock_619590612.jpg" alt="Placeholder Image"
        class="object-cover w-full h-full">
    </div>

    <div class="lg:p-36 md:p-52 sm:20 p-8 w-full lg:w-1/2">
      <div class="container px-4 mx-auto bg-white p-10 rounded-lg shadow-lg">
        <h1 class="text-5xl font-semibold mb-10 mx-auto text-center">Login</h1>
        <div class="text-center">
          <div
            class="h-40 w-40 overflow-hidden rounded-lg ring-2 ring-gray-700 text-center mx-auto border-4 border-gray-200">
            <img src="https://cdn.pixabay.com/photo/2016/03/31/19/56/avatar-1295397__340.png" alt="Avatar" />
          </div>
        </div>

        <!-- Display error message if login fails -->
        <?php if (!empty($login_error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
          <span class="block sm:inline"><?php echo $login_error; ?></span>
        </div>
        <?php endif; ?>
        
        <!-- Form starts here -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
          <div class="mb-4">
            <label class="text-gray-800 font-semibold block my-3 text-md" for="email">Email</label>
            <input class="w-full bg-gray-100 px-2 py-2 rounded-lg focus:outline-none" type="email" name="email" id="email"
              placeholder="Enter your email" required />
            <label class="text-gray-800 font-semibold block my-3 text-md" for="password">Password</label>
            <input class="w-full bg-gray-100 px-2 py-2 rounded-lg focus:outline-none" type="password" name="password" id="password"
              placeholder="Enter your password" required />
          </div>

          <button type="submit"
            class="bg-blue-900 hover:bg-blue-600 text-white font-semibold rounded-md py-2 px-4 w-full mt-2">Login</button>
        </form>
        <!-- Form ends here -->

        <div class="mt-6 text-blue-900 text-center">
          <a href="register.php" class="hover:underline">Sign up Here</a>
        </div>
      </div>
    </div>
  </div>
</body>

</html>
