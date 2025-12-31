<?php
// connect.php
class DBConnect
{
    private $u = "root";  // Database username
    private $p = "";      // Database password
    private $d = "e-commerce";  // Database name
    private $s = "localhost";   // Database server (usually localhost)
    public $db_handle;        // Public handle for database connection

    public function __construct()
    {
        // Create the database connection
        $this->db_handle = mysqli_connect($this->s, $this->u, $this->p, $this->d);

        // Check if connection was successful
        if (!$this->db_handle) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }
}
?>
