<?php 

/**
* 12.1-6 Estimated Cost of ISF Structures based on RCS
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 09. 25
*/
class Class_12_1_6
{
    private $db;
    public $unclaimed;
    public $tbl_cols;
    public $total;

    public function __construct()
    {
        require('../sql.php');
        $this->db = $link;

        $query = "SELECT * FROM `survey` WHERE is_deleted = 0 AND `type` = 'ISF'";
        $result = $this->db->query($query);
        while ($row = $result->fetch_assoc()) {
            $this->unclaimed[$row['uid']] = $row['uid'];
        }
    }

    public function getData()
    {
        $excess = [];
        $data = [];
        $columns = $this->getMunicipality();
        $this->tbl_cols = $tbl_cols = array("Margin_light_no", "Margin_light_area", "Margin_light_cost", "Margin_semi_no", "Margin_semi_area", "Margin_semi_cost", "Margin_concrete_no", "Margin_concrete_area", "Margin_concrete_cost", "Margin_total_no", "Margin_total_area", "Margin_total_cost", "Severe_light_no", "Severe_light_area", "Severe_light_cost", "Severe_semi_no", "Severe_semi_area", "Severe_semi_cost", "Severe_concrete_no", "Severe_concrete_area", "Severe_concrete_cost", "Severe_total_no", "Severe_total_area", "Severe_total_cost", "Total");

        foreach ($tbl_cols as $col) {
            $col_total[$col] = 0;
        }

        $translation = array(
            "light" => "Light Material",
            "semi" => "Semi-concrete",
            "concrete" => "Concrete",
        );

        foreach ($columns as $mun => $brgys) {


            foreach ($tbl_cols as $col) {
                $data[$mun][$col] = 0;
            }
            $query = "SELECT * FROM survey WHERE is_deleted = 0 AND `type` = 'ISF' AND `address` LIKE '%" . $mun . "%'";
            if ($mun == "Valenzuela") $query =  $query . " AND NOT `address` LIKE '%(Depot)%'";
            $result = $this->db->query($query);
            while ($row = $result->fetch_assoc()) {
                $make = "";

                if (trim(strtoupper($row['make'])) == "CONCRETE") $make = "concrete";
                if (trim(strtoupper($row['make'])) == "LIGHT MATERIALS") $make = "light";
                if (trim(strtoupper($row['make'])) == "PERMANENT") $make = "concrete";
                if (trim(strtoupper($row['make'])) == "PEMANENT") $make = "concrete";
                if (trim(strtoupper($row['make'])) == "SEMI-CONCRETE") $make = "semi";
                if (trim(strtoupper($row['make'])) == "SEMI-PERMANENT") $make = "semi";
                if (trim(strtoupper($row['make'])) == "WAREHOUSSE TYPE") $make = "semi";
                if (trim(strtoupper($row['make'])) == "WOOD") $make = "light";
                if (trim(strtoupper($row['make'])) == "WOOD W/LIGHT MATERIALS") $make = "light";

                $area = floatval($row['dms_affected']);
                $extent = strtoupper(trim($row['viable']));
                $affect = "";
                
                if ($extent == "VIABLE") $affect = "Margin";
                if ($extent == "VAIBLE") $affect = "Margin";
                if ($extent == "NON-VIABLE") $affect = "Severe";
                if ($extent == "NONVIABLE") $affect = "Severe";

                if ($affect != "" && $make =="") {
                    $excess[] = $row['uid'];
                }

                if ($affect != "" && $make != "") {
                    unset($this->unclaimed[$row['uid']]);

                    $sql = "SELECT * FROM material_cost WHERE structure_type = '" . $translation[$make] . "'";
                    $cost = $this->db->query($sql)->fetch_assoc();

                    $mult = 0;
                    if ($mun == "Manila") {
                        $mult = floatval($cost['manila']);
                    } elseif ($mun == "Valenzuela" || $mun == "Valenzuela (Depot)") {
                        $mult = floatval($cost['valenzuela']);
                    } else {
                        $mult = floatval($cost['bulacan']);
                    }
                    
                    $data[$mun][$affect . "_" . $make . "_no"]++;
                    $data[$mun][$affect . "_" . $make . "_area"]+= $area;
                    $data[$mun][$affect . "_" . $make . "_cost"]+= ($area * $mult);
                    $data[$mun]["Total"]+= ($area * $mult);

                    $data[$mun][$affect . "_total_no"]++;
                    $data[$mun][$affect . "_total_area"]+= $area;
                    $data[$mun][$affect . "_total_cost"]+= ($area * $mult);

                    $col_total[$affect . "_" . $make . "_no"]++;
                    $col_total[$affect . "_" . $make . "_area"]+= $area;
                    $col_total[$affect . "_" . $make . "_cost"]+= ($area * $mult);
                    
                    $col_total[$affect . "_total_no"]++;
                    $col_total[$affect . "_total_area"]+= $area;
                    $col_total[$affect . "_total_cost"]+= ($area * $mult);
                    $col_total["Total"]+= ($area * $mult);
                       
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