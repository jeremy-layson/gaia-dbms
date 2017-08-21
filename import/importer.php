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
            $inputFileType = PHPExcel_IOFactory::identify($inputFileName);
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
                'C' => array('asset_num', 'Asset Number'), //Asset Number 
                'D' => array('name', 'DMS Respondent'), //Name of DMS Respondent 
                'E' => array('address', 'Address'), //ADDRESS 
                'H' => array('baranggay', 'Baranggay'),
                'S' => array('family_head', 'Family Head'),
                'V' => array('family_head_gender', 'Family Head Gender'),

                //B. Household demographic info
                'AE' => array('hdi_length_stay', 'Length of Stay'), //length of stay
                'AF' => array('hdi_reason_econ', 'Reason for moving: Economic'),
                'AG' => array('hdi_reason_social', 'Reason for moving: Social'),
                'AH' => array('hdi_reason_other', 'Reason for moving: Other'),

                //C. Affected Land Occupants
                'AJ' => array('ownership', 'C. Ownership'), //Ownership
                'AK' => array('use', 'C. Use'), //USE (AH) - Affected Lands
                'AL' => array('owner', 'C. Owner'), //Owner - Affected Lands
                'AN' => array('dp_type', 'C. Type of DP'), //Type of DP  - Affected Lands
                'AO' => array('alo_total_area', 'C. Total Area'), //Total area
                'AP' => array('alo_affectedarea', 'C. Affected Area'), //area affected
                'AR' => array('alo_extent', 'C. Extent of Impact'),

                //D. Main Structure Occupant
                'BN' => array('structure_type', 'D. Type'), //type (Main structure occupant)
                'BO' => array('structure_owner', 'D. Structure Owner'), //Structure owner (Main structure occupant)
                'BP' => array('structure_use', 'D. Use'), //Use (Main structure occupant)
                'BQ' => array('structure_dp', 'D. Type of DP'), //Type of DP (Main structure occupant)
                'BS' => array('dms_total_area', 'D. Floor Area'), //floor area
                'BT' => array('dms_affected', 'D. Affected Area'), //affected area
                'BV' => array('extent', 'D. Extent of Impact'), //extent of impact

                //D2. Residential Structure Arrangements
                'FU' => array('displacement', 'D2. Displacement'), //Displacement (Residential Structure Arrangements)

                //D4. Relocation Package Option
                'GL' => array('rpo_relocation_option', 'D4. What option'),

                //In choosing relocation site, what are the most important factors you will consider?
                'GT' => array('rpo_reloc_factor_near_orig', 'Relocation Factor: Near to Original Residence'), 
                'GU' => array('rpo_reloc_factor_livelihood', 'Relocation Factor: Near Sources of Livelihood'),
                'GV' => array('rpo_reloc_factor_health_school', 'Relocation Factor: Near Health and School Facilities'),
                'GW' => array('rpo_reloc_factor_market_access', 'Relocation Factor: Accessible to markets'),
                'GX' => array('rpo_reloc_factor_transport_access', 'Relocation Factor: Accessible transporation'),
                'GY' => array('rpo_reloc_factor_4ps_benefit', 'Relocation Factor: Still Acquire 4Ps Benefits'),
                'GZ' => array('rpo_reloc_factor_others', 'Relocation Factor: Others'),

                //Most desired basic services/facilities in reloc site. 

                'HC' => array('rpo_desired_service_health_center', 'Desired Service: Health Center'),
                'HD' => array('rpo_desired_service_private_clinic', 'Desired Service: Private Clinic'),
                'HE' => array('rpo_desired_service_gov_hospital', 'Desired Service: Govt Hospital'),
                'HF' => array('rpo_desired_service_police_outpost', 'Desired Service: Police Outpost'),
                'HG' => array('rpo_desired_service_livelihood', 'Desired Service: Livelihood Center'),
                'HH' => array('rpo_desired_service_market', 'Desired Service: Market'),
                'HI' => array('rpo_desired_service_school', 'Desired Service: School'),
                'HJ' => array('rpo_desired_service_brgy_hall', 'Desired Service: Baranggay Hall'),
                'HK' => array('rpo_desired_service_transport', 'Desired Service: Transporation'),
                'HL' => array('rpo_desired_service_others', 'Desired Service: Others'),
                
                

                //I. Socio-Economic Survey
                'JN' => array('hh_members', 'Household Members'), // //Household members (Socio-economic Survey)
                'JO' => array('hh_head', 'Household Head'), //Households Head (Track color) [322] tag

                //I2. Source of Households Income
                'LU' => array('shi_source_employee', 'I2. Source of Income: Employee'), //Source of income
                'LV' => array('shi_source_fbusiness', 'I2. Source of Income: Formal Business'),
                'LW' => array('shi_source_informal', 'I2. Source of Income: Informal Income'),
                'LY' => array('shi_employ_permanent', 'I2. Employment Status: Permanent'), //Employment Status
                'LZ' => array('shi_employ_contract', 'I2. Employment Status: Contractual'),
                'MA' => array('shi_employ_extra', 'I2. Employment Status: Extra'),
                'MK' => array('shi_total_hh_income', 'I2. Total Household Income'), //Total HH Income  
                'MC' => array('shi_place_employment', 'I2. Address of Employment'), //Address/place of employment of business              
                'MG' => array('shi_total_transpo', 'I2. Total Transporation Expenses'), //total transportation expenses

                //cohorts
                'KB' => array('ses_05_male', 'Cohorts 0-5 Male'),
                'KC' => array('ses_05_female', 'Cohorts 0-5 Female'),
                'KD' => array('ses_614_male', 'Cohorts 6-14 Male'),
                'KE' => array('ses_614_female', 'Cohorts 6-14 Female'),
                'KF' => array('ses_1530_male', 'Cohorts 15-30 Male'),
                'KG' => array('ses_1530_female', 'Cohorts 15-30 Female'),
                'KH' => array('ses_3159_male', 'Cohorts 31-59 Male'),
                'KI' => array('ses_3159_female', 'Cohorts 31-59 Female'),
                'KJ' => array('ses_60_male', 'Cohorts 60 Above Male'),
                'KK' => array('ses_60_female', 'Cohorts 60 Above Female'),
                'KL' => array('ses_other_male', 'Cohorts Others Male'),
                'KM' => array('ses_other_female', 'Cohorts Others Female'),
                'KN' => array('ses_total_male', 'Cohorts Total Male' ),   //cohorts male total
                'KO' => array('ses_total_female', 'Cohorts Total Female'), //cohorts female total

                //education
                'KT' => array('ses_ed_none_male', 'Education None Male'),
                'KU' => array('ses_ed_none_female', 'Education None Female'),
                'KV' => array('ses_ed_pre_male', 'Education Pre-school Male'),
                'KW' => array('ses_ed_pre_female', 'Education Pre-school Female'),
                'KX' => array('ses_ed_elem_male', 'Education Elementary Male'),
                'KY' => array('ses_ed_elem_female', 'Education Elementary Female'),
                'KZ' => array('ses_ed_elemgrad_male', 'Education Elementary Graduate Male'),
                'LA' => array('ses_ed_elemgrad_female', 'Education Elementary Graduate Female'),
                'LB' => array('ses_ed_hs_male', 'Education Highschool Male'),
                'LC' => array('ses_ed_hs_female', 'Education Highschool Female'),
                'LD' => array('ses_ed_hsgrad_male', 'Education Highschool Graduate Male'),
                'LE' => array('ses_ed_hsgrad_female', 'Education Highschool Graduate Female'),
                'LF' => array('ses_ed_college_male', 'Education College Male'),
                'LG' => array('ses_ed_college_female', 'Education College Female'),
                'LH' => array('ses_ed_collegegrad_male', 'Education College Graduate Male'),
                'LI' => array('ses_ed_collegegrad_female', 'Education College Graduate Female'),
                'LJ' => array('ses_ed_voc_male', 'Education Vocational Male'),
                'LK' => array('ses_ed_voc_female', 'Education Vocational Female'),
                'LL' => array('ses_ed_vocgrad_male', 'Education Vocational Graduate Male'),
                'LM' => array('ses_ed_vocgrad_female', 'Education Vocational Graduate Female'),
                'LN' => array('ses_ed_notage_male', 'Education Not in Age Male'),
                'LO' => array('ses_ed_notage_female', 'Education Not in Age Female'),
                'LP' => array('ses_ed_other_male', 'Education Other Male'),
                'LQ' => array('ses_ed_other_female', 'Education Other Female'),
                
                //I3. Household Expenses
                'NC' => array('he_total_expenses', 'I3. Total Monthly Expenses'), //monhtly expenses
                
                //E. Crops and Trees
                'HV' => array('trees_fb', 'E. Fruit Bearing'), //fruit bearing
                'HW' => array('trees_nonfb', 'E. Non Fruit Bearing'), //timber / non-fb
                'HX' => array('trees_cash', 'E. Plants/Cashcrop'), //plants / cashcrop

                //F. Social Vulnerability
                'IF' => array('sv_10k', 'F. Less than 10K/Month'),           // < 10K/month
                'IG' => array('sv_hh_woman', 'F. Female household head'),      // HH Head is woman
                'IH' => array('sv_60above', 'F. Person > 60 yrs'),       // person > 60 yrs 
                'II' => array('sv_special_assist', 'F. Special Assistance'),// Special assistance

                //M. Income and Livelihood Support Assitance

                //If ever you would lose your job because of the NSCR Project what sort of livelihood assistance would suit for your needs?
                'OJ' => array('ialsa_livelihood_assistance', 'M. Livelihood Assistance'),
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

        // echo $query;
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

    public function importData()
    {
        $worksheet = $this->phpExcel->setActiveSheetIndex(13);
        $worksheetTitle = $worksheet->getTitle();
        $highestRow = 831;

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
                        $data = $cellValue;
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
                        (strpos($data, 'Malanday') != FALSE)) {
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
                    if ($value[0] == 'hh_head' && $color == 'FFB3D9') $data = $data . ' [322]';


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
    }
}

$import = new Importer('../data.xlsx');
$import->createTableSurvey();
$import->importData();