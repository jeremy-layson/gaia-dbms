<?php

class tableManager
{
    private $db;
    public $table_name;
    public $columns;
    public $data;
    public $deleted;
    public $index;
    public $page_name;

    private $redir_list;
    private $page_names;

    public $municipality;
    public $materials;
    public $structureCost;
    public $improvements;

    public $trees;

    public function __construct($table_name)
    {
        $this->redir_list = array(
            'market_value'  => '12_1_2.php',
            'survey'        => '12_1_2.php',
            'constant'      => 'constant.php',
            'material_cost' => '12_1_4.php',
              
        );

        $this->page_names = array(
            'market_value'  => 'Table 12.1-2 Market Values for the Project Affected Lands (PhP/sq.m)',
            'survey'        => 'Survey Table',
            'constant'      => 'Other Contants',
            'material_cost' => 'Construction Cost by Material',
        );

        require('../sql.php');
        $this->db = $link;
        $this->table_name = $table_name;
        $this->index = $this->redir_list[$table_name];
        $this->page_name = $this->page_names[$table_name];

        $this->loadColumns();
        $this->getCurrent();
        $this->getDeleted();
        $this->getMunicipality();
        $this->getStructureCost();
    }

    public function loadColumns()
    {
        $query = "show full columns from " . $this->table_name;
        $result = $this->db->query($query);
        while ($row = $result->fetch_assoc()) {
            $comment = explode("|", $row['Comment']); //0-show / 1-Name
            $this->columns[$row['Field']] = array($comment[0], $comment[1]);
        }
    }

    public function getCurrent()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE is_deleted = 0";
        $result = $this->db->query($query);

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $this->data = $data;
    }

    public function getDeleted()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE is_deleted = 1";
        $result = $this->db->query($query);

        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $this->deleted = $data;
    }

    public function edit($data) {
        $update = [];
        $types = 's';
        $vals = [];

        $tmpID = $data['uid'];
        unset($data['uid']);

        foreach ($data as $key => $val) {
            $types = $types . 's';
            $vals[] = $val;
            $update[] = '`' . $key  . "` = ?";
        }

        $vals[] = $tmpID;

        if (!($stmt = $this->db->prepare("UPDATE " . $this->table_name ." SET " . implode(", ", $update) . " WHERE uid = ?"))) {
            echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
            return false;
        }

        $stmt->bind_param($types, ...$vals);
           
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            return false;
        }

        // header("Location: " . $this->index);
    }

    public function create($data) {
        unset($data['uid']);
        $rows = [];
        $vals = [];
        $fields = '';
        $columns = [];
        foreach ($data as $key => $val) {
            $rows[] = "?";
            $vals[] = $val;
            $fields = $fields . 's';
            $columns[] = "`" . $key . "`";
        }

        $columns = implode(",", $columns);
        if (!($stmt = $this->db->prepare("INSERT INTO " . $this->table_name . "($columns) VALUES(" . implode(", ", $rows) . ")"))) {
            echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
        }

        $stmt->bind_param($fields, ...$vals);
           
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
        }

        header("Location: " . $this->index);
    }

    public function delete($id) {
        if (!($stmt = $this->db->prepare("UPDATE " . $this->table_name . " SET is_deleted = 1 WHERE uid = ?"))) {
            echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
            return false;
        }

        $stmt->bind_param('i', $id);
           
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            return false;
        }

        header("Location: " . $this->index);
    }

    public function restore($id) {
        if (!($stmt = $this->db->prepare("UPDATE " . $this->table_name . " SET is_deleted = 0 WHERE uid = ?"))) {
            echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
            return false;
        }

        $stmt->bind_param('i', $id);
           
        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            return false;
        }

        header("Location: " . $this->index);
    }

    public function table_12_1_2()
    {
        $data = [];
        foreach ($this->data as $dKey => $dValue) {
            $data[$dValue['municipality']][$dValue['category']] = number_format($dValue['cost'], 1);
        }

        return $data;
    }

    public function table_12_1_3()
    {
        $data = [];
        foreach ($this->data as $dKey => $dValue) {
            $value = 1000.00;
            $data[$dValue['municipality']][$dValue['category']]['area'] = number_format($value, 1);
            $data[$dValue['municipality']][$dValue['category']]['multiplier'] = number_format(($value * floatval($dValue['cost'])), 1);
        }

        return $data;
    }

    public function table_12_1_4()
    {
        return $this->data;
    }

    public function getMunicipality()
    {
        $query = "SELECT * FROM municipality WHERE is_deleted = 0";
        $result = $this->db->query($query);

        while ($row = $result->fetch_assoc()) {
            $data[$row['municipality']] = $row;
        }

        $this->municipality = $data;
    }
    public function getStructureCost()
    {
        $data = [];

        $this->materials = $materials = array('light', 'semi', 'concrete');
        foreach ($this->municipality as $key => $value) {
            foreach ($materials as $material) {
                $data[$key][$material]['Legal']['area'] = 0;
                $data[$key][$material]['Legal']['cost'] = 0;
                $data[$key][$material]['ISF']['area'] = 0;
                $data[$key][$material]['ISF']['cost'] = 0;
            }
        }

        foreach ($materials as $material) {
            $data['Total'][$material]['Legal']['area'] = 1;
            $data['Total'][$material]['Legal']['cost'] = 1;
            $data['Total'][$material]['ISF']['area'] = 1;
            $data['Total'][$material]['ISF']['cost'] = 1;
        }

        $this->structureCost = $data;
    }

    public function table_structure()
    {
        $data = $this->structureCost;

        return $data;
    }

    public function table_12_1_9()
    {
        $this->improvements = $improvements = array('fence', 'gate', 'others');

        $data = [];

        foreach ($this->municipality as $key => $value) {
            foreach ($improvements as $improvement) {
                $data[$key][$improvement]['area'] = 0;
                $data[$key][$improvement]['cost'] = 0;
            }
        }
        foreach ($improvements as $improvement) {
            $data['Total'][$improvement]['area'] = 1;
            $data['Total'][$improvement]['cost'] = 1;
        }

        return $data;
    }

    public function table_12_1_10()
    {
        $this->trees = $trees = array('fruit', 'nonfruit', 'crops');

        $data = [];

        foreach ($this->municipality as $key => $value) {
            foreach ($trees as $tree) {
                $data[$key][$tree]['area'] = 0;
                $data[$key][$tree]['cost'] = 0;
            }
        }
        foreach ($trees as $tree) {
            $data['Total'][$tree]['area'] = 1;
            $data['Total'][$tree]['cost'] = 1;
        }

        return $data;
    }
}
