<?php 
ini_set('memory_limit', '1024M');
ini_set('max_execution_time', 0);
set_time_limit(0);
//  Include PHPExcel_IOFactory
include '../Classes/PHPExcel/IOFactory.php';



class Importer
{
    private $inputFileName; // 'data.xlsx';
    private $phpExcel;
    private $inputFileType;
    private $phpWriter;
    private $columns; 
    private $db;

    public function __construct($fileName)
    {

        require_once('../sql.php');
        $this->db = $link;
        $this->inputFileName = $fileName;
        $inputFileName = $fileName;
        $this->loadColumn('survey');
        //  Read Excel workbook
        try {
            $this->inputFileType = $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
            $objReader = PHPExcel_IOFactory::createReader($inputFileType);
            $this->phpExcel = $objReader->load($inputFileName);

            
        } catch(Exception $e) {
            die('Error loading file "'.pathinfo($inputFileName,PATHINFO_BASENAME).'": '.$e->getMessage());
        }
    }

    /**
     * get all worksheets in the excel file
     * 
     * @return Array
     */
    public function getWorkSheets()
    {
        $workSheets = [];

        //loop through worksheets then get names
        for ($i=0; $i < $this->phpExcel->getSheetCount()-1; $i++) {
            $currentSheet = $this->phpExcel->setActiveSheetIndex($i);
            $workSheets[] = array($currentSheet->getTitle(), $i);
        }

        return $workSheets;
    }

