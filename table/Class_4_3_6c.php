<?php 

/**
* 4.3-6c - Reason for Establishing Residence in Present Place per LGU
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 10. 02
*/
class Class_4_3_6c
{
    private $db;
    public $unclaimed;
    public $total;
    public $municipalities;
    public $fields;
    public $definition;
    public function __construct()
    {
        require('../sql.php');
        $this->db = $link;

        $query = "SELECT * FROM `survey` WHERE is_deleted = 0 AND hh_head LIKE '%[322]'";
        $result = $this->db->query($query);
        while ($row = $result->fetch_assoc()) {
            $this->unclaimed[$row['uid']] = $row['uid'];
        }

        $this->definition['econ'] = 'Economic Reason';
        $this->definition['socio'] = 'Social Reason';
        $this->definition['other'] = 'Other Reason';
        $this->definition['noans'] = 'No Answer';
           
    }

    public function getData()
    {
        $data = [];

        $this->municipalities = $municipalities = $this->getMunicipality();
        
        $result = $this->db->query($query = "SELECT uid,type,`use`,address,baranggay,hdi_reason_econ,hdi_reason_social,hdi_reason_other FROM survey WHERE is_deleted = 0 AND `hh_head` LIKE '%[322]'");

        foreach ($municipalities as $key => $value) $data[$key] = array('econ' => [], 'socio' => [], 'other' => [], 'noans' => [], 'Total' => array('Total' => []));
        $data['Total'] = array('econ' => [], 'socio' => [], 'other' => [], 'noans' => [], 'Total' => array('Total' => []));

        $fields = array('econ' => [], 'socio' => [], 'other' => [], 'noans' => []);
        $fields['noans'] = array('No Answer' => []);
        while ($row = $result->fetch_assoc()) {
            $econ = explode(',', $row['hdi_reason_econ']);
            $socio = explode(',', $row['hdi_reason_social']);
            $other = explode(',', $row['hdi_reason_other']);

            if (trim($econ[0]) != '') {
                foreach ($econ as $cat) {
                    $cat = strtoupper(trim($cat));
                    foreach ($data as $key => $value) $data[$key]['econ'][$cat] = [];
                    foreach ($data as $key => $value) $fields['econ'][$cat] = [];
                }
            }
            if (trim($socio[0]) != '') {
                foreach ($socio as $cat) {
                    $cat = strtoupper(trim($cat));
                    foreach ($data as $key => $value) $data[$key]['socio'][$cat] = [];
                    foreach ($data as $key => $value) $fields['socio'][$cat] = [];
                }
            }
            if (trim($other[0]) != '') {
                foreach ($other as $cat) {
                    $cat = strtoupper(trim($cat));
                    foreach ($data as $key => $value) $data[$key]['other'][$cat] = [];
                    foreach ($data as $key => $value) $fields['other'][$cat] = [];
                }
            }
        }
        foreach ($data as $key => $value) $data[$key]['noans']['No Answer'] = [];
        foreach ($data as $key => $value) $data[$key]['Total']['Total'] = [];
        
        $this->fields = $fields;
        mysqli_data_seek($result, 0);
        while ($row = $result->fetch_assoc()) {
            $added = false;
            $econ = explode(',', $row['hdi_reason_econ']);
            $socio = explode(',', $row['hdi_reason_social']);
            $other = explode(',', $row['hdi_reason_other']);
            
            $mun = '';

            foreach ($municipalities as $key => $value) {
                if (strpos($row['address'], $key) != FALSE) {
                    $mun = $key;
                }
            }

            if (trim($econ[0]) != '') {
                foreach ($econ as $cat) {
                    $added = true;
                    $cat = strtoupper(trim($cat));
                    $data[$mun]['econ'][$cat][] = $row['uid'];
                    $data[$mun]['Total']['Total'][] = $row['uid'];
                    
                    $data['Total']['econ'][$cat][] = $row['uid'];
                    $data['Total']['Total']['Total'][] = $row['uid'];
                       
                }
            }

            if (trim($socio[0]) != '') {
                foreach ($socio as $cat) {
                    $added = true;
                    $cat = strtoupper(trim($cat));
                    $data[$mun]['socio'][$cat][] = $row['uid'];
                    $data[$mun]['Total']['Total'][] = $row['uid'];

                    $data['Total']['socio'][$cat][] = $row['uid'];
                    $data['Total']['Total']['Total'][] = $row['uid'];
                }
            }

            if (trim($other[0]) != '') {
                foreach ($other as $cat) {
                    $added = true;
                    $cat = strtoupper(trim($cat));
                    $data[$mun]['other'][$cat][] = $row['uid'];
                    $data[$mun]['Total']['Total'][] = $row['uid'];

                    $data['Total']['other'][$cat][] = $row['uid'];
                    $data['Total']['Total']['Total'][] = $row['uid'];
                }
            }


            if (trim($econ[0]) == '' && trim($socio[0]) == '' && trim($other[0]) == '') {
                $added = true;
                $cat = 'No Answer';
                $data[$mun]['noans']['No Answer'][] = $row['uid'];
                $data[$mun]['Total']['Total'][] = $row['uid'];

                $data['Total']['noans']['No Answer'][] = $row['uid'];
                $data['Total']['Total']['Total'][] = $row['uid'];
            }

            if ($added === true) {
                unset($this->unclaimed[$row['uid']]);
            }
        }

        if (isset($this->total['CIBE']) === FALSE) $this->total['CIBE'] = [];
        if (isset($this->total['ISF']) === FALSE) $this->total['ISF'] = [];
        if (isset($this->total['Commercial']) === FALSE) $this->total['Commercial'] = [];
        
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