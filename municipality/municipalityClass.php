<?php 

/**
* class for managing municipality table
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 07. 29
*/
class Municipality
{
    private $combined;
    private $municipalities;
    private $baranggays;

    private $db;

    public function __construct()
    {
        require('../sql.php');
        $this->db = $link;
        //get all municipality data
        $query = "SELECT * FROM municipality WHERE is_deleted = 0";
        $result = $this->db->query($query);

        while ($row = $result->fetch_assoc()) {
            $this->combined[$row['municipality']][] = array($row['baranggay'], $row['wildcard'], $row['uid']);
            $this->municipalities[] = $row['municipality'];
            $this->baranggays[] = $row['baranggay'];
        }
    }

    public function getDeleted() {
        $query = "SELECT * FROM municipality WHERE is_deleted = 1";
        $result = $this->db->query($query);

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        return $data;
    }

    public function getAll() {
        return $this->combined;
    }

    public function create($data) {
        if (!($stmt = $this->db->prepare("INSERT INTO municipality VALUES(NULL, ?, ?, ?)"))) {
            echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
        }

        $stmt->bind_param('sss', $data['municipality'], $data['baranggay'], $data['wildcard']);
           
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        session_start();
        $this->db->query("INSERT INTO `logs` VALUES(NULL, '" . $_SESSION['username'] . "', 'Create', 'municipality', " .  $this->db->insert_id. ", NOW(), 0)");

        header("Location: /municipality/view.php");
    }

    public function delete($id) {
        if (!($stmt = $this->db->prepare("UPDATE municipality SET is_deleted = 1 WHERE uid = ?"))) {
            echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
            return false;
        }

        $stmt->bind_param('i', $id);
           
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            return false;
        }

        session_start();
        $this->db->query("INSERT INTO `logs` VALUES(NULL, '" . $_SESSION['username'] . "', 'Delete', 'municipality', $id, NOW(), 0)");

        return $id;
    }

    public function restore($id) {
        if (!($stmt = $this->db->prepare("UPDATE municipality SET is_deleted = 0 WHERE uid = ?"))) {
            echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
            return false;
        }

        $stmt->bind_param('i', $id);
           
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            return false;
        }

        session_start();
        $this->db->query("INSERT INTO `logs` VALUES(NULL, '" . $_SESSION['username'] . "', 'Restore', 'municipality', $id, NOW(), 0)");

        return $id;
    }

    public function edit($data) {
        if (!($stmt = $this->db->prepare("UPDATE municipality SET municipality = ?, baranggay = ?, wildcard = ? WHERE uid = ?"))) {
            echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
            return false;
        }

        $stmt->bind_param('sssi', $data['municipality'], $data['baranggay'], $data['wildcard'], $data['id']);
           
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            return false;
        }

        session_start();
        $this->db->query("INSERT INTO `logs` VALUES(NULL, '" . $_SESSION['username'] . "', 'Modify', 'municipality', " . $data['id'] . ", NOW(), 0)");

        return array($data['municipality'], $data['baranggay'], $data['wildcard']);
    }
}

$municipality = new Municipality();
?>