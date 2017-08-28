<?php 
    require('sql.php');

    if (isset($_POST['username'])) {
        $user = $_POST['username'];
        $pass = $_POST['password'];

        $query = "SELECT uid FROM user WHERE username = ? AND password = ? AND is_deleted = 0";

        if (!($stmt = $link->prepare($query))) {
            echo "Prepare failed: (" . $link->errno . ") " . $this->db->error;
        }

        $stmt->bind_param('ss', $user, $pass);
           
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        $result = '';

        $stmt->bind_result($result);

        $stmt->fetch();
        
        if (intval($result) > 0) {
            //create session
            session_start();
            $_SESSION['username'] = $user;
            header("Location: /index.php");
        } else {
            header("Location: /index.php");
        }
    }
?>