    private function loadColumn($col)
    {
        if ($col == 'survey') {
            $this->columns = array(
                'A' => array('type', 'Type'), // filter ISF or Legal
                'B' => array('unique_asset', 'Unique Asset Number'),
                'C' => array('asset_num', 'Asset Number'), //Asset Number 
                'D' => array('name', 'DMS Respondent'), //Name of DMS Respondent 
                'E' => array('address', 'Address'), //ADDRESS 
                'H' => array('baranggay', 'Baranggay'),
                'T' => array('family_head', 'Family Head'),
                'W' => array('family_head_gender', 'Family Head Gender'),
                'Z' => array('civil_status', 'Civil Status'),

                //B. Household demographic info
                'AF' => array('hdi_length_stay', 'Length of Stay'), //length of stay
                'AG' => array('hdi_reason_econ', 'Reason for moving: Economic'),
                'AH' => array('hdi_reason_social', 'Reason for moving: Social'),
                'AI' => array('hdi_reason_other', 'Reason for moving: Other'),

                //C. Affected Land Occupants
                'AK' => array('ownership', 'C. Ownership'), //Ownership
                'AL' => array('use', 'C. Use (Actual)'), //Land use (actual)
                'AM' => array('use_structure', 'C. Use (Structure use)'), //Based on Structure use
                
                'AN' => array('owner', 'C. Owner'), //Owner - Affected Lands
                'AP' => array('dp_type', 'C. Type of DP'), //Type of DP  - Affected Lands
                'AQ' => array('alo_total_area', 'C. Total Area'), //Total area
                'AR' => array('alo_affectedarea', 'C. Affected Area'), //area affected
                'AT' => array('alo_extent', 'C. Extent of Impact'),

                //D. Main Structure Occupant
                'BQ' => array('structure_type', 'D. Type'), //type (Main structure occupant)
                'BR' => array('structure_owner', 'D. Structure Owner'), //Structure owner (Main structure occupant)
                'BS' => array('structure_use', 'D. Use'), //Use (Main structure occupant)
                'BT' => array('structure_dp', 'D. Type of DP'), //Type of DP (Main structure occupant)
                'BV' => array('dms_total_area', 'D. Floor Area'), //floor area
                'BW' => array('dms_affected', 'D. Affected Area'), //affected area
                'BY' => array('extent', 'D. Extent of Impact'), //extent of impact

                'CQ' => array('improve_fence', 'D1A. Fence'),
                'CS' => array('improve_gate', 'D1B. Gate'),
                'CU' => array('improve_post', 'D1C. Post'),
                'DC' => array('improve_well', 'D1D. Well'),
                'DL' => array('improve_pigpen', 'D1E. Pig Pen'),
                'ED' => array('improve_bcourt', 'D1G. Basketball Court'),
                'EM' => array('improve_bridge', 'D1H. Pedestrian/Bridge Pathway/Overpass'),
                'ES' => array('improve_terminal', 'D1I. Transport Terminal'),
                'EY' => array('improve_shed', 'D1J. Waiting Shed'),
                'FG' => array('improve_storage', 'D1K. Storage Area/Stock Room'),
                'FN' => array('improve_toilet', 'D1L. Comfort Room/Toilet and Bath'),
                'FV' => array('improve_watertank', 'D1M. Water Tank'),
                'GD' => array('improve_extension', 'D1N. House Extension'),
                'GL' => array('improve_fishpond', 'D1O. Fish Pond'),
                'GT' => array('improve_garage', 'D1P. Garage'),
                'HB' => array('improve_sarisari', 'D1Q. Sari-sari Store'),
                'HJ' => array('improve_playground', 'D1R. Playground'),
                'HR' => array('improve_table', 'D1S. Playground'),
                'HZ' => array('improve_parking', 'D1T. Parking Lot'),
                

                //D2. Residential Structure Arrangements
                'JR' => array('displacement', 'D2. Displacement'), //Displacement (Residential Structure Arrangements)

                //D4. Relocation Package Option
                'KI' => array('rpo_relocation_option', 'D4. What option'),
                'KJ' => array('rpo_relocation_preferred' ,'D4. Preferred Province'),
                'KL' => array('rpo_reloc_1stprio', 'D4. 1st Priority'),

                //In choosing relocation site, what are the most important factors you will consider?
                'KQ' => array('rpo_reloc_factor_near_orig', 'Relocation Factor: Near to Original Residence'), 
                'KR' => array('rpo_reloc_factor_livelihood', 'Relocation Factor: Near Sources of Livelihood'),
                'KS' => array('rpo_reloc_factor_health_school', 'Relocation Factor: Near Health and School Facilities'),
                'KT' => array('rpo_reloc_factor_market_access', 'Relocation Factor: Accessible to markets'),
                'KU' => array('rpo_reloc_factor_transport_access', 'Relocation Factor: Accessible transporation'),
                'KV' => array('rpo_reloc_factor_4ps_benefit', 'Relocation Factor: Still Acquire 4Ps Benefits'),
                'KW' => array('rpo_reloc_factor_others', 'Relocation Factor: Others'),

                //Most desired basic services/facilities in reloc site. 

                'KZ' => array('rpo_desired_service_health_center', 'Desired Service: Health Center'),
                'LA' => array('rpo_desired_service_private_clinic', 'Desired Service: Private Clinic'),
                'LB' => array('rpo_desired_service_gov_hospital', 'Desired Service: Govt Hospital'),
                'LC' => array('rpo_desired_service_police_outpost', 'Desired Service: Police Outpost'),
                'LD' => array('rpo_desired_service_livelihood', 'Desired Service: Livelihood Center'),
                'LE' => array('rpo_desired_service_market', 'Desired Service: Market'),
                'LF' => array('rpo_desired_service_school', 'Desired Service: School'),
                'LG' => array('rpo_desired_service_brgy_hall', 'Desired Service: Baranggay Hall'),
                'LH' => array('rpo_desired_service_transport', 'Desired Service: Transporation'),
                'LI' => array('rpo_desired_service_others', 'Desired Service: Others'),
                
                

                //I. Socio-Economic Survey
                'NK' => array('hh_members', 'Household Members'), // //Household members (Socio-economic Survey)
                'NL' => array('hh_head', 'Household Head'), //Households Head (Track color) [322] tag

                'OP' => array('hh_unemployed', 'Unemployed HH Members'),

                //I2. Source of Households Income
                'PR' => array('shi_source_employee', 'I2. Source of Income: Employee'), //Source of income
                'PS' => array('shi_source_fbusiness', 'I2. Source of Income: Formal Business'),
                'PT' => array('shi_source_informal', 'I2. Source of Income: Informal Income'),
                'PU' => array('shi_employ_permanent', 'I2. Employment Status: Permanent'), //Employment Status
                'PV' => array('shi_employ_contract', 'I2. Employment Status: Contractual'),
                'PW' => array('shi_employ_extra', 'I2. Employment Status: Extra'),
                
                'QL' => array('shi_total_hh_income', 'I2. Total Household Income'), //Total HH Income  
                'PY' => array('shi_place_employment', 'I2. Address of Employment'), //Address/place of employment of business              
                'QH' => array('shi_total_transpo', 'I2. Total Transporation Expenses'), //total transportation expenses

                //cohorts
                'NY' => array('ses_05_male', 'Cohorts 0-5 Male'),
                'NZ' => array('ses_05_female', 'Cohorts 0-5 Female'),
                'OA' => array('ses_614_male', 'Cohorts 6-14 Male'),
                'OB' => array('ses_614_female', 'Cohorts 6-14 Female'),
                'OC' => array('ses_1530_male', 'Cohorts 15-30 Male'),
                'OD' => array('ses_1530_female', 'Cohorts 15-30 Female'),
                'OE' => array('ses_3159_male', 'Cohorts 31-59 Male'),
                'OF' => array('ses_3159_female', 'Cohorts 31-59 Female'),
                'OG' => array('ses_60_male', 'Cohorts 60 Above Male'),
                'OH' => array('ses_60_female', 'Cohorts 60 Above Female'),
                'OI' => array('ses_other_male', 'Cohorts Others Male'),
                'OJ' => array('ses_other_female', 'Cohorts Others Female'),
                'OK' => array('ses_total_male', 'Cohorts Total Male' ),   //cohorts male total
                'OL' => array('ses_total_female', 'Cohorts Total Female'), //cohorts female total

                //education
                'OQ' => array('ses_ed_none_male', 'Education None Male'),
                'OR' => array('ses_ed_none_female', 'Education None Female'),
                'OS' => array('ses_ed_pre_male', 'Education Pre-school Male'),
                'OT' => array('ses_ed_pre_female', 'Education Pre-school Female'),
                'OU' => array('ses_ed_elem_male', 'Education Elementary Male'),
                'OV' => array('ses_ed_elem_female', 'Education Elementary Female'),
                'OW' => array('ses_ed_elemgrad_male', 'Education Elementary Graduate Male'),
                'OX' => array('ses_ed_elemgrad_female', 'Education Elementary Graduate Female'),
                'OY' => array('ses_ed_hs_male', 'Education Highschool Male'),
                'OZ' => array('ses_ed_hs_female', 'Education Highschool Female'),
                'PA' => array('ses_ed_hsgrad_male', 'Education Highschool Graduate Male'),
                'PB' => array('ses_ed_hsgrad_female', 'Education Highschool Graduate Female'),
                'PC' => array('ses_ed_college_male', 'Education College Male'),
                'PD' => array('ses_ed_college_female', 'Education College Female'),
                'PE' => array('ses_ed_collegegrad_male', 'Education College Graduate Male'),
                'PF' => array('ses_ed_collegegrad_female', 'Education College Graduate Female'),
                'PG' => array('ses_ed_voc_male', 'Education Vocational Male'),
                'PH' => array('ses_ed_voc_female', 'Education Vocational Female'),
                'PI' => array('ses_ed_vocgrad_male', 'Education Vocational Graduate Male'),
                'PJ' => array('ses_ed_vocgrad_female', 'Education Vocational Graduate Female'),
                'PK' => array('ses_ed_notage_male', 'Education Not in Age Male'),
                'PL' => array('ses_ed_notage_female', 'Education Not in Age Female'),
                'PM' => array('ses_ed_other_male', 'Education Other Male'),
                'PN' => array('ses_ed_other_female', 'Education Other Female'),
                
                //I3. Household Expenses
                'RD' => array('he_total_expenses', 'I3. Total Monthly Expenses'), //monhtly expenses

                'RE' => array('he_source_light', 'I3. Source of lighting'),
                'RF' => array('he_fuel_cooking', 'I3. Fuel for cooking'),
                'RG' => array('he_water_supply', 'I3. Water Supply'),
                'RH' => array('he_sanitation', 'I3. Sanitation Facility (Toilet)'),

                //K. Household Assets
                'RI' => array('ha_tricycle', 'K. Tricycle'),
                'RJ' => array('ha_motorcycle', 'K. Motorcyle'),
                'RK' => array('ha_computer', 'K. Computer'),
                'RL' => array('ha_electricfan', 'K. Electric Fan'),
                'RM' => array('ha_tv', 'K. Television'),
                'RN' => array('ha_radio', 'K. Radio'),
                'RO' => array('ha_music', 'K. Music Component'),
                'RP' => array('ha_amplifier', 'K. Amplifier'),
                'RQ' => array('ha_refrigerator', 'K. Refrigerator'),
                'RR' => array('ha_stove', 'K. Stove'),
                'RS' => array('ha_superkalan', 'K. Superkalan'),
                'RT' => array('ha_dvd', 'K. Portable DVD'),
                'RU' => array('ha_car', 'K. Car'),
                'RV' => array('ha_gadget', 'K. Gadget'),
                'RW' => array('ha_bike', 'K. Trike/Bike'),
                'RX' => array('ha_ricecooker', 'K. Rice Cooker'),
                'RY' => array('ha_jeep', 'K. Jeepney'),
                'RZ' => array('ha_waterpurifier', 'K. Water Purifier'),
                'SA' => array('ha_aircon', 'K. Aircon'),
                'SB' => array('ha_washingmachine', 'K. Washing Machine'),
                'SC' => array('ha_sewingmachine', 'K. Sewing Machine'),



                //E. Crops and Trees
                'LS' => array('trees_fb', 'E. Fruit Bearing'), //fruit bearing
                'LT' => array('trees_nonfb', 'E. Non Fruit Bearing'), //timber / non-fb
                'LU' => array('trees_cash', 'E. Plants/Cashcrop'), //plants / cashcrop

                //F. Social Vulnerability
                'MC' => array('sv_10k', 'F. Less than 10K/Month'),           // < 10K/month
                'MD' => array('sv_hh_woman', 'F. Female household head'),      // HH Head is woman
                'ME' => array('sv_60above', 'F. Person > 60 yrs'),       // person > 60 yrs 
                'MF' => array('sv_special_assist', 'F. Special Assistance'),// Special assistance
                
                //L. Female Participation in Decision Making
                'SD' => array('fpdm_finance', 'L. Financial Matters'),
                'SE' => array('fpdm_education', 'L. Education of child'),
                'SF' => array('fpdm_health', 'L. Health care of child'),
                'SG' => array('fpdm_purchase', 'L. Purchase of Assets'),
                'SH' => array('fpdm_daytoday', 'L. Day to day activities'),
                'SI' => array('fpdm_social', 'L. Social functions and marriages'),
                'SJ' => array('fpdm_others', 'L. Others'),
                //M. Income and Livelihood Support Assitance

                //If ever you would lose your job because of the NSCR Project what sort of livelihood assistance would suit for your needs?
                'SK' => array('ialsa_livelihood_assistance', 'M. Livelihood Assistance'),

                //O. Flooding

                'VG' => array('flood_5years', 'Flooding in the last 5 years?'),
                'VH' => array('flood_deepest', 'When deepest flooding'),
                'VI' => array('flood_typhoon', 'Particular typhoon'),
                'VJ' => array('flood_times', 'How many times'),
                'VK' => array('flood_max_height', 'Maximum height'),
                'VL' => array('flood_location', 'Where is the location of flooding'),
                'VM' => array('flood_damage', 'How much damage caused by floood'),
                'VN' => array('flood_subside', 'How long to subside'),
                
            );
        }
    }

