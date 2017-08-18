<?php 

/**
* 4.2-1 Affected Lands: Area (m2)
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 08. 18
*/
class Class_4_2_1
{
    private $db;
    public $unclaimed;

    public function __construct()
    {
        require('../sql.php');
        $this->db = $link;

        $query = "SELECT * FROM `survey`";
        $result = $this->db->query($query);
        while ($row = $result->fetch_assoc()) {
            $this->unclaimed[$row['uid']] = $row['uid'];
        }
    }

    public function getData()
    {
        $data = [];
        $columns = $this->getMunicipality();

        $col_total['Grand Total']['RESIDENTIAL'] = array('COUNT' => 0);
        $col_total['Grand Total']['COMMERCIAL'] = array('COUNT' => 0);
        $col_total['Grand Total']['AGRICULTURAL'] = array('COUNT' => 0);
        $col_total['Grand Total']['INDUSTRIAL'] = array('COUNT' => 0);
        $col_total['Grand Total']['INSTITUTIONAL'] = array('COUNT' => 0);
        $col_total['Grand Total']['MIXED USE'] = array('COUNT' => 0);
        $col_total['Grand Total']['Total'] = array('COUNT' => 0);

        foreach ($columns as $col) {
            $data[$col]['RESIDENTIAL'] = array('COUNT' => 0);
            $data[$col]['COMMERCIAL'] = array('COUNT' => 0);
            $data[$col]['AGRICULTURAL'] = array('COUNT' => 0);
            $data[$col]['INDUSTRIAL'] = array('COUNT' => 0);
            $data[$col]['INSTITUTIONAL'] = array('COUNT' => 0);
            $data[$col]['MIXED USE'] = array('COUNT' => 0);
            $data[$col]['Total'] = array('COUNT' => 0);
            
            
            $result = $this->db->query("SELECT uid,`use`,alo_affectedarea FROM survey WHERE `address` LIKE '%" . $col . "%'");
            while ($row = $result->fetch_assoc()) {
                unset($this->unclaimed[$row['uid']]);
                $data[$col][strtoupper($row['use'])]['COUNT'] += floatval($row['alo_affectedarea']);
                $col_total['Grand Total'][strtoupper($row['use'])]['COUNT'] += floatval($row['alo_affectedarea']);
                $data[$col]['Total']['COUNT'] += floatval($row['alo_affectedarea']);

                $data[$col][strtoupper($row['use'])][] = $row['uid'];
                $col_total['Grand Total'][strtoupper($row['use'])][] = $row['uid'];
                $data[$col]['Total'][] = $row['uid'];
                
            }
        }

        return array_merge($data, $col_total);
    }

    private function getMunicipality()
    {
        $query = "SELECT municipality FROM municipality GROUP BY municipality";
        $result = $this->db->query($query);
        while ($row = $result->fetch_assoc()) {
            $data[] = $row['municipality'];
        }
        return $data;
    }

}