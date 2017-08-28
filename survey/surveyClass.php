<?php 

/**
* class for managing survey table
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 08. 12
*/
class Survey
{
    private $db;

    public function __construct()
    {
        require('../sql.php');
        $this->db = $link;
    }

    public function getAll() {
        return false;
    }

    public function create($data) {
        $data = $data['data'];
        unset($data['uid']);
        $rows = [];
        $vals = [];
        $fields = '';
        foreach ($data as $key => $val) {
            $rows[] = "?";
            $vals[] = $val;
            $fields = $fields . 's';
        }
        if (!($stmt = $this->db->prepare("INSERT INTO survey VALUES(NULL, " . implode(", ", $rows) . ")"))) {
            echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
        }

        $stmt->bind_param($fields, ...$vals);
           
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        session_start();
        $this->db->query("INSERT INTO `logs` VALUES(NULL, '" . $_SESSION['username'] . "', 'Create', 'survey', " . $this->db->insert_id . ", NOW(), 0)");

        return array_merge(array('uid' => $this->db->insert_id), $data);
    }

    public function delete($id) {
        if (!($stmt = $this->db->prepare("UPDATE survey SET is_deleted = 1 WHERE uid = ?"))) {
            echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
            return false;
        }

        $stmt->bind_param('i', $id);
           
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            return false;
        }

        session_start();
        $this->db->query("INSERT INTO `logs` VALUES(NULL, '" . $_SESSION['username'] . "', 'Delete', 'survey', $id, NOW(), 0)");

        return $id;
    }

    public function edit($data) {
        $data = $data['data'];
        $update = [];
        $types = 's';
        $vals = [];

        foreach ($data as $key => $val) {
            $types = $types . 's';
            $vals[] = $val;
            $update[] = '`' . $key  . "` = ?";
        }
        $vals[] = $data['uid'];

        if (!($stmt = $this->db->prepare("UPDATE survey SET " . implode(", ", $update) . " WHERE uid = ?"))) {
            echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
            return false;
        }

        $stmt->bind_param($types, ...$vals);
           
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            return false;
        }

        $lastval = array_pop($data);
        $data = array_merge(array('uid' => $lastval), $data);

        session_start();
        $this->db->query("INSERT INTO `logs` VALUES(NULL, '" . $_SESSION['username'] . "', 'Modify', 'survey', " . $data['uid'] . ", NOW(), 0)");

        return $data;
    }

    public function restore($id) {
        if (!($stmt = $this->db->prepare("UPDATE survey SET is_deleted = 0 WHERE uid = ?"))) {
            echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
            return false;
        }

        $stmt->bind_param('i', $id);
           
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            return false;
        }

        session_start();
        $this->db->query("INSERT INTO `logs` VALUES(NULL, '" . $_SESSION['username'] . "', 'Restore', 'survey', $id, NOW(), 0)");

        return $id;
    }
}
?>