    public function createTableSurvey()
    {
        $columns = $this->columns;

        $query = "CREATE TABLE `survey` (`uid` int(8) NOT NULL COMMENT '0|ID|text' PRIMARY KEY AUTO_INCREMENT";

        foreach ($columns as $key => $value) {
            $query = $query . "," . "`" . $value[0] . "` text COMMENT '1|" . $value[1] . "|text'";
        }

        $query = $query . ", `is_deleted` int(11) NOT NULL DEFAULT '0' COMMENT '0|Deleted|text');";

        $this->db->query("DROP TABLE survey");       

        if (!($stmt = $this->db->prepare($query))) {
            echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
            return false;
        }


        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            return false;
        }


        $this->db->query("ALTER TABLE `survey` ADD PRIMARY KEY (`uid`);");
        $this->db->query("ALTER TABLE `survey` MODIFY `uid` int(8) NOT NULL AUTO_INCREMENT COMMENT '0|ID|text', AUTO_INCREMENT=10000;COMMIT;");
    }

    public function importData($maxRow = 831)
    {
        $sheetIndex = 0;

        while (true) {
            $worksheet = $this->phpExcel->setActiveSheetIndex($sheetIndex);
            $worksheetTitle = $worksheet->getTitle();

            if ($worksheetTitle == 'SurveyDATA') {
                break;
            }

            $sheetIndex++;
        }

        $worksheet = $this->phpExcel->setActiveSheetIndex($sheetIndex);
        $worksheetTitle = $worksheet->getTitle();
        $highestRow = $maxRow;

        $all = [];
        $showHidden = true;
        $verification = [];
        for ($i=5; $i <= $highestRow; $i++) {
            if ($worksheet->getRowDimension($i)->getVisible() || $showHidden ) {
                //get data
                $row = [];
                foreach ($this->columns as $key => $value) {
                    //flush cache
                    PHPExcel_Calculation::getInstance($this->phpExcel)->flushInstance();
                    $cellValue = $worksheet->getCell($key . $i)->getCalculatedValue();
                    $color = $worksheet->getStyle($key . $i)->getFill()->getStartColor()->getRGB();

                    //get only isf or legal
                    if ($key == 'A') {
                        if (strpos(strtoupper($cellValue), 'ISF') !== false) {
                            $data = 'ISF';
                        } else {
                            $data = 'LEGAL';
                        }
                    } else {
                        $data = trim($cellValue);
                    }

                    if (is_null($data)) $data = '';
                    if ($value[0] == 'hh_members' && ($data == '')) $data = '0';
                    if ($value[0] == 'alo_affectedarea' && ($data == '')) $data = '0';
                    if ($value[0] == 'alo_total_area' && ($data == '')) $data = '0';
                    if ($value[0] == 'trees_fb' && ($data == '')) $data = '0';
                    if ($value[0] == 'trees_nonfb' && ($data == '')) $data = '0';
                    if ($value[0] == 'trees_cash' && ($data == '')) $data = '0';
                    if ($value[0] == 'baranggay') $data = str_replace('Barangay', 'Baranggay', $data);

                    //Valenzuela Depot
                    if (($value[0] == 'address') && 
                        (strpos($data, 'Valenzuela') != FALSE) &&
                        (strpos($data, 'Malanday') != FALSE) && 
                        (strpos($data, '(Depot)') == FALSE)) {
                        $data = str_replace('Valenzuela', 'Valenzuela (Depot)', $data);
                    }

                    if ($value[0] == 'hdi_length_stay') {
                        $data = str_replace('rys', 'years', $data);
                        $data = str_replace('yrs', 'years', $data);
                        $data = trim($data) == 'More than 15' ? 'More than 15 years' : $data;
                    }

                    //misspelling

                    $data = str_replace('Velenzuela', 'Valenzuela', $data);
                    $data = str_replace('Meycauyan', 'Meycauayan', $data);

                    //hdi_reason_econ
                    if ($value[0] == 'hdi_reason_econ') {
                        $data = str_replace(' ti ', ' to ', $data);
                        $data = str_replace('Livelihod', 'livelihood', $data);
                        $data = str_replace('ivelihood', 'livelihood', $data);
                        $data = str_replace('ivellihood', 'livelihood', $data);
                        $data = str_replace('Llivelihood', 'livelihood', $data);
                        $data = str_replace('llivelihood', 'livelihood', $data);
                        
                        $data = str_replace('Rent fee', 'rental fee', $data);
                        $data = str_replace('Retal', 'rental', $data);
                        $data = str_replace('Affordable Rent free', 'Affordable rental fee', $data);
                        
                        $data = str_replace('Rent fee', 'Rent free', $data);
                        $data = str_replace('Rrent', 'rent', $data);
                    }

                    if ($value[0] == 'hdi_reason_other') {
                        // $data = str_replace('Verbal Agreement (Relative)', 'Verbal agreement', $data);
                        // $data = str_replace('Verbal agreement to be the caretaker for the fish', 'Verbal agreement', $data);
                        // $data = str_replace('Acquired rights/birth', 'Acquired rights', $data);
                        // $data = str_replace('Inherited', 'Inheritance/Residence Since Birth', $data);
                        // $data = str_replace('Since Birth', 'Inheritance/Residence Since Birth', $data);
                        // $data = str_replace('Born here', 'Inheritance/Residence Since Birth', $data);
                          
                    } 


                    //HH Head color conditional
                    if ($value[0] == 'hh_head' && $color == 'FFC1E0') $data = $data . ' [322]';


                    $row[$value[0]] = $data;
                }
                $all[] = $row;

                $verification[] = $row['asset_num'];

                $fields = '';
                $values = '';
                $vals = [];
                $stringdef = '';
                foreach ($row as $key => $val) {
                    $fields = $fields . ', `' . $key . '`';
                    $values = $values . ", ?";
                    $vals[] = $val;
                    $stringdef = $stringdef . 's';
                }
                if (!($stmt = $this->db->prepare("INSERT INTO survey (`uid` $fields) VALUES(NULL $values)"))) {
                    echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
                }

                $stmt->bind_param($stringdef, ...$vals);

                if (!$stmt->execute()) {
                    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                }

                
            }

        }

        echo "FINISHED";
    }

    public function addColumn()
    {
        $query = "ALTER TABLE `survey` ADD `unique_asset` VARCHAR(200) NOT NULL AFTER `type`";  
        $this->db->query($query);
    }

    public function export()
    {
        
        $sheetIndex = 0;

        while (true) {
            $worksheet = $this->phpExcel->setActiveSheetIndex($sheetIndex);
            $worksheetTitle = $worksheet->getTitle();

            if ($worksheetTitle == 'SurveyDATA') {
                break;
            }

            $sheetIndex++;
        }


        $worksheet = $this->phpExcel->setActiveSheetIndex($sheetIndex);
        $worksheetTitle = $worksheet->getTitle();

        $objWriter = PHPExcel_IOFactory::createWriter($this->phpExcel, $this->inputFileType);

        $query = "SELECT count(uid) as cnt FROM `survey`";
        $result = $this->db->query($query)->fetch_all(MYSQLI_ASSOC);

        $query = "SELECT * FROM `survey` ORDER by uid";
        $data = $this->db->query($query)->fetch_all(MYSQLI_ASSOC);

        $writer = $objWriter->getPHPExcel()->getActiveSheet();
        for ($i=5; $i <= (intval($result[0]['cnt']) + 4); $i++) {
            foreach ($this->columns as $key => $value) { //value[0]
                $cellValue = $writer->setCellValue($key . $i, utf8_encode($data[$i - 5][$value[0]]), true);
                echo "Cell $key$i | Value: " . $data[$i - 5][$value[0]] . " | Result: $cellValue" . "<br>";
            }
        }

        $objWriter->save($this->inputFileName);

    }
}

if (isset($_GET['function']) === true && $_GET['function'] != '') {
    $filename = '../data.xlsx';
    if (isset($_GET['filename']) === TRUE && $_GET['filename'] != '') {
        $filename = '../import/' . $_GET['filename'] . '.xlsx';
    }

    $func = $_GET['function'];
    $import = new Importer($filename);
    $import->$func();
} else {
    $maxRow = 831;
    $filename = '../data.xlsx';

    if (isset($_GET['max_row']) === true) {
        $maxRow = $_GET['max_row'];
    }

    if (isset($_GET['filename']) === TRUE && $_GET['filename'] != '') {
        $filename = '../import/' . $_GET['filename'] . '.xlsx';
    }

    $import = new Importer($filename);
    $import->createTableSurvey();
    $import->importData($maxRow);
}
