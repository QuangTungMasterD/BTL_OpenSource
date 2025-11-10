
<?
  require_once 'db_connection.php';
  function getAllRole() {
    $conn = getDbConnection();

    $sql = "SELECT idRole, nameRole FROM roles";
    $result = mysqli_query($conn, $sql);
    $roles = [];
    if ($result && mysqli_num_rows($result) > 0) {
        
        while ($row = mysqli_fetch_assoc($result)) { 
            $roles[] = $row;
        }
    }

    mysqli_close($conn);
    return $roles;
  }
?>
