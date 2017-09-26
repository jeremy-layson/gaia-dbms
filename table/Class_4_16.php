<?php 

/**
* 4.16 Number of Affected Structures in Valenzuela Depot
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 09 . 17
*/
class Class_4_16
{
    private $db;
    public $unclaimed;

    public function __construct()
    {
        require('../sql.php');
        $this->db = $link;

        $query = "SELECT * FROM `survey` WHERE is_deleted = 0 AND `address` LIKE '%(Depot)%'";
        $result = $this->db->query($query);
        while ($row = $result->fetch_assoc()) {
            $this->unclaimed[$row['uid']] = $row['uid'];
        }
    }

    public function getData()
    {
        $data = [];
        $columns = array('Valenzuela (Depot)');

        $col_total['Grand Total']['RESIDENTIAL'] = array('COUNT' => 0);
        $col_total['Grand Total']['COMMERCIAL'] = array('COUNT' => 0);
        $col_total['Grand Total']['INDUSTRIAL'] = array('COUNT' => 0);
        $col_total['Grand Total']['INSTITUTIONAL'] = array('COUNT' => 0);
        $col_total['Grand Total']['MIXED USE'] = array('COUNT' => 0);
        $col_total['Grand Total']['Total'] = array('COUNT' => 0);

        foreach ($columns as $col) {
            $data[$col]['RESIDENTIAL'] = array('COUNT' => 0);
            $data[$col]['COMMERCIAL'] = array('COUNT' => 0);
            $data[$col]['INDUSTRIAL'] = array('COUNT' => 0);
            $data[$col]['INSTITUTIONAL'] = array('COUNT' => 0);
            $data[$col]['MIXED USE'] = array('COUNT' => 0);
            $data[$col]['Total'] = array('COUNT' => 0);
            
            $result = $this->db->query("SELECT uid,`structure_use` as `use`,extent FROM survey WHERE is_deleted = 0 AND `address` LIKE '%" . $col . "%'");
            while ($row = $result->fetch_assoc()) {
                    
                if ($row['use'] == 'Residential' || $row['use'] == 'Commercial' || $row['use'] == 'Industrial' || $row['use'] == 'Institutional' || $row['use'] == 'Mixed use' || $row['use'] == 'Mixed Use') {
                    unset($this->unclaimed[$row['uid']]);
                    $data[$col][strtoupper($row['use'])]['COUNT']++;
                    $data[$col]['Total']['COUNT']++;
                    $col_total['Grand Total'][strtoupper($row['use'])]['COUNT']++;
                    $col_total['Grand Total']['Total']['COUNT']++;

                    $data[$col][strtoupper($row['use'])][] = $row['uid'];
                    $data[$col]['Total'][] = $row['uid'];
                    $col_total['Grand Total'][strtoupper($row['use'])][] = $row['uid'];
                    $col_total['Grand Total']['Total'][] = $row['uid'];
                    
                }
            }
        }

        return array_merge($data, $col_total);
    }

    private function getMunicipality()
    {
        $query = "SELECT municipality FROM municipality WHERE is_deleted = 0 GROUP BY municipality ORDER BY uid ASC";
        $result = $this->db->query($query);
        while ($row = $result->fetch_assoc()) {
            $data[] = $row['municipality'];
        }
        return $data;
    }

}