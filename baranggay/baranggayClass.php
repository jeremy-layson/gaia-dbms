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

        $this->total = array(
            'SO_R'      => array(0, 0),
            'SO_MU'     => array(0, 0),
            'SO_CIBE'   => array(0, 0),
            'SO_I'      => array(0, 0),
            'RR'        => array(0, 0),
            'Total'     => array(0, 0),
            'Excess'    => array()
        );

        $this->order = array('SO_R', 'SO_MU', 'SO_CIBE', 'SO_I', 'RR', 'Total');
    }

    //returns the wildcarded string
    public function getWildcard($wildcard) {
        $ret = '';
        $wc = explode(',', $wildcard);
        foreach ($wc as $card) {
            $ret = $ret . " address LIKE '%" . trim($card) . "%' OR";
        }

        return substr($ret, 0, -3);
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
}