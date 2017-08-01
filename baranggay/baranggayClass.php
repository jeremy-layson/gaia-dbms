<?php

/**
* baranggay class for both legal and illegal
* @author Jeremy Layson <jeremy.b.layson@gmail.com>
* @since 2017 . 07 . 30
*/
Abstract class Baranggay
{
    protected $db;
    protected $included;
    protected $municipalities;

    protected $total; 
    protected $order;

    protected $fields;
    protected $rawTotal;

    public function __construct()
    {
        //get all necessary data
        include('../../sql.php');
        $this->db = $link;

        $query = "SELECT * FROM municipality";
        $result = $this->db->query($query);

        while ($row = $result->fetch_assoc()) {
            $this->municipalities[$row['municipality']][] = array($row['baranggay'], $row['wildcard']);
        }

        $this->order = array('SO_R', 'SO_MU', 'SO_CIBE', 'SO_I', 'RR', 'Total');
    }

    //returns the wildcarded string
    public function getWildcard($wildcard) {
        $ret = '';
        $wc = explode(',', $wildcard);
        foreach ($wc as $card) {
            $ret = $ret . " address LIKE '%" . trim($card) . "%' OR";
        }

        return ' (' . substr($ret, 0, -3) . ')';
    }
    
    //pass extent here
    public function isStay($type) {
        $type = trim(strtoupper($type));
        if (strpos($type, 'CAN STAY') !== FALSE) {
            return 0; //add to stay
        } elseif ($type == 'NEED DISPLACEMENT') {
            return 1;
        } else {
            return -1;
        }
    }

    public function printUncategorized()
    {
        $this->fields = array(
            'ID'                => 'uid',
            'Asset #'           => 'asset_num',
            'Name'              => 'name',
            'Address'           => 'address',
            'Structure Type'    => 'structure_type',
            'Structure Owner'   => 'structure_owner',
            'Use'               => 'structure_use',
            'DP Type'           => 'structure_dp',
            'Displacement'      => 'displacement',
            'Reason'            => 'REASON',
        );
        echo '<table border="1" cellpadding="3" cellspacing="0">';
        echo '<thead>';
            echo '<tr>';
            foreach ($this->fields as $key => $val) {
                echo "<td>$key</td>";
            }
            echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
            foreach ($this->total['Excess'] as $excess) {
                $query = 'SELECT * FROM survey WHERE uid = ' . $excess[0];
                $result = $this->db->query($query);
                $data = $result->fetch_assoc();

                echo '<tr>';
                    foreach ($this->fields as $key => $val) {
                        if ($val != 'REASON') {
                            echo "<td>" . $data[$val] . "</td>";
                        }
                    }
                    echo "<td>" . $excess[1] . "</td>";
                echo '</tr>';
            }
            echo '<tr>';
                echo '<td colspan="' . count($this->fields) . '">Total Count: ' . count($this->total['Excess']) . '</td>';
            echo '</tr>';
        echo '</tbody>';
        echo '</table>';
    }

    public function printUnincluded($type)
    {
        echo '<thead>';
            echo '<tr>';
            foreach ($this->fields as $key => $val) {
                echo "<td>$key</td>";
            }
            echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
            $query = 'SELECT * FROM survey WHERE `type` = "' . $type . '" AND `uid` NOT IN (' . implode(', ', $this->included) . ')';
            $result = $this->db->query($query);
            while ($data = $result->fetch_assoc()) {
                echo '<tr>';
                foreach ($this->fields as $key => $val) {
                    if ($val != 'REASON') {
                        echo "<td>" . $data[$val] . "</td>";
                    }
                }
                echo "<td>Invalid Address</td>";
                echo '</tr>';
            }
                echo '<td colspan="' . count($this->fields) . '">Total Count: ' . $result->num_rows . '</td>';
            echo '</tr>';
        echo '</tbody>';
    }
}