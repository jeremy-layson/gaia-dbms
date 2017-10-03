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
    public $tbl;

    public function __construct($fileName, $tbl)
    {
        $this->tbl = $tbl;
        require_once('../sql.php');
        $this->db = $link;
        $this->inputFileName = $fileName;
        $inputFileName = $fileName;
        $this->loadColumn($tbl);
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
        if ($col == 'hh_names') {
            $this->columns = array(
                'C' => array('asset_num', 'Asset Number'),
                'D' => array('asset_891', '891'),
                'E' => array('asset_752', '752'),
                'G' => array('asset_num_2', 'Asset Number (Column G)'),
                'H' => array('hh_member', 'Name of HH Member'),
                'I' => array('age', 'Age'),
                'J' => array('gender', 'Gender'),
                'K' => array('civil', 'Civil Status'),
                'M' => array('education', 'Educational Attainment'),
                
                'O' => array('hh_job', 'Specific Jobs'),
                  
            );
        }

        if ($col == 'transmittal') {
            $this->columns = array(
                'A' => array('id', 'ID'),
                'B' => array('date', 'Date'),
                'C' => array('delivered', 'Delivered To'),
                'D' => array('careof', 'C/O'),
                'E' => array('contents', 'Contents'),
                'F' => array('project', 'Project Name'),
                'G' => array('consultant', 'Consultant Name'),
                'H' => array('msgr', 'Messenger Name'),
                'I' => array('address', 'Address'),
                'J' => array('contact', 'Contact Person(s)'),
                'K' => array('casehandler', 'Case Handler'),
                
            );
        }

        if ($col == 'survey') {
            $this->columns = array(
                'A' => array('type', 'Type'), // filter ISF or Legal
                'B' => array('unique_asset', 'Unique Asset Number'),
                'C' => array('asset_num', 'Asset Number'), //Asset Number 
                'F' => array('name', 'DMS Respondent'), //Name of DMS Respondent 
                'G' => array('address', 'Address'), //ADDRESS 
                'J' => array('baranggay', 'Baranggay'),
                'V' => array('family_head', 'Family Head'),
                'Y' => array('family_head_gender', 'Family Head Gender'),
                'AB' => array('civil_status', 'Civil Status'),

                'AF' => array('religion', 'Religion'),
                'AG' => array('ethnicity', 'Ethnicity'),
                
                //B. Household demographic info
                'AH' => array('hdi_length_stay', 'Length of Stay'), //length of stay
                'AI' => array('hdi_reason_econ', 'Reason for moving: Economic'),
                'AJ' => array('hdi_reason_social', 'Reason for moving: Social'),
                'AK' => array('hdi_reason_other', 'Reason for moving: Other'),

                //C. Affected Land Occupants
                'AM' => array('ownership', 'C. Ownership'), //Ownership
                'AN' => array('use', 'C. Use (Actual)'), //Land use (actual)
                'AO' => array('use_structure', 'C. Use (Structure use)'), //Based on Structure use
                
                'AP' => array('owner', 'C. Owner'), //Owner - Affected Lands
                'AR' => array('dp_type', 'C. Type of DP'), //Type of DP  - Affected Lands
                'AS' => array('alo_total_area', 'C. Total Area'), //Total area
                'AT' => array('alo_affectedarea', 'C. Affected Area'), //area affected
                'AV' => array('alo_extent', 'C. Extent of Impact'),

                'BA' => array('kd_document', 'C. Document'),
                'BB' => array('kd_title', 'C. Title'),
                'BC' => array('kd_real', 'C. Real Estate Tax'),
                'BD' => array('kd_deed', 'C. Deed/Mortgage'),
                'BE' => array('kd_landplan', 'C. Land Plan'),
                'BF' => array('kd_brgycert', 'C. Baranggay Residency Certificate'),
                'BG' => array('kd_landrights', 'C. Land Rights'),
                'BH' => array('kd_lease', 'C. Lease Contract'),
                'BI' => array('kd_certaward', 'C. Certificate of lot award'),

                'BL' => array('lr_realtax', 'C1. Real Estate Tax'),

                //D. Main Structure Occupant
                'BS' => array('structure_type', 'D. Type (Structure Ownership)'), //type (Main structure occupant)
                'BT' => array('structure_owner', 'D. Structure Owner'), //Structure owner (Main structure occupant)
                'BU' => array('structure_use', 'D. Use'), //Use (Main structure occupant)
                'BV' => array('structure_dp', 'D. Type of DP'), //Type of DP (Main structure occupant)
                'BX' => array('dms_total_area', 'D. Floor Area'), //floor area
                'BY' => array('dms_affected', 'D. Affected Area'), //affected area
                'CA' => array('extent', 'D. Extent of Impact'), //extent of impact
                'CB' => array('make', 'D. Type/Make'), //extent of impact
                
                'CS' => array('improve_fence', 'D1A. Fence'),
                'CU' => array('improve_gate', 'D1B. Gate'),
                'CW' => array('improve_post', 'D1C. Post'),
                'DE' => array('improve_well', 'D1D. Well'),
                'DN' => array('improve_pigpen', 'D1E. Pig Pen'),
                'DW' => array('improve_chicken', 'D1F. Chicken Cage'),
                'EF' => array('improve_bcourt', 'D1G. Basketball Court'),
                'EO' => array('improve_bridge', 'D1H. Pedestrian/Bridge Pathway/Overpass'),
                'EU' => array('improve_terminal', 'D1I. Transport Terminal'),
                'FA' => array('improve_shed', 'D1J. Waiting Shed'),
                'FI' => array('improve_storage', 'D1K. Storage Area/Stock Room'),
                'FP' => array('improve_toilet', 'D1L. Comfort Room/Toilet and Bath'),
                'FX' => array('improve_watertank', 'D1M. Water Tank'),
                'GF' => array('improve_extension', 'D1N. House Extension'),
                'GN' => array('improve_fishpond', 'D1O. Fish Pond'),
                'GV' => array('improve_garage', 'D1P. Garage'),
                'HD' => array('improve_sarisari', 'D1Q. Sari-sari Store'),
                'HL' => array('improve_playground', 'D1R. Playground'),
                'HT' => array('improve_table', 'D1S. Concrete Table'),
                'IB' => array('improve_parking', 'D1T. Parking Lot'),
                

                //D2. Residential Structure Arrangements
                'JT' => array('displacement', 'D2. Displacement'), //Displacement (Residential Structure Arrangements)
                'JS' => array('viable', 'D2. Structural Viability'), //Displacement (Residential Structure Arrangements)
                'JP' => array('dsa_rent_cost', 'D2. Rental'),

                //D4. Relocation Package Option
                'KK' => array('rpo_relocation_option', 'D4. What option'),
                'KL' => array('rpo_relocation_preferred' ,'D4. Preferred Province'),
                'KN' => array('rpo_reloc_1stprio', 'D4. 1st Priority'),

                //In choosing relocation site, what are the most important factors you will consider?
                'KS' => array('rpo_reloc_factor_near_orig', 'Relocation Factor: Near to Original Residence'), 
                'KT' => array('rpo_reloc_factor_livelihood', 'Relocation Factor: Near Sources of Livelihood'),
                'KU' => array('rpo_reloc_factor_health_school', 'Relocation Factor: Near Health and School Facilities'),
                'KV' => array('rpo_reloc_factor_market_access', 'Relocation Factor: Accessible to markets'),
                'KW' => array('rpo_reloc_factor_transport_access', 'Relocation Factor: Accessible transporation'),
                'KX' => array('rpo_reloc_factor_4ps_benefit', 'Relocation Factor: Still Acquire 4Ps Benefits'),
                'KY' => array('rpo_reloc_factor_others', 'Relocation Factor: Others'),

                //Most desired basic services/facilities in reloc site. 

                'LB' => array('rpo_desired_service_health_center', 'Desired Service: Health Center'),
                'LC' => array('rpo_desired_service_private_clinic', 'Desired Service: Private Clinic'),
                'LD' => array('rpo_desired_service_gov_hospital', 'Desired Service: Govt Hospital'),
                'LE' => array('rpo_desired_service_police_outpost', 'Desired Service: Police Outpost'),
                'LF' => array('rpo_desired_service_livelihood', 'Desired Service: Livelihood Center'),
                'LG' => array('rpo_desired_service_market', 'Desired Service: Market'),
                'LH' => array('rpo_desired_service_school', 'Desired Service: School'),
                'LI' => array('rpo_desired_service_brgy_hall', 'Desired Service: Baranggay Hall'),
                'LJ' => array('rpo_desired_service_transport', 'Desired Service: Transporation'),
                'LK' => array('rpo_desired_service_others', 'Desired Service: Others'),

                'LL' => array('reloc_exp', 'D5. Previous Relocation Experience'),
                'LM' => array('reloc_site', 'D5. Relocation Site'),
                

                //E. Crops and Trees
                'LU' => array('trees_fb', 'E. Fruit Bearing'), //fruit bearing
                'LV' => array('trees_nonfb', 'E. Non Fruit Bearing'), //timber / non-fb
                'LW' => array('trees_cash', 'E. Plants/Cashcrop'), //plants / cashcrop

                //F. Social Vulnerability
                'ME' => array('sv_10k', 'F. Less than 10K/Month'),           // < 10K/month
                'MF' => array('sv_hh_woman', 'F. Female household head'),      // HH Head is woman
                'MG' => array('sv_60above', 'F. Person > 60 yrs'),       // person > 60 yrs 
                'MH' => array('sv_special_assist', 'F. Special Assistance'),// Special assistance
                
                'ND' => array('cibe_structure', 'H. Classification'),
                //I. Socio-Economic Survey
                'NM' => array('hh_members', 'Household Members'), // //Household members (Socio-economic Survey)
                'NN' => array('hh_head', 'Household Head'), //Households Head (Track color) [322] tag


                //cohorts
                'OA' => array('ses_05_male', 'Cohorts 0-5 Male'),
                'OB' => array('ses_05_female', 'Cohorts 0-5 Female'),
                'OC' => array('ses_614_male', 'Cohorts 6-14 Male'),
                'OD' => array('ses_614_female', 'Cohorts 6-14 Female'),
                'OE' => array('ses_1530_male', 'Cohorts 15-30 Male'),
                'OF' => array('ses_1530_female', 'Cohorts 15-30 Female'),
                'OG' => array('ses_3159_male', 'Cohorts 31-59 Male'),
                'OH' => array('ses_3159_female', 'Cohorts 31-59 Female'),
                'OI' => array('ses_60_male', 'Cohorts 60 Above Male'),
                'OJ' => array('ses_60_female', 'Cohorts 60 Above Female'),
                'OK' => array('ses_other_male', 'Cohorts Others Male'),
                'OL' => array('ses_other_female', 'Cohorts Others Female'),
                'OM' => array('ses_total_male', 'Cohorts Total Male' ),   //cohorts male total
                'ON' => array('ses_total_female', 'Cohorts Total Female'), //cohorts female total

                'OR' => array('hh_unemployed', 'Unemployed HH Members'),

                //education
                'OS' => array('ses_ed_none_male', 'Education None Male'),
                'OT' => array('ses_ed_none_female', 'Education None Female'),
                'OU' => array('ses_ed_pre_male', 'Education Pre-school Male'),
                'OV' => array('ses_ed_pre_female', 'Education Pre-school Female'),
                'OW' => array('ses_ed_elem_male', 'Education Elementary Male'),
                'OX' => array('ses_ed_elem_female', 'Education Elementary Female'),
                'OY' => array('ses_ed_elemgrad_male', 'Education Elementary Graduate Male'),
                'OZ' => array('ses_ed_elemgrad_female', 'Education Elementary Graduate Female'),
                'PA' => array('ses_ed_hs_male', 'Education Highschool Male'),
                'PB' => array('ses_ed_hs_female', 'Education Highschool Female'),
                'PC' => array('ses_ed_hsgrad_male', 'Education Highschool Graduate Male'),
                'PD' => array('ses_ed_hsgrad_female', 'Education Highschool Graduate Female'),
                'PE' => array('ses_ed_college_male', 'Education College Male'),
                'PF' => array('ses_ed_college_female', 'Education College Female'),
                'PG' => array('ses_ed_collegegrad_male', 'Education College Graduate Male'),
                'PH' => array('ses_ed_collegegrad_female', 'Education College Graduate Female'),
                'PI' => array('ses_ed_voc_male', 'Education Vocational Male'),
                'PJ' => array('ses_ed_voc_female', 'Education Vocational Female'),
                'PK' => array('ses_ed_vocgrad_male', 'Education Vocational Graduate Male'),
                'PL' => array('ses_ed_vocgrad_female', 'Education Vocational Graduate Female'),
                'PM' => array('ses_ed_notage_male', 'Education Not in Age Male'),
                'PN' => array('ses_ed_notage_female', 'Education Not in Age Female'),
                'PO' => array('ses_ed_other_male', 'Education Other Male'),
                'PP' => array('ses_ed_other_female', 'Education Other Female'),

                
                //I2. Source of Households Income
                'PS' => array('shi_earning_member', 'I2. Earning Household Members'), //Source of income
                'PT' => array('shi_source_employee', 'I2. Source of Income: Employee'), //Source of income
                'PU' => array('shi_source_fbusiness', 'I2. Source of Income: Formal Business'),
                'PV' => array('shi_source_informal', 'I2. Source of Income: Informal Income'),
                'PW' => array('shi_employ_permanent', 'I2. Employment Status: Permanent'), //Employment Status
                'PX' => array('shi_employ_contract', 'I2. Employment Status: Contractual'),
                'PY' => array('shi_employ_extra', 'I2. Employment Status: Extra'),
                
                'PZ' => array('hh_employment', 'Employment Status of HH Head'),
                'QA' => array('shi_place_employment', 'I2. Address of Employment'), //Address/place of employment of business              
                
                'QJ' => array('shi_total_transpo', 'I2. Total Transporation Expenses'), //total transportation expenses
                'QN' => array('shi_total_hh_income', 'I2. Total Household Income'), //Total HH Income  
                'QU' => array('he_rental_amort', 'I2. Rental/Amortization'),
                //I3. Household Expenses
                'RF' => array('he_total_expenses', 'I3. Total Monthly Expenses'), //monhtly expenses

                'RG' => array('he_source_light', 'I3. Source of lighting'),
                'RH' => array('he_fuel_cooking', 'I3. Fuel for cooking'),
                'RI' => array('he_water_supply', 'I3. Water Supply'),
                'RJ' => array('he_sanitation', 'I3. Sanitation Facility (Toilet)'),

                //K. Household Assets
                'RK' => array('ha_tricycle', 'K. Tricycle'),
                'RL' => array('ha_motorcycle', 'K. Motorcyle'),
                'RM' => array('ha_computer', 'K. Computer'),
                'RN' => array('ha_electricfan', 'K. Electric Fan'),
                'RO' => array('ha_tv', 'K. Television'),
                'RP' => array('ha_radio', 'K. Radio'),
                'RQ' => array('ha_music', 'K. Music Component'),
                'RR' => array('ha_amplifier', 'K. Amplifier'),
                'RS' => array('ha_refrigerator', 'K. Refrigerator'),
                'RT' => array('ha_stove', 'K. Stove'),
                'RU' => array('ha_superkalan', 'K. Superkalan'),
                'RV' => array('ha_dvd', 'K. Portable DVD'),
                'RW' => array('ha_car', 'K. Car'),
                'RX' => array('ha_gadget', 'K. Gadget'),
                'RY' => array('ha_bike', 'K. Trike/Bike'),
                'RZ' => array('ha_ricecooker', 'K. Rice Cooker'),
                'SA' => array('ha_jeep', 'K. Jeepney'),
                'SB' => array('ha_waterpurifier', 'K. Water Purifier'),
                'SC' => array('ha_aircon', 'K. Aircon'),
                'SD' => array('ha_washingmachine', 'K. Washing Machine'),
                'SE' => array('ha_sewingmachine', 'K. Sewing Machine'),


                //L. Female Participation in Decision Making
                'SF' => array('fpdm_finance', 'L. Financial Matters'),
                'SG' => array('fpdm_education', 'L. Education of child'),
                'SH' => array('fpdm_health', 'L. Health care of child'),
                'SI' => array('fpdm_purchase', 'L. Purchase of Assets'),
                'SJ' => array('fpdm_daytoday', 'L. Day to day activities'),
                'SK' => array('fpdm_social', 'L. Social functions and marriages'),
                'SL' => array('fpdm_others', 'L. Others'),
                //M. Income and Livelihood Support Assitance

                //If ever you would lose your job because of the NSCR Project what sort of livelihood assistance would suit for your needs?
                'SM' => array('ialsa_livelihood_assistance', 'M. Livelihood Assistance'),

                //O. Flooding

                'VI' => array('flood_5years', 'Flooding in the last 5 years?'),
                'VJ' => array('flood_deepest', 'When deepest flooding'),
                'VK' => array('flood_typhoon', 'Particular typhoon'),
                'VL' => array('flood_times', 'How many times'),
                'VM' => array('flood_max_height', 'Maximum height'),
                'VN' => array('flood_location', 'Where is the location of flooding'),
                'VO' => array('flood_damage', 'How much damage caused by floood'),
                'VP' => array('flood_subside', 'How long to subside'),
                
            );
        }
    }

    public function createTableSurvey()
    {
        $columns = $this->columns;
        $tbl = $this->tbl;
        $query = "CREATE TABLE `$tbl` (`uid` int(8) NOT NULL COMMENT '0|ID|text' PRIMARY KEY AUTO_INCREMENT";

        foreach ($columns as $key => $value) {
            $query = $query . "," . "`" . $value[0] . "` text COMMENT '1|" . $value[1] . "|text'";
        }

        $query = $query . ", `is_deleted` int(11) NOT NULL DEFAULT '0' COMMENT '0|Deleted|text');";

        $this->db->query("DROP TABLE $tbl");       

        if (!($stmt = $this->db->prepare($query))) {
            echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
            return false;
        }


        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            return false;
        }


        $this->db->query("ALTER TABLE `$tbl` ADD PRIMARY KEY (`uid`);");
        $this->db->query("ALTER TABLE `$tbl` MODIFY `uid` int(8) NOT NULL AUTO_INCREMENT COMMENT '0|ID|text', AUTO_INCREMENT=10000;COMMIT;");
    }

    public function createTableHHNames()
    {
        $columns = $this->columns;

        $query = "CREATE TABLE `hh_names` (`uid` int(8) NOT NULL COMMENT '0|ID|text' PRIMARY KEY AUTO_INCREMENT";

        foreach ($columns as $key => $value) {
            $query = $query . "," . "`" . $value[0] . "` text COMMENT '1|" . $value[1] . "|text'";
        }

        $query = $query . ", `is_deleted` int(11) NOT NULL DEFAULT '0' COMMENT '0|Deleted|text');";

        $this->db->query("DROP TABLE hh_names");       

        if (!($stmt = $this->db->prepare($query))) {
            echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
            return false;
        }


        if (!$stmt->execute()) {
            echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            return false;
        }


        $this->db->query("ALTER TABLE `hh_names` ADD PRIMARY KEY (`uid`);");
        $this->db->query("ALTER TABLE `hh_names` MODIFY `uid` int(8) NOT NULL AUTO_INCREMENT COMMENT '0|ID|text', AUTO_INCREMENT=10000;COMMIT;");
    }

    public function importData($maxRow = 831)
    {
        $sheetIndex = 0;

        while (true) {
            $worksheet = $this->phpExcel->setActiveSheetIndex($sheetIndex);
            $worksheetTitle = $worksheet->getTitle();

            if ($this->tbl == 'survey') {
                if ($worksheetTitle == 'SurveyDATA') {
                    break;
                }
            } elseif ($this->tbl == 'hh_names') {
                if ($worksheetTitle == 'HH.names') {
                    break;
                }
            } elseif ($this->tbl == 'transmittal') {
                if ($worksheetTitle == 'Transmittal_Record') {
                    break;
                }
            }
                

            $sheetIndex++;
        }

        $worksheet = $this->phpExcel->setActiveSheetIndex($sheetIndex);
        $worksheetTitle = $worksheet->getTitle();
        $highestRow = $maxRow;

        $all = [];
        $showHidden = true;
        $verification = [];

        $start = 5;
        if ($this->tbl == "hh_names" || $this->tbl == "transmittal") $start = 2;

        for ($i=$start; $i <= $highestRow; $i++) {
            if ($worksheet->getRowDimension($i)->getVisible() || $showHidden ) {
                //get data
                $row = [];
                foreach ($this->columns as $key => $value) {
                    //flush cache
                    PHPExcel_Calculation::getInstance($this->phpExcel)->flushInstance();
                    $cellValue = $worksheet->getCell($key . $i)->getCalculatedValue();
                    $color = $worksheet->getStyle($key . $i)->getFill()->getStartColor()->getRGB();

                    //get only isf or legal
                    if ($key == 'A' && $this->db == "survey") {
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

                if ($this->tbl == 'survey') {
                    if (!($stmt = $this->db->prepare("INSERT INTO survey (`uid` $fields) VALUES(NULL $values)"))) {
                        echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
                    }
                } elseif ($this->tbl == 'hh_names') {
                    if (!($stmt = $this->db->prepare("INSERT INTO hh_names (`uid` $fields) VALUES(NULL $values)"))) {
                        echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
                    }
                } elseif ($this->tbl == 'transmittal') {
                    if (!($stmt = $this->db->prepare("INSERT INTO transmittal (`uid` $fields) VALUES(NULL $values)"))) {
                        echo "Prepare failed: (" . $this->db->errno . ") " . $this->db->error;
                    }
                }
                
                $stmt->bind_param($stringdef, ...$vals);

                if (!$stmt->execute()) {
                    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                }

                
            }

        }

        echo "FINISHED";
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

        $query = "SELECT * FROM `survey` ORDER by `asset_num`";
        $data = $this->db->query($query)->fetch_all(MYSQLI_ASSOC);

        $writer = $objWriter->getPHPExcel()->getActiveSheet();
        for ($i=5; $i <= (intval($result[0]['cnt']) + 4); $i++) {
            foreach ($this->columns as $key => $value) { //value[0]
                $cellValue = $writer->setCellValue($key . $i, ($data[$i - 5][$value[0]]), true);
                echo "Cell $key$i | Value: " . $data[$i - 5][$value[0]] . " | Result: $cellValue" . "\n\r";
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
    $import = new Importer($filename, 'survey');
    $import->$func();
} else {
    $maxRow = 729;
    $filename = '../data.xlsx';
    $table = "survey";

    if (isset($_GET['max_row']) === true) {
        $maxRow = $_GET['max_row'];
    }

    if (isset($_GET['filename']) === TRUE && $_GET['filename'] != '') {
        $filename = '../import/' . $_GET['filename'] . '.xlsx';
    }

    if (isset($_GET['table']) === true) {
        $table = $_GET['table'];
    }

    $import = new Importer($filename, $table);
    $import->createTableSurvey();
    $import->importData($maxRow);

    // $maxRow = 2299;
    // $import = new Importer($filename, 'hh_names');
    // $import->createTableHHNames();
    // $import->importData($maxRow);
}
