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
        if (!($stmt = $this->db->prepare("INSERT INTO municipality VALUES(NULL, ?, ?, ?)"))) {
            echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
        }

        $stmt->bind_param('sss', $data['municipality'], $data['baranggay'], $data['wildcard']);
           
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        header("Location: /municipality/view.php");
    }

    public function delete($id) {
        if (!($stmt = $this->db->prepare("DELETE FROM survey WHERE uid = ?"))) {
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

        return $data;
    }
}
?>