<?php
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    protected $conn;

    protected function setUp(): void
    {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "testDB";  // Use a separate test database

        // Create a new connection
        $this->conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function testConnection()
    {
        $this->assertInstanceOf(mysqli::class, $this->conn);
    }

    protected function tearDown(): void
    {
        $this->conn->close();
    }
}
?>
