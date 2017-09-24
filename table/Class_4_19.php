<?php 

/**
* 4.19 Number of Affected Improvements per LGU
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 09 . 17
*/
class Class_4_19
{
    private $db;
    public $unclaimed;
    public $tbl_cols;
    public $definition;

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
        $data = [];
        $columns = $this->getMunicipality();
        unset($columns['Valenzuela (Depot)']);

        $this->tbl_cols = $tbl_cols = array('improve_fence','improve_gate','improve_post','improve_well', 'improve_watertank','improve_pigpen', 'improve_chicken','improve_bcourt', 'improve_bridge','improve_terminal','improve_shed','improve_storage','improve_toilet','improve_extension','improve_garage','improve_fishpond','improve_playground','improve_parking','improve_sarisari');

        $this->definition = array(
            'improve_fence'     => 'Fences, Gates',
            'improve_gate'      => '',
            'improve_post'      => 'Posts',
            'improve_well'      => 'Water well, water tank',
            'improve_watertank' => '',
            'improve_pigpen'    => 'Pig pen, chicken cage',
            'improve_chicken'   => '',
            'improve_bcourt'    => 'Basketball Court',
            'improve_bridge'    => 'Pedestrian, transport terminal, waiting shed, storage area',
            'improve_terminal'  => '',
            'improve_shed'      => '',
            'improve_storage'   => '',
            'improve_toilet'    => 'Toilet and bath',
            'improve_extension' => 'House extension, garage',
            'improve_garage'    => '',
            'improve_fishpond'  => 'Fish pond',
            'improve_playground' => 'Playground',
            'improve_parking'   => 'Parking lot',
            'improve_sarisari'  => 'Sari-sari Store'

        );

        $mix = array(
            array('improve_fence', 'improve_gate'),
            array('improve_well', 'improve_watertank'),
            array('improve_pigpen', 'improve_chicken'),
            array('improve_bridge', 'improve_terminal', 'improve_shed', 'improve_storage'),
            array('improve_extension', 'improve_garage'),
        );

        foreach ($tbl_cols as $key => $value) $col_total['Grand Total'][$value] = 0;
        $col_total['Grand Total']['total'] = 0;

        foreach ($columns as $col) {
            foreach ($tbl_cols as $key => $value) $data[$col][$value] = 0;
            $data[$col]['total'] = 0;
            
            $result = $this->db->query("SELECT * FROM survey WHERE is_deleted = 0 AND `address` LIKE '%" . $col . "%'");
            while ($row = $result->fetch_assoc()) {
                unset($this->unclaimed[$row['uid']]);
                
                foreach ($tbl_cols as $key => $value) {
                    $data[$col][$value] += boolval($row[$value]);
                    $data[$col]['total'] += boolval($row[$value]);
                    
                    $col_total['Grand Total'][$value] += boolval($row[$value]);
                    $col_total['Grand Total']['total'] += boolval($row[$value]);
                }
            }
        }

        //mix them all
        foreach ($mix as $entry) {
            for ($i=1; $i <= count($entry)-1; $i++) { 
                foreach ($data as $key => $value) {
                    $data[$key][$entry[0]] += $data[$key][$entry[$i]];
                    unset($data[$key][$entry[$i]]);
                }

                $col_total['Grand Total'][$entry[0]] += $col_total['Grand Total'][$entry[$i]];
                unset($col_total['Grand Total'][$entry[$i]]);

                for ($j=0; $j <= (count($tbl_cols)-1);$j++) {
                    if (trim($tbl_cols[$j]) == trim($entry[$i])) {
                        unset($tbl_cols[$j]);
                        $tbl_cols = array_values($tbl_cols);
                        break;
                    }
                }
            }
        }
        $this->tbl_cols = $tbl_cols;

        return array_merge($data, $col_total);
    }

    private function getMunicipality()
    {
        $query = "SELECT municipality FROM municipality WHERE is_deleted = 0 GROUP BY municipality ORDER BY uid ASC";
        $result = $this->db->query($query);
        while ($row = $result->fetch_assoc()) {
            $data[$row['municipality']] = $row['municipality'];
        }
        return $data;
    }

}