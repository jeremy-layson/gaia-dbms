<?php 

/**
* 4.3-6 - Reason for Establishing Residence in Present Place
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 08. 18
*/
class Class_4_3_6
{
    private $db;
    public $unclaimed;
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
        
        $result = $this->db->query($query = "SELECT uid,type,`use`,address,baranggay,hdi_reason_econ,hdi_reason_social,hdi_reason_other FROM survey WHERE is_deleted = 0 AND `hh_head` LIKE '%[322]'");

        $data['econ'] = [];
        $data['socio'] = [];
        $data['other'] = [];

        while ($row = $result->fetch_assoc()) {
            $added = false;
            $econ = explode(',', $row['hdi_reason_econ']);
            $socio = explode(',', $row['hdi_reason_social']);
            $other = explode(',', $row['hdi_reason_other']);
            
            $use = $row['use'];
            $type = $row['type'];

            if ($use == 'Institutional') $use = 'CIBE';
            if ($use == 'Industrial') $use = 'CIBE';
            if ($use == 'Mixed Use') $use = 'CIBE';
            if ($use == 'Commercial') $use = 'CIBE';
            
            if ($type == 'ISF') $use = 'ISF';

            if (trim($econ[0]) != '') {
                foreach ($econ as $cat) {
                    $added = true;
                    $cat = strtoupper(trim($cat));
                    $data['econ'][$cat][$use][] = $row['uid'];
                    $data['econ'][$cat]['Total'][] = $row['uid'];
                }
            }

            if (trim($socio[0]) != '') {
                foreach ($socio as $cat) {
                    $added = true;
                    $cat = strtoupper(trim($cat));
                    $data['socio'][$cat][$use][] = $row['uid'];
                    $data['socio'][$cat]['Total'][] = $row['uid'];
                }
            }

            if (trim($other[0]) != '') {
                foreach ($other as $cat) {
                    $added = true;
                    $cat = strtoupper(trim($cat));
                    $data['other'][$cat][$use][] = $row['uid'];
                    $data['other'][$cat]['Total'][] = $row['uid'];
                }
            }

            if ($added === true) {
                unset($this->unclaimed[$row['uid']]);
                $this->total[$use][]    = $row['uid'];
                $this->total['Total'][]   = $row['uid'];
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