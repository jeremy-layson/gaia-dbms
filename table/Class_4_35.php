<?php 

/**
* 4.35 Primary Occupation
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 10. 11
*/
class Class_4_35
{
    private $db;
    public $unclaimed;
    public $tbl_cols;
    public $total;

    public function __construct()
    {
        require('../sql.php');
        $this->db = $link;

        $query = "SELECT * FROM `survey` WHERE is_deleted = 0 AND hh_head LIKE '%[322]'";
        $result = $this->db->query($query);
        while ($row = $result->fetch_assoc()) {
            $this->unclaimed[$row['uid']] = $row['uid'];
        }
    }

    public function getData()
    {
        $data = [];
        $columns = $this->getMunicipality();
        $this->tbl_cols = $tbl_cols = array('husband', 'wife', 'member', 'Total');

        foreach ($tbl_cols as $col) {
            $col_total[$col] = array('COUNT' => 0);
        }

        $query = "SELECT * FROM survey WHERE is_deleted = 0 AND hh_head LIKE '%[322]'";
        $result = $this->db->query($query);

        while ($row = $result->fetch_assoc()) {
            $asset = $row['asset_num'];

            //get hh
            $query = "SELECT * FROM hh_names WHERE asset_main = '" . $asset . "'";
            $hh = $this->db->query($query)->fetch_all(MYSQLI_ASSOC);

            foreach ($hh as $key => $value) {
                $pos = strtoupper(trim($value['hh_position']));
                $job = strtolower(trim($value['hh_job']));
                $col = $job;

                if ($pos == "HUSBAND") {
                    $pos = "husband";
                } elseif ($pos == "WIFE") {
                    $pos = "wife";
                } else {
                    $pos = "member";
                }

                if ($job != "") {
                    if (isset($data[$col][$pos]) === FALSE) {
                        $data[$col][$pos] = array('COUNT' => 0);
                    }
                    if (isset($data[$col]['Total']) === FALSE) {
                        $data[$col]['Total'] = array('COUNT' => 0);
                    }

                    unset($this->unclaimed[$row['uid']]);
                    $data[$col][$pos][] = $row['uid'];
                    $data[$col][$pos]['COUNT']++;

                    $data[$col]['Total'][] = $row['uid'];
                    $data[$col]['Total']['COUNT']++;
                    
                    $col_total[$pos][] = $row['uid'];
                    $col_total[$pos]['COUNT']++;
                    $col_total['Total'][] = $row['uid'];
                    $col_total['Total']['COUNT']++;
                       
                }
            }
               
        }

        $this->total = $col_total;
        return $data;
    }

    private function getMunicipality()
    {
        $query = "SELECT * FROM municipality WHERE is_deleted = 0";
        $result = $this->db->query($query);
        while ($row = $result->fetch_assoc()) {
            $data[$row['municipality']][] = array($row['baranggay'], $row['wildcard']);
        }

        return $data;
    }

        //returns the wildcarded string
    private function getWildcard($wildcard) {
        $ret = '';
        $wc = explode(',', $wildcard);
        foreach ($wc as $card) {
            if (is_numeric(trim($card)) === TRUE) {
                $card = 'Barangay ' . trim($card);
            }
            $ret = $ret . " baranggay = '" . trim($card) . "' OR";
        }

        return ' (' . substr($ret, 0, -3) . ')';
    }

}