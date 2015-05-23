<?php
    /*
     * Script:    DataTables server-side script for PHP and MySQL
     * Copyright: 2010 - Allan Jardine, 2012 - Chris Wright
     * License:   GPL v2 or BSD (3-point)
     */
     
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * Easy set variables
     */
     
    /* Array of database columns which should be read and sent back to DataTables. Use a space where
     * you want to insert a non-database field (for example a counter or static image)
     */
    $aColumns = array(
	'Id','Business_Year','State_Code',
'Source_Name','Version_Number','Import_Date','Benefit_Package_ID','Issuer_ID_repeated','State_Code_repeated','Market_Coverage','Dental_Only_Plan_Indicator','Tax_Identification_Number','Plan_ID','Plan_Marketing_Name',
'HIOS_Product_ID','HPID_National_Health_Plan_Identifier','Network_ID','Service_Area_ID','Formulary_ID','New_Existing_Plan','Plan_Type','Metal_Level','Unique_Plan_Design','QHP_Non_QHP','Notice_Required_for_Pregnancy','Is_a_Referral_Required_for_Specialist','Specialist_Requiring_a_Referral','Plan_Level_Exclusions',
'Limited_Cost_Sharing_Plan_Variation_Estimated_Advanced_Payment','HSA_Eligible','HSA_HRA_Employer_Contribution','HSA_HRA_Employer_Contribution_Amount','Child_Only_Offering','Child_Only_Plan_ID','Wellness_Program_Offered','Disease_Management_Programs_Offered','EHB_Apportionment_for_Pediatric_Dental','Guaranteed_Rate','Specialty_Drug_Maximum_Coinsurance','Inpatient_Copayment_Maximum_Days',
'Begin_Primary_Care_Cost_Sharing_After_Number_Of_Visits','Begin_Primary_Care_Deductible_Coinsurance_After_Number_Of_Copays','Plan_Effictive_Date','Plan_Expiration_Date','Out_of_Country_Coverage','Out_of_Country_Coverage_Description','Out_of_Service_Area_Coverage','Out_of_Service_Area_Coverage_Description','National_Network','URL_for_Summary_of_Benefits_AND_Coverage','URL_for_Enrollment_Payment','Plan_Brochure','Plan_ID_Standard_Component_ID_with_Variant','CSR_Variation_Type','Issuer_Actuarial_Value','AV_Calculator_Output_Number','Medical_Drug_Deductibles_Integrated','Medical_Drug_Maximum_Out_of_Pocket_Integrated','Multiple_In_Network_Tiers','First_Tier_Utilization','Second_Tier_Utilization',
'MOP_Medical_EHB_Benefits_In_Network_Tier_1_Individual','MOP_Medical_EHB_Benefits_In_Network_Tier_1_Family','MOP_Medical_EHB_Benefits_In_Network_Tier_2_Individual','MOP_Medical_EHB_Benefits_In_Network_Tier_2_Family','MOP_Medical_EHB_Benefits_Out_of_Network_Individual','MOP_Medical_EHB_Benefits_Out_of_Network_Family','MOP_Medical_EHB_Benefits_Combined_In_Out_Network_Individual','MOP_Medical_EHB_Benefits_Combined_In_Out_Network_Family','MOP_Drug_EHB_Benefits_In_Network_Tier_1_Individual','MOP_Drug_EHB_Benefits_In_Network_Tier_1_Family','MOP_Drug_EHB_Benefits_In_Network_Tier_2_Individual','MOP_Drug_EHB_Benefits_In_Network_Tier_2_Family','MOP_Drug_EHB_Benefits_Out_of_Network_Individual','MOP_Drug_EHB_Benefits_Out_of_Network_Family','MOP_Drug_EHB_Benefits_Combined_In_Out_Network_Individual','MOP_Drug_EHB_Benefits_Combined_In_Out_Network_Family','MOP_MANDD_EHB_Bens_Total_In_Network_Tier_1_Individual','MOP_MANDD_EHB_Bens_Total_In_Network_Tier_1_Family','MOP_MANDD_EHB_Bens_Total_In_Network_Tier_2_Individual','MOP_MANDD_EHB_Bens_Total_In_Network_Tier_2_Family','MOP_MANDD_EHB_Bens_Total_Out_of_Network_Individual','MOP_MANDD_EHB_Bens_Total_Out_of_Network_Family','MOP_MANDD_EHB_Bens_Total_Combined_In_Out_Network_Individual','MOP_MANDD_EHB_Bens_Total_Combined_In_Out_Network_Family',
'Medical_EHB_Deductible_In_Network_Tier_1_Individual','Medical_EHB_Deductible_In_Network_Tier_1_Family','Medical_EHB_Deductible_In_Network_Tier_1_Default_Coinsurance','Medical_EHB_Deductible_In_Network_Tier_2_Individual','Medical_EHB_Deductible_In_Network_Tier_2_Family','Medical_EHB_Deductible_In_Network_Tier_2_Default_Coinsurance','Medical_EHB_Deductible_Out_of_Network_Individual','Medical_EHB_Deductible_Out_of_Network_Family','Medical_EHB_Deductible_Combined_In_Out_of_Network_Individual','Medical_EHB_Deductible_Combined_In_Out_of_Network_Family'
);
     
    /* Indexed column (used for fast and accurate table cardinality) */
    $sIndexColumn = "Id";
     
    /* DB table to use */
    $sTable = "PA14";
     
    /* Database connection information */
    $gaSql['user']       = "myuser";
    $gaSql['password']   = "DataIsFurry0";
    $gaSql['db']         = "HealthBase";
    $gaSql['server']     = "localhost";
     
     
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
     * If you just want to use the basic configuration for DataTables with PHP server-side, there is
     * no need to edit below this line
     */
     
    /*
     * Local functions
     */
    function fatal_error ( $sErrorMessage = '' )
    {
        header( $_SERVER['SERVER_PROTOCOL'] .' 500 Internal Server Error' );
        die( $sErrorMessage );
    }
 
     
    /*
     * MySQL connection
     */
    if ( ! $gaSql['link'] = mysql_pconnect( $gaSql['server'], $gaSql['user'], $gaSql['password']  ) )
    {
        fatal_error( 'Could not open connection to server' );
    }
 
    if ( ! mysql_select_db( $gaSql['db'], $gaSql['link'] ) )
    {
        fatal_error( 'Could not select database ' );
    }
     
     
    /*
     * Paging
     */
    $sLimit = "";
    if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
    {
        $sLimit = "LIMIT ".intval( $_GET['iDisplayStart'] ).", ".
            intval( $_GET['iDisplayLength'] );
    }
     
     
    /*
     * Ordering
     */
    $sOrder = "";
    if ( isset( $_GET['iSortCol_0'] ) )
    {
        $sOrder = "ORDER BY  ";
        for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
        {
            if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
            {
                $sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
                    ".($_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";
            }
        }
         
        $sOrder = substr_replace( $sOrder, "", -2 );
        if ( $sOrder == "ORDER BY" )
        {
            $sOrder = "";
        }
    }
     
     
    /*
     * Filtering
     * NOTE this does not match the built-in DataTables filtering which does it
     * word by word on any field. It's possible to do here, but concerned about efficiency
     * on very large tables, and MySQL's regex functionality is very limited
     */
    $sWhere = "";
    if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
    {
        $sWhere = "WHERE (";
        for ( $i=0 ; $i<count($aColumns) ; $i++ )
        {
            if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" )
            {
                $sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
            }
        }
        $sWhere = substr_replace( $sWhere, "", -3 );
        $sWhere .= ')';
    }
     
    /* Individual column filtering */
    for ( $i=0 ; $i<count($aColumns) ; $i++ )
    {
        if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
        {
            if ( $sWhere == "" )
            {
                $sWhere = "WHERE ";
            }
            else
            {
                $sWhere .= " AND ";
            }
            $sWhere .= $aColumns[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
        }
    }
     
     
    /*
     * SQL queries
     * Get data to display
     */
    $sQuery = "
        SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
        FROM   $sTable
        $sWhere
        $sOrder
        $sLimit
    ";
    $rResult = mysql_query( $sQuery, $gaSql['link'] ) or fatal_error( 'MySQL Error: ' . mysql_errno() );
     
    /* Data set length after filtering */
    $sQuery = "
        SELECT FOUND_ROWS()
    ";
    $rResultFilterTotal = mysql_query( $sQuery, $gaSql['link'] ) or fatal_error( 'MySQL Error: ' . mysql_errno() );
    $aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
    $iFilteredTotal = $aResultFilterTotal[0];
     
    /* Total data set length */
    $sQuery = "
        SELECT COUNT(".$sIndexColumn.")
        FROM   $sTable
    ";
    $rResultTotal = mysql_query( $sQuery, $gaSql['link'] ) or fatal_error( 'MySQL Error: ' . mysql_errno() );
    $aResultTotal = mysql_fetch_array($rResultTotal);
    $iTotal = $aResultTotal[0];
     
     
    /*
     * Output
     */
    $output = array(
        "sEcho" => intval($_GET['sEcho']),
        "iTotalRecords" => $iTotal,
        "iTotalDisplayRecords" => $iFilteredTotal,
        "aaData" => array()
    );
     
    while ( $aRow = mysql_fetch_array( $rResult ) )
    {
        $row = array();
        for ( $i=0 ; $i<count($aColumns) ; $i++ )
        {
            if ( $aColumns[$i] == "version" )
            {
                /* Special output formatting for 'version' column */
                $row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $aRow[ $aColumns[$i] ];
            }
            else if ( $aColumns[$i] != ' ' )
            {
                /* General output */
                $row[] = $aRow[ $aColumns[$i] ];
            }
        }
        $output['aaData'][] = $row;
    }
     
    echo json_encode( $output );
?>