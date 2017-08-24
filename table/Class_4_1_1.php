<?php 

/**
* 4.1-1 Affected Cities and Municipalities and COrresponding Baranggays by NSCR Project
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 08. 17
*/
class Class_4_1_1
{
    private $db;
    public $unclaimed;

    public function __construct()
    {
        require('../sql.php');
        $this->db = $link;

        $query = "SELECT * FROM `survey` WHERE is_deleted = 0";
        $result = $this->db->query($query);
        while ($row = $result->fetch_assoc()) {
            $this->unclaimed[$row['uid']] = $row['uid'];
        } 
    }

    public function getData()
    {
        $query = "SELECT * FROM municipality_zone";
        $result = $this->db->query($query);

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $query = "SELECT uid FROM survey WHERE is_deleted = 0 AND (" . $this->getWildcard($row['wildcard']) . ") AND address like '%" . $row['municipality'] . "%'";
            $id_list = $this->db->query($query);
            $ids = [];
            while ($id = $id_list->fetch_assoc()) {
                $ids[] = $id['uid'];
                unset($this->unclaimed[$id['uid']]);
            }
            $data[$row['municipality']][] = array($row['baranggay'], $ids);
            
        }

        return $data;
    }

    //returns the wildcarded string
    public function getWildcard($wildcard) {
        $ret = '';
        $wc = explode(',', $wildcard);
        foreach ($wc as $card) {
            if (is_numeric(trim($card)) === TRUE) {
                $card = 'Baranggay ' . trim($card);
            }
            $ret = $ret . " baranggay = '" . trim($card) . "' OR";
        }

        return ' (' . substr($ret, 0, -3) . ')';
    }


}