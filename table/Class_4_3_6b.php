<?php 

/**
* 4.3-6b - Reason for Establishing Residence in Present Place per LGU
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 09. 13
*/
class Class_4_3_6b
{
    private $db;
    public $unclaimed;
    public $total;
    public $municipalities;

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

        $this->municipalities = $municipalities = $this->getMunicipality();
        
        $result = $this->db->query($query = "SELECT uid,type,`use`,address,baranggay,hdi_reason_econ,hdi_reason_social,hdi_reason_other FROM survey WHERE is_deleted = 0 AND `hh_head` LIKE '%[322]'");

        $data['econ'] = [];
        $data['socio'] = [];
        $data['other'] = [];
        $data['noans'] = [];


        while ($row = $result->fetch_assoc()) {
            $added = false;
            $econ = explode(',', $row['hdi_reason_econ']);
            $socio = explode(',', $row['hdi_reason_social']);
            $other = explode(',', $row['hdi_reason_other']);
            
            $use = 
            
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
                    $data['econ'][$cat][$mun][] = $row['uid'];
                    $data['econ'][$cat]['Total'][] = $row['uid'];
                    $this->total[$mun][]    = $row['uid'];
                    $this->total['Total'][]   = $row['uid'];
                }
            }

            if (trim($socio[0]) != '') {
                foreach ($socio as $cat) {
                    $added = true;
                    $cat = strtoupper(trim($cat));
                    $data['socio'][$cat][$mun][] = $row['uid'];
                    $data['socio'][$cat]['Total'][] = $row['uid'];
                    $this->total[$mun][]    = $row['uid'];
                    $this->total['Total'][]   = $row['uid'];
                }
            }

            if (trim($other[0]) != '') {
                foreach ($other as $cat) {
                    $added = true;
                    $cat = strtoupper(trim($cat));
                    $data['other'][$cat][$mun][] = $row['uid'];
                    $data['other'][$cat]['Total'][] = $row['uid'];
                    $this->total[$mun][]    = $row['uid'];
                    $this->total['Total'][]   = $row['uid'];
                }
            }


            if (trim($econ[0]) == '' && trim($socio[0]) == '' && trim($other[0]) == '') {
                $added = true;
                $cat = strtoupper(trim($cat));
                $data['noans']['No Answer'][$mun][] = $row['uid'];
                $data['noans']['No Answer']['Total'][] = $row['uid'];
                $this->total[$mun][]    = $row['uid'];
                $this->total['Total'][]   = $row['uid'];
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