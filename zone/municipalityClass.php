<?php 

/**
* class for managing municipality (with zones) table
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 08. 17
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
        $query = "SELECT * FROM municipality_zone";
        $result = $this->db->query($query);

        while ($row = $result->fetch_assoc()) {
            $this->combined[$row['municipality']][] = array($row['baranggay'], $row['wildcard'], $row['uid']);
            $this->municipalities[] = $row['municipality'];
            $this->baranggays[] = $row['baranggay'];
        }
    }

    public function getAll() {
        return $this->combined;
    }

    public function create($data) {
        if (!($stmt = $this->db->prepare("INSERT INTO municipality_zone VALUES(NULL, ?, ?, ?)"))) {
            echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
        }

        $stmt->bind_param('sss', $data['municipality'], $data['baranggay'], $data['wildcard']);
           
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        header("Location: /zone/view.php");
    }

    public function delete($id) {
        if (!($stmt = $this->db->prepare("DELETE FROM municipality_zone WHERE uid = ?"))) {
            echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
            return false;
        }

        $stmt->bind_param('i', $id);
           
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            return false;
        }

        return $id;
    }

    public function edit($data) {
        if (!($stmt = $this->db->prepare("UPDATE municipality_zone SET municipality = ?, baranggay = ?, wildcard = ? WHERE uid = ?"))) {
            echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
            return false;
        }

        $stmt->bind_param('sssi', $data['municipality'], $data['baranggay'], $data['wildcard'], $data['id']);
           
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            return false;
        }

        return array($data['municipality'], $data['baranggay'], $data['wildcard']);
    }
}

$municipality = new Municipality();
?>