<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php

require_once("../modules/session.php");
require_once("../modules/db.php");
require_once('../library/fedex-common.php5');
$path_to_wsdl = "../wsdl/RateService_v8.wsdl";
$arrBoxCount = array();
$arrBoxes = array();

// CONNECT TO SQL SERVER DATABASE
$connNewOrder = mssql_connect($dbServer, $dbUser, $dbPass) or die("Couldn't connect to SQL Server on $dbServer");
$selected = mssql_select_db($dbName, $connNewOrder);

// Collect the ProductID and Qty for all Entries
$ProductID1 = $_POST["ItemID1"];
$ProductID2 = $_POST["ItemID2"];
$ProductID3 = $_POST["ItemID3"];
$ProductID4 = $_POST["ItemID4"];
$ProductID5 = $_POST["ItemID5"];
$ProductID6 = $_POST["ItemID6"];
$ProductID7 = $_POST["ItemID7"];
$ProductID8 = $_POST["ItemID8"];
$ProductID9 = $_POST["ItemID9"];
$ProductID10 = $_POST["ItemID10"];
$ProductID11 = $_POST["ItemID11"];
$ProductID12 = $_POST["ItemID12"];
$ProductID13 = $_POST["ItemID13"];
$ProductID14 = $_POST["ItemID14"];
$ProductID15 = $_POST["ItemID15"];
$ProductID16 = $_POST["ItemID16"];
$ProductID17 = $_POST["ItemID17"];
$ProductID18 = $_POST["ItemID18"];
$ProductID19 = $_POST["ItemID19"];
$ProductID20 = $_POST["ItemID20"];
$Qty1 = $_POST["ItemQty1"];
$Qty2 = $_POST["ItemQty2"];
$Qty3 = $_POST["ItemQty3"];
$Qty4 = $_POST["ItemQty4"];
$Qty5 = $_POST["ItemQty5"];
$Qty6 = $_POST["ItemQty6"];
$Qty7 = $_POST["ItemQty7"];
$Qty8 = $_POST["ItemQty8"];
$Qty9 = $_POST["ItemQty9"];
$Qty10 = $_POST["ItemQty10"];
$Qty11 = $_POST["ItemQty11"];
$Qty12 = $_POST["ItemQty12"];
$Qty13 = $_POST["ItemQty13"];
$Qty14 = $_POST["ItemQty14"];
$Qty15 = $_POST["ItemQty15"];
$Qty16 = $_POST["ItemQty16"];
$Qty17 = $_POST["ItemQty17"];
$Qty18 = $_POST["ItemQty18"];
$Qty19 = $_POST["ItemQty19"];
$Qty20 = $_POST["ItemQty20"];
$EntryFlag1 = "";
$EntryFlag2 = "";
$EntryFlag3 = "";
$EntryFlag4 = "";
$EntryFlag5 = "";
$EntryFlag6 = "";
$EntryFlag7 = "";
$EntryFlag8 = "";
$EntryFlag9 = "";
$EntryFlag10 = "";
$EntryFlag11 = "";
$EntryFlag12 = "";
$EntryFlag13 = "";
$EntryFlag14 = "";
$EntryFlag15 = "";
$EntryFlag16 = "";
$EntryFlag17 = "";
$EntryFlag18 = "";
$EntryFlag19 = "";
$EntryFlag20 = "";
$DivisionInt1 = 0;
$DivisionInt2 = 0;
$DivisionInt3 = 0;
$DivisionInt4 = 0;
$DivisionInt5 = 0;
$DivisionInt6 = 0;
$DivisionInt7 = 0;
$DivisionInt8 = 0;
$DivisionInt9 = 0;
$DivisionInt10 = 0;
$DivisionInt11 = 0;
$DivisionInt12 = 0;
$DivisionInt13 = 0;
$DivisionInt14 = 0;
$DivisionInt15 = 0;
$DivisionInt16 = 0;
$DivisionInt17 = 0;
$DivisionInt18 = 0;
$DivisionInt19 = 0;
$DivisionInt20 = 0;
$RemWt1 = 0;
$RemWt2 = 0;
$RemWt3 = 0;
$RemWt4 = 0;
$RemWt5 = 0;
$RemWt6 = 0;
$RemWt7 = 0;
$RemWt8 = 0;
$RemWt9 = 0;
$RemWt10 = 0;
$RemWt11 = 0;
$RemWt12 = 0;
$RemWt13 = 0;
$RemWt14 = 0;
$RemWt15 = 0;
$RemWt16 = 0;
$RemWt17 = 0;
$RemWt18 = 0;
$RemWt19 = 0;
$RemWt20 = 0;
$EntryWeight1 = 0;
$EntryWeight2 = 0;
$EntryWeight3 = 0;
$EntryWeight4 = 0;
$EntryWeight5 = 0;
$EntryWeight6 = 0;
$EntryWeight7 = 0;
$EntryWeight8 = 0;
$EntryWeight9 = 0;
$EntryWeight10 = 0;
$EntryWeight11 = 0;
$EntryWeight12 = 0;
$EntryWeight13 = 0;
$EntryWeight14 = 0;
$EntryWeight15 = 0;
$EntryWeight16 = 0;
$EntryWeight17 = 0;
$EntryWeight18 = 0;
$EntryWeight19 = 0;
$EntryWeight20 = 0;
$counterBoxes = 0;

// Now get the Weight and BoxCount for each entry
if (isset($_POST['ItemID1']) && $_POST['ItemID1'] != 0)
{
    $ProductWtBx1 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID1);
}
else
{
    $EntryFlag1 = "True";
}

if (isset($_POST['ItemID2']) && $_POST['ItemID2'] != 0)
{
    $ProductWtBx2 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID2);
}
else
{
    $EntryFlag2 = "True";
}

if (isset($_POST['ItemID3']) && $_POST['ItemID3'] != 0)
{
    $ProductWtBx3 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID3);
}
else
{
    $EntryFlag3 = "True";
}

if (isset($_POST['ItemID4']) && $_POST['ItemID4'] != 0)
{
    $ProductWtBx4 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID4);
}
else
{
    $EntryFlag4 = "True";
}

if (isset($_POST['ItemID5']) && $_POST['ItemID5'] != 0)
{
    $ProductWtBx5 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID5);
}
else
{
    $EntryFlag5 = "True";
}

if (isset($_POST['ItemID6']) && $_POST['ItemID6'] != 0)
{
    $ProductWtBx6 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID6);
}
else
{
    $EntryFlag6 = "True";
}

if (isset($_POST['ItemID7']) && $_POST['ItemID7'] != 0)
{
    $ProductWtBx7 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID7);
}
else
{
    $EntryFlag7 = "True";
}

if (isset($_POST['ItemID8']) && $_POST['ItemID8'] != 0)
{
    $ProductWtBx8 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID8);
}
else
{
    $EntryFlag8 = "True";
}

if (isset($_POST['ItemID9']) && $_POST['ItemID9'] != 0)
{
    $ProductWtBx9 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID9);
}
else
{
    $EntryFlag9 = "True";
}

if (isset($_POST['ItemID10']) && $_POST['ItemID10'] != 0)
{
    $ProductWtBx10 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID10);
}
else
{
    $EntryFlag10 = "True";
}

if (isset($_POST['ItemID11']) && $_POST['ItemID11'] != 0)
{
    $ProductWtBx11 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID11);
}
else
{
    $EntryFlag11 = "True";
}

if (isset($_POST['ItemID12']) && $_POST['ItemID12'] != 0)
{
    $ProductWtBx12 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID12);
}
else
{
    $EntryFlag12 = "True";
}

if (isset($_POST['ItemID13']) && $_POST['ItemID13'] != 0)
{
    $ProductWtBx13 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID13);
}
else
{
    $EntryFlag13 = "True";
}

if (isset($_POST['ItemID14']) && $_POST['ItemID14'] != 0)
{
    $ProductWtBx14 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID14);
}
else
{
    $EntryFlag14 = "True";
}

if (isset($_POST['ItemID15']) && $_POST['ItemID15'] != 0)
{
    $ProductWtBx15 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID15);
}
else
{
    $EntryFlag15 = "True";
}

if (isset($_POST['ItemID16']) && $_POST['ItemID16'] != 0)
{
    $ProductWtBx16 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID16);
}
else
{
    $EntryFlag16 = "True";
}

if (isset($_POST['ItemID17']) && $_POST['ItemID17'] != 0)
{
    $ProductWtBx17 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID17);
}
else
{
    $EntryFlag17 = "True";
}

if (isset($_POST['ItemID18']) && $_POST['ItemID18'] != 0)
{
    $ProductWtBx18 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID18);
}
else
{
    $EntryFlag18 = "True";
}

if (isset($_POST['ItemID19']) && $_POST['ItemID19'] != 0)
{
    $ProductWtBx19 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID19);
}
else
{
    $EntryFlag19 = "True";
}

if (isset($_POST['ItemID20']) && $_POST['ItemID20'] != 0)
{
    $ProductWtBx20 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID20);
}
else
{
    $EntryFlag20 = "True";
}

// CLOSE DATABASE CONNECTION
mssql_close($connNewOrder);

?>

<head>
	<title>Customer Management | Revitalash Administration | Calculate Boxes Prototype</title>
    <meta name="ROBOTS" content="NOINDEX, NOFOLLOW">
	<link rel="stylesheet" href="/csradmin/styles/revitalash.css" type="text/css" />
</head>

<body>

	<div id="wrapper">

		<?php include("includes/dspMasthead.php"); ?>

		<div id="content">

         <h1>Calculate Boxes Prototype</h1>

         <table width="900" cellpadding="3" cellspacing="0" style="margin:10px;">
         <tr><th width="*" style="text-align:left">Entries</th></tr>
         </table>

         <table width="900" cellpadding="3" cellspacing="0" style="margin:10px;">

         <tr>
	        <th width="*" style="text-align:left">Nav Id</th>
	        <th width="100" style="text-align:left">Qty</th>
            <th width="120" style="text-align:left">Unit Weight</th>
            <th width="120" style="text-align:left">Box Count</th>
            <th width="100" style="text-align:left;">Entry Weight</th>
            <th width="100" style="text-align:left"># Boxes</th>
            <th width="150" style="text-align:left;">Rem Bal</th>
            <th width="150" style="text-align:left;">Rem Weight</th>
         </tr>

         <?php

             // 1 Entry
             if ($EntryFlag1 != "True")
             {
             while($row = mssql_fetch_array($ProductWtBx1))
             {
                 $Weight1 = $row["Weight"];
                 $BoxCount1 = $row["BoxCount"];
                 $BoxWeight1 = $row["BoxWeight"];
                 $NavId1 = $row["PartNumber"];
                 $BoxLength1 = $row["BoxLength"];
                 $BoxWidth1 = $row["BoxWidth"];
                 $BoxHeight1 = $row["BoxHeight"];
             }
                 
             // Calculate the # of Boxes Complete and the Remanent Balance
             $DivisionTotal1 = $Qty1/$BoxCount1;
             $DivisionInt1 = floor($Qty1/$BoxCount1);
             $DivisionRem1 = $DivisionTotal1 - $DivisionInt1;
             $RemBal1 = $DivisionRem1 * $BoxCount1;
             $EntryWeight1 = $DivisionInt1 * $BoxWeight1;
             $RemWt1 = $RemBal1 * $Weight1;

             echo '<tr>';
             echo '<td>' . $NavId1 . '</td>';
             echo '<td>' . $Qty1 . '</td>';
             echo '<td>' . $Weight1 . '</td>';
             echo '<td>' . $BoxCount1 . '</td>';
             echo '<td>' . $EntryWeight1 . '</td>';
             echo '<td>' . $DivisionInt1 . '</td>';
             echo '<td>' . $RemBal1 . '</td>';
             echo '<td>' . $RemWt1 . '</td>';
             echo '</tr>';

             if($DivisionInt1 > 0)
             {
             for ($counter = 0; $counter < $DivisionInt1; $counter++)
             {
                 $arrBoxCount[$counterBoxes] = 1;
                 $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeight1,
                      'Units' => 'LB'),
                      'Dimensions' => array('Length' => $BoxLength1,
                      'Width' => $BoxWidth1,
                      'Height' => $BoxHeight1,
                      'Units' => 'IN'));
                 $counterBoxes++;
             }
             }

             }

             // 2 Entry
             if ($EntryFlag2 != "True")
             {
             while($row = mssql_fetch_array($ProductWtBx2))
             {
                 $Weight2 = $row["Weight"];
                 $BoxCount2 = $row["BoxCount"];
                 $BoxWeight2 = $row["BoxWeight"];
                 $NavId2 = $row["PartNumber"];
                 $BoxLength2 = $row["BoxLength"];
                 $BoxWidth2 = $row["BoxWidth"];
                 $BoxHeight2 = $row["BoxHeight"];
             }

             // Calculate the # of Boxes Complete and the Remanent Balance
             $DivisionTotal2 = $Qty2/$BoxCount2;
             $DivisionInt2 = floor($Qty2/$BoxCount2);
             $DivisionRem2 = $DivisionTotal2 - $DivisionInt2;
             $RemBal2 = $DivisionRem2 * $BoxCount2;
             $EntryWeight2 = $DivisionInt2 * $BoxWeight2;
             $RemWt2 = $RemBal2 * $Weight2;

             echo '<tr>';
             echo '<td>' . $NavId2 . '</td>';
             echo '<td>' . $Qty2 . '</td>';
             echo '<td>' . $Weight2 . '</td>';
             echo '<td>' . $BoxCount2 . '</td>';
             echo '<td>' . $EntryWeight2 . '</td>';
             echo '<td>' . $DivisionInt2 . '</td>';
             echo '<td>' . $RemBal2 . '</td>';
             echo '<td>' . $RemWt2 . '</td>';
             echo '</tr>';

             if($DivisionInt2 > 0)
             {
             for ($counter = 0; $counter < $DivisionInt2; $counter++)
             {
                 $arrBoxCount[$counterBoxes] = 1;
                 $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeight2,
                      'Units' => 'LB'),
                      'Dimensions' => array('Length' => $BoxLength2,
                      'Width' => $BoxWidth2,
                      'Height' => $BoxHeight2,
                      'Units' => 'IN'));
                 $counterBoxes++;
             }
             }

             }

             // 3 Entry
             if ($EntryFlag3 != "True")
             {
             while($row = mssql_fetch_array($ProductWtBx3))
             {
                 $Weight3 = $row["Weight"];
                 $BoxCount3 = $row["BoxCount"];
                 $BoxWeight3 = $row["BoxWeight"];
                 $NavId3 = $row["PartNumber"];
                 $BoxLength3 = $row["BoxLength"];
                 $BoxWidth3 = $row["BoxWidth"];
                 $BoxHeight3 = $row["BoxHeight"];
             }

             // Calculate the # of Boxes Complete and the Remanent Balance
             $DivisionTotal3 = $Qty3/$BoxCount3;
             $DivisionInt3 = floor($Qty3/$BoxCount3);
             $DivisionRem3 = $DivisionTotal3 - $DivisionInt3;
             $RemBal3 = $DivisionRem3 * $BoxCount3;
             $EntryWeight3 = $DivisionInt3 * $BoxWeight3;
             $RemWt3 = $RemBal3 * $Weight3;

             echo '<tr>';
             echo '<td>' . $NavId3 . '</td>';
             echo '<td>' . $Qty3 . '</td>';
             echo '<td>' . $Weight3 . '</td>';
             echo '<td>' . $BoxCount3 . '</td>';
             echo '<td>' . $EntryWeight3 . '</td>';
             echo '<td>' . $DivisionInt3 . '</td>';
             echo '<td>' . $RemBal3 . '</td>';
             echo '<td>' . $RemWt3 . '</td>';
             echo '</tr>';

             if($DivisionInt3 > 0)
             {
             for ($counter = 0; $counter < $DivisionInt3; $counter++)
             {
                 $arrBoxCount[$counterBoxes] = 1;
                 $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeight3,
                      'Units' => 'LB'),
                      'Dimensions' => array('Length' => $BoxLength3,
                      'Width' => $BoxWidth3,
                      'Height' => $BoxHeight3,
                      'Units' => 'IN'));
                 $counterBoxes++;
             }
             }

             }

             // 4 Entry
             if ($EntryFlag4 != "True")
             {
             while($row = mssql_fetch_array($ProductWtBx4))
             {
                 $Weight4 = $row["Weight"];
                 $BoxCount4 = $row["BoxCount"];
                 $BoxWeight4 = $row["BoxWeight"];
                 $NavId4 = $row["PartNumber"];
                 $BoxLength4 = $row["BoxLength"];
                 $BoxWidth4 = $row["BoxWidth"];
                 $BoxHeight4 = $row["BoxHeight"];
             }

             // Calculate the # of Boxes Complete and the Remanent Balance
             $DivisionTotal4 = $Qty4/$BoxCount4;
             $DivisionInt4 = floor($Qty4/$BoxCount4);
             $DivisionRem4 = $DivisionTotal4 - $DivisionInt4;
             $RemBal4 = $DivisionRem4 * $BoxCount4;
             $EntryWeight4 = $DivisionInt4 * $BoxWeight4;
             $RemWt4 = $RemBal4 * $Weight4;

             echo '<tr>';
             echo '<td>' . $NavId4 . '</td>';
             echo '<td>' . $Qty4 . '</td>';
             echo '<td>' . $Weight4 . '</td>';
             echo '<td>' . $BoxCount4 . '</td>';
             echo '<td>' . $EntryWeight4 . '</td>';
             echo '<td>' . $DivisionInt4 . '</td>';
             echo '<td>' . $RemBal4 . '</td>';
             echo '<td>' . $RemWt4 . '</td>';
             echo '</tr>';

             if($DivisionInt4 > 0)
             {
             for ($counter = 0; $counter < $DivisionInt4; $counter++)
             {
                 $arrBoxCount[$counterBoxes] = 1;
                 $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeight4,
                      'Units' => 'LB'),
                      'Dimensions' => array('Length' => $BoxLength4,
                      'Width' => $BoxWidth4,
                      'Height' => $BoxHeight4,
                      'Units' => 'IN'));
                 $counterBoxes++;
             }
             }

             }

             // 5 Entry
             if ($EntryFlag5 != "True")
             {
             while($row = mssql_fetch_array($ProductWtBx5))
             {
                 $Weight5 = $row["Weight"];
                 $BoxCount5 = $row["BoxCount"];
                 $BoxWeight5 = $row["BoxWeight"];
                 $NavId5 = $row["PartNumber"];
                 $BoxLength5 = $row["BoxLength"];
                 $BoxWidth5 = $row["BoxWidth"];
                 $BoxHeight5 = $row["BoxHeight"];
             }

             // Calculate the # of Boxes Complete and the Remanent Balance
             $DivisionTotal5 = $Qty5/$BoxCount5;
             $DivisionInt5 = floor($Qty5/$BoxCount5);
             $DivisionRem5 = $DivisionTotal5 - $DivisionInt5;
             $RemBal5 = $DivisionRem5 * $BoxCount5;
             $EntryWeight5 = $DivisionInt5 * $BoxWeight5;
             $RemWt5 = $RemBal5 * $Weight5;

             echo '<tr>';
             echo '<td>' . $NavId5 . '</td>';
             echo '<td>' . $Qty5 . '</td>';
             echo '<td>' . $Weight5 . '</td>';
             echo '<td>' . $BoxCount5 . '</td>';
             echo '<td>' . $EntryWeight5 . '</td>';
             echo '<td>' . $DivisionInt5 . '</td>';
             echo '<td>' . $RemBal5 . '</td>';
             echo '<td>' . $RemWt5 . '</td>';
             echo '</tr>';

             if($DivisionInt5 > 0)
             {
             for ($counter = 0; $counter < $DivisionInt5; $counter++)
             {
                 $arrBoxCount[$counterBoxes] = 1;
                 $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeight5,
                      'Units' => 'LB'),
                      'Dimensions' => array('Length' => $BoxLength5,
                      'Width' => $BoxWidth5,
                      'Height' => $BoxHeight5,
                      'Units' => 'IN'));
                 $counterBoxes++;
             }
             }

             }

             // 6 Entry
             if ($EntryFlag6 != "True")
             {
             while($row = mssql_fetch_array($ProductWtBx6))
             {
                 $Weight6 = $row["Weight"];
                 $BoxCount6 = $row["BoxCount"];
                 $BoxWeight6 = $row["BoxWeight"];
                 $NavId6 = $row["PartNumber"];
                 $BoxLength6 = $row["BoxLength"];
                 $BoxWidth6 = $row["BoxWidth"];
                 $BoxHeight6 = $row["BoxHeight"];
             }

             // Calculate the # of Boxes Complete and the Remanent Balance
             $DivisionTotal6 = $Qty6/$BoxCount6;
             $DivisionInt6 = floor($Qty6/$BoxCount6);
             $DivisionRem6 = $DivisionTotal6 - $DivisionInt6;
             $RemBal6 = $DivisionRem6 * $BoxCount6;
             $EntryWeight6 = $DivisionInt6 * $BoxWeight6;
             $RemWt6 = $RemBal6 * $Weight6;

             echo '<tr>';
             echo '<td>' . $NavId6 . '</td>';
             echo '<td>' . $Qty6 . '</td>';
             echo '<td>' . $Weight6 . '</td>';
             echo '<td>' . $BoxCount6 . '</td>';
             echo '<td>' . $EntryWeight6 . '</td>';
             echo '<td>' . $DivisionInt6 . '</td>';
             echo '<td>' . $RemBal6 . '</td>';
             echo '<td>' . $RemWt6 . '</td>';
             echo '</tr>';

             if($DivisionInt6 > 0)
             {
             for ($counter = 0; $counter < $DivisionInt6; $counter++)
             {
                 $arrBoxCount[$counterBoxes] = 1;
                 $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeight6,
                      'Units' => 'LB'),
                      'Dimensions' => array('Length' => $BoxLength6,
                      'Width' => $BoxWidth6,
                      'Height' => $BoxHeight6,
                      'Units' => 'IN'));
                 $counterBoxes++;
             }
             }
             
             }

             // 7 Entry
             if ($EntryFlag7 != "True")
             {
             while($row = mssql_fetch_array($ProductWtBx7))
             {
                 $Weight7 = $row["Weight"];
                 $BoxCount7 = $row["BoxCount"];
                 $BoxWeight7 = $row["BoxWeight"];
                 $NavId7 = $row["PartNumber"];
                 $BoxLength7 = $row["BoxLength"];
                 $BoxWidth7 = $row["BoxWidth"];
                 $BoxHeight7 = $row["BoxHeight"];
             }

             // Calculate the # of Boxes Complete and the Remanent Balance
             $DivisionTotal7 = $Qty7/$BoxCount7;
             $DivisionInt7 = floor($Qty7/$BoxCount7);
             $DivisionRem7 = $DivisionTotal7 - $DivisionInt7;
             $RemBal7 = $DivisionRem7 * $BoxCount7;
             $EntryWeight7 = $DivisionInt7 * $BoxWeight7;
             $RemWt7 = $RemBal7 * $Weight7;

             echo '<tr>';
             echo '<td>' . $NavId7 . '</td>';
             echo '<td>' . $Qty7 . '</td>';
             echo '<td>' . $Weight7 . '</td>';
             echo '<td>' . $BoxCount7 . '</td>';
             echo '<td>' . $EntryWeight7 . '</td>';
             echo '<td>' . $DivisionInt7 . '</td>';
             echo '<td>' . $RemBal7 . '</td>';
             echo '<td>' . $RemWt7 . '</td>';
             echo '</tr>';

             if($DivisionInt7 > 0)
             {
             for ($counter = 0; $counter < $DivisionInt7; $counter++)
             {
                 $arrBoxCount[$counterBoxes] = 1;
                 $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeight7,
                      'Units' => 'LB'),
                      'Dimensions' => array('Length' => $BoxLength7,
                      'Width' => $BoxWidth7,
                      'Height' => $BoxHeight7,
                      'Units' => 'IN'));
                 $counterBoxes++;
             }
             }

             }

             // 8 Entry
             if ($EntryFlag8 != "True")
             {
             while($row = mssql_fetch_array($ProductWtBx8))
             {
                 $Weight8 = $row["Weight"];
                 $BoxCount8 = $row["BoxCount"];
                 $BoxWeight8 = $row["BoxWeight"];
                 $NavId8 = $row["PartNumber"];
                 $BoxLength8 = $row["BoxLength"];
                 $BoxWidth8 = $row["BoxWidth"];
                 $BoxHeight8 = $row["BoxHeight"];
             }

             // Calculate the # of Boxes Complete and the Remanent Balance
             $DivisionTotal8 = $Qty8/$BoxCount8;
             $DivisionInt8 = floor($Qty8/$BoxCount8);
             $DivisionRem8 = $DivisionTotal8 - $DivisionInt8;
             $RemBal8 = $DivisionRem8 * $BoxCount8;
             $EntryWeight8 = $DivisionInt8 * $BoxWeight8;
             $RemWt8 = $RemBal8 * $Weight8;

             echo '<tr>';
             echo '<td>' . $NavId8 . '</td>';
             echo '<td>' . $Qty8 . '</td>';
             echo '<td>' . $Weight8 . '</td>';
             echo '<td>' . $BoxCount8 . '</td>';
             echo '<td>' . $EntryWeight8 . '</td>';
             echo '<td>' . $DivisionInt8 . '</td>';
             echo '<td>' . $RemBal8 . '</td>';
             echo '<td>' . $RemWt8 . '</td>';
             echo '</tr>';

             if($DivisionInt8 > 0)
             {
             for ($counter = 0; $counter < $DivisionInt8; $counter++)
             {
                 $arrBoxCount[$counterBoxes] = 1;
                 $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeight8,
                      'Units' => 'LB'),
                      'Dimensions' => array('Length' => $BoxLength8,
                      'Width' => $BoxWidth8,
                      'Height' => $BoxHeight8,
                      'Units' => 'IN'));
                 $counterBoxes++;
             }
             }
             
             }

             // 9 Entry
             if ($EntryFlag9 != "True")
             {
             while($row = mssql_fetch_array($ProductWtBx9))
             {
                 $Weight9 = $row["Weight"];
                 $BoxCount9 = $row["BoxCount"];
                 $BoxWeight9 = $row["BoxWeight"];
                 $NavId9 = $row["PartNumber"];
                 $BoxLength9 = $row["BoxLength"];
                 $BoxWidth9 = $row["BoxWidth"];
                 $BoxHeight9 = $row["BoxHeight"];
             }

             // Calculate the # of Boxes Complete and the Remanent Balance
             $DivisionTotal9 = $Qty9/$BoxCount9;
             $DivisionInt9 = floor($Qty9/$BoxCount9);
             $DivisionRem9 = $DivisionTotal9 - $DivisionInt9;
             $RemBal9 = $DivisionRem9 * $BoxCount9;
             $EntryWeight9 = $DivisionInt9 * $BoxWeight9;
             $RemWt9 = $RemBal9 * $Weight9;

             echo '<tr>';
             echo '<td>' . $NavId9 . '</td>';
             echo '<td>' . $Qty9 . '</td>';
             echo '<td>' . $Weight9 . '</td>';
             echo '<td>' . $BoxCount9 . '</td>';
             echo '<td>' . $EntryWeight9 . '</td>';
             echo '<td>' . $DivisionInt9 . '</td>';
             echo '<td>' . $RemBal9 . '</td>';
             echo '<td>' . $RemWt9 . '</td>';
             echo '</tr>';

             if($DivisionInt9 > 0)
             {
             for ($counter = 0; $counter < $DivisionInt9; $counter++)
             {
                 $arrBoxCount[$counterBoxes] = 1;
                 $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeight9,
                      'Units' => 'LB'),
                      'Dimensions' => array('Length' => $BoxLength9,
                      'Width' => $BoxWidth9,
                      'Height' => $BoxHeight9,
                      'Units' => 'IN'));
                 $counterBoxes++;
             }
             }
             
             }

             // 10 Entry
             if ($EntryFlag10 != "True")
             {
             while($row = mssql_fetch_array($ProductWtBx10))
             {
                 $Weight10 = $row["Weight"];
                 $BoxCount10 = $row["BoxCount"];
                 $BoxWeight10 = $row["BoxWeight"];
                 $NavId10 = $row["PartNumber"];
                 $BoxLength10 = $row["BoxLength"];
                 $BoxWidth10 = $row["BoxWidth"];
                 $BoxHeight10 = $row["BoxHeight"];
             }

             // Calculate the # of Boxes Complete and the Remanent Balance
             $DivisionTotal10 = $Qty10/$BoxCount10;
             $DivisionInt10 = floor($Qty10/$BoxCount10);
             $DivisionRem10 = $DivisionTotal10 - $DivisionInt10;
             $RemBal10 = $DivisionRem10 * $BoxCount10;
             $EntryWeight10 = $DivisionInt10 * $BoxWeight10;
             $RemWt10 = $RemBal10 * $Weight10;

             echo '<tr>';
             echo '<td>' . $NavId10 . '</td>';
             echo '<td>' . $Qty10 . '</td>';
             echo '<td>' . $Weight10 . '</td>';
             echo '<td>' . $BoxCount10 . '</td>';
             echo '<td>' . $EntryWeight10 . '</td>';
             echo '<td>' . $DivisionInt10 . '</td>';
             echo '<td>' . $RemBal10 . '</td>';
             echo '<td>' . $RemWt10 . '</td>';
             echo '</tr>';

             if($DivisionInt10 > 0)
             {
             for ($counter = 0; $counter < $DivisionInt10; $counter++)
             {
                 $arrBoxCount[$counterBoxes] = 1;
                 $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeight10,
                      'Units' => 'LB'),
                      'Dimensions' => array('Length' => $BoxLength10,
                      'Width' => $BoxWidth10,
                      'Height' => $BoxHeight10,
                      'Units' => 'IN'));
                 $counterBoxes++;
             }
             }
             
             }

             // 11 Entry
             if ($EntryFlag11 != "True")
             {
             while($row = mssql_fetch_array($ProductWtBx11))
             {
                 $Weight11 = $row["Weight"];
                 $BoxCount11 = $row["BoxCount"];
                 $BoxWeight11 = $row["BoxWeight"];
                 $NavId11 = $row["PartNumber"];
                 $BoxLength11 = $row["BoxLength"];
                 $BoxWidth11 = $row["BoxWidth"];
                 $BoxHeight11 = $row["BoxHeight"];
             }

             // Calculate the # of Boxes Complete and the Remanent Balance
             $DivisionTotal11 = $Qty11/$BoxCount11;
             $DivisionInt11 = floor($Qty11/$BoxCount11);
             $DivisionRem11 = $DivisionTotal11 - $DivisionInt11;
             $RemBal11 = $DivisionRem11 * $BoxCount11;
             $EntryWeight11 = $DivisionInt11 * $BoxWeight11;
             $RemWt11 = $RemBal11 * $Weight11;

             echo '<tr>';
             echo '<td>' . $NavId11 . '</td>';
             echo '<td>' . $Qty11 . '</td>';
             echo '<td>' . $Weight11 . '</td>';
             echo '<td>' . $BoxCount11 . '</td>';
             echo '<td>' . $EntryWeight11 . '</td>';
             echo '<td>' . $DivisionInt11 . '</td>';
             echo '<td>' . $RemBal11 . '</td>';
             echo '<td>' . $RemWt11 . '</td>';
             echo '</tr>';

             if($DivisionInt11 > 0)
             {
             for ($counter = 0; $counter < $DivisionInt11; $counter++)
             {
                 $arrBoxCount[$counterBoxes] = 1;
                 $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeight11,
                      'Units' => 'LB'),
                      'Dimensions' => array('Length' => $BoxLength11,
                      'Width' => $BoxWidth11,
                      'Height' => $BoxHeight11,
                      'Units' => 'IN'));
                 $counterBoxes++;
             }
             }
             
             }

             // 12 Entry
             if ($EntryFlag12 != "True")
             {
             while($row = mssql_fetch_array($ProductWtBx12))
             {
                 $Weight12 = $row["Weight"];
                 $BoxCount12 = $row["BoxCount"];
                 $BoxWeight12 = $row["BoxWeight"];
                 $NavId12 = $row["PartNumber"];
                 $BoxLength12 = $row["BoxLength"];
                 $BoxWidth12 = $row["BoxWidth"];
                 $BoxHeight12 = $row["BoxHeight"];
             }

             // Calculate the # of Boxes Complete and the Remanent Balance
             $DivisionTotal12 = $Qty12/$BoxCount12;
             $DivisionInt12 = floor($Qty12/$BoxCount12);
             $DivisionRem12 = $DivisionTotal12 - $DivisionInt12;
             $RemBal12 = $DivisionRem12 * $BoxCount12;
             $EntryWeight12 = $DivisionInt12 * $BoxWeight12;
             $RemWt12 = $RemBal12 * $Weight12;

             echo '<tr>';
             echo '<td>' . $NavId12 . '</td>';
             echo '<td>' . $Qty12 . '</td>';
             echo '<td>' . $Weight12 . '</td>';
             echo '<td>' . $BoxCount12 . '</td>';
             echo '<td>' . $EntryWeight12 . '</td>';
             echo '<td>' . $DivisionInt12 . '</td>';
             echo '<td>' . $RemBal12 . '</td>';
             echo '<td>' . $RemWt12 . '</td>';
             echo '</tr>';

             if($DivisionInt12 > 0)
             {
             for ($counter = 0; $counter < $DivisionInt12; $counter++)
             {
                 $arrBoxCount[$counterBoxes] = 1;
                 $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeight12,
                      'Units' => 'LB'),
                      'Dimensions' => array('Length' => $BoxLength12,
                      'Width' => $BoxWidth12,
                      'Height' => $BoxHeight12,
                      'Units' => 'IN'));
                 $counterBoxes++;
             }
             }

             }

             // 13 Entry
             if ($EntryFlag13 != "True")
             {
             while($row = mssql_fetch_array($ProductWtBx13))
             {
                 $Weight13 = $row["Weight"];
                 $BoxCount13 = $row["BoxCount"];
                 $BoxWeight13 = $row["BoxWeight"];
                 $NavId13 = $row["PartNumber"];
                 $BoxLength13 = $row["BoxLength"];
                 $BoxWidth13 = $row["BoxWidth"];
                 $BoxHeight13 = $row["BoxHeight"];
             }

             // Calculate the # of Boxes Complete and the Remanent Balance
             $DivisionTotal13 = $Qty13/$BoxCount13;
             $DivisionInt13 = floor($Qty13/$BoxCount13);
             $DivisionRem13 = $DivisionTotal13 - $DivisionInt13;
             $RemBal13 = $DivisionRem13 * $BoxCount13;
             $EntryWeight13 = $DivisionInt13 * $BoxWeight13;
             $RemWt13 = $RemBal13 * $Weight13;

             echo '<tr>';
             echo '<td>' . $NavId13 . '</td>';
             echo '<td>' . $Qty13 . '</td>';
             echo '<td>' . $Weight13 . '</td>';
             echo '<td>' . $BoxCount13 . '</td>';
             echo '<td>' . $EntryWeight13 . '</td>';
             echo '<td>' . $DivisionInt13 . '</td>';
             echo '<td>' . $RemBal13 . '</td>';
             echo '<td>' . $RemWt13 . '</td>';
             echo '</tr>';

             if($DivisionInt13 > 0)
             {
             for ($counter = 0; $counter < $DivisionInt13; $counter++)
             {
                 $arrBoxCount[$counterBoxes] = 1;
                 $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeight13,
                      'Units' => 'LB'),
                      'Dimensions' => array('Length' => $BoxLength13,
                      'Width' => $BoxWidth13,
                      'Height' => $BoxHeight13,
                      'Units' => 'IN'));
                 $counterBoxes++;
             }
             }
             
             }

             // 14 Entry
             if ($EntryFlag14 != "True")
             {
             while($row = mssql_fetch_array($ProductWtBx14))
             {
                 $Weight14 = $row["Weight"];
                 $BoxCount14 = $row["BoxCount"];
                 $BoxWeight14 = $row["BoxWeight"];
                 $NavId14 = $row["PartNumber"];
                 $BoxLength14 = $row["BoxLength"];
                 $BoxWidth14 = $row["BoxWidth"];
                 $BoxHeight14 = $row["BoxHeight"];
             }

             // Calculate the # of Boxes Complete and the Remanent Balance
             $DivisionTotal14 = $Qty14/$BoxCount14;
             $DivisionInt14 = floor($Qty14/$BoxCount14);
             $DivisionRem14 = $DivisionTotal14 - $DivisionInt14;
             $RemBal14 = $DivisionRem14 * $BoxCount14;
             $EntryWeight14 = $DivisionInt14 * $BoxWeight14;
             $RemWt14 = $RemBal14 * $Weight14;

             echo '<tr>';
             echo '<td>' . $NavId14 . '</td>';
             echo '<td>' . $Qty14 . '</td>';
             echo '<td>' . $Weight14 . '</td>';
             echo '<td>' . $BoxCount14 . '</td>';
             echo '<td>' . $EntryWeight14 . '</td>';
             echo '<td>' . $DivisionInt14 . '</td>';
             echo '<td>' . $RemBal14 . '</td>';
             echo '<td>' . $RemWt14 . '</td>';
             echo '</tr>';

             if($DivisionInt14 > 0)
             {
             for ($counter = 0; $counter < $DivisionInt14; $counter++)
             {
                 $arrBoxCount[$counterBoxes] = 1;
                 $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeight14,
                      'Units' => 'LB'),
                      'Dimensions' => array('Length' => $BoxLength14,
                      'Width' => $BoxWidth14,
                      'Height' => $BoxHeight14,
                      'Units' => 'IN'));
                 $counterBoxes++;
             }
             }
             
             }

             // 15 Entry
             if ($EntryFlag15 != "True")
             {
             while($row = mssql_fetch_array($ProductWtBx15))
             {
                 $Weight15 = $row["Weight"];
                 $BoxCount15 = $row["BoxCount"];
                 $BoxWeight15 = $row["BoxWeight"];
                 $NavId15 = $row["PartNumber"];
                 $BoxLength15 = $row["BoxLength"];
                 $BoxWidth15 = $row["BoxWidth"];
                 $BoxHeight15 = $row["BoxHeight"];
             }

             // Calculate the # of Boxes Complete and the Remanent Balance
             $DivisionTotal15 = $Qty15/$BoxCount15;
             $DivisionInt15 = floor($Qty15/$BoxCount15);
             $DivisionRem15 = $DivisionTotal15 - $DivisionInt15;
             $RemBal15 = $DivisionRem15 * $BoxCount15;
             $EntryWeight15 = $DivisionInt15 * $BoxWeight15;
             $RemWt15 = $RemBal15 * $Weight15;

             echo '<tr>';
             echo '<td>' . $NavId15 . '</td>';
             echo '<td>' . $Qty15 . '</td>';
             echo '<td>' . $Weight15 . '</td>';
             echo '<td>' . $BoxCount15 . '</td>';
             echo '<td>' . $EntryWeight15 . '</td>';
             echo '<td>' . $DivisionInt15 . '</td>';
             echo '<td>' . $RemBal15 . '</td>';
             echo '<td>' . $RemWt15 . '</td>';
             echo '</tr>';

             if($DivisionInt15 > 0)
             {
             for ($counter = 0; $counter < $DivisionInt15; $counter++)
             {
                 $arrBoxCount[$counterBoxes] = 1;
                 $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeight15,
                      'Units' => 'LB'),
                      'Dimensions' => array('Length' => $BoxLength15,
                      'Width' => $BoxWidth15,
                      'Height' => $BoxHeight15,
                      'Units' => 'IN'));
                 $counterBoxes++;
             }
             }
             
             }

             // 16 Entry
             if ($EntryFlag16 != "True")
             {
             while($row = mssql_fetch_array($ProductWtBx16))
             {
                 $Weight16 = $row["Weight"];
                 $BoxCount16 = $row["BoxCount"];
                 $BoxWeight16 = $row["BoxWeight"];
                 $NavId16 = $row["PartNumber"];
                 $BoxLength16 = $row["BoxLength"];
                 $BoxWidth16 = $row["BoxWidth"];
                 $BoxHeight16 = $row["BoxHeight"];
             }

             // Calculate the # of Boxes Complete and the Remanent Balance
             $DivisionTotal16 = $Qty16/$BoxCount16;
             $DivisionInt16 = floor($Qty16/$BoxCount16);
             $DivisionRem16 = $DivisionTotal16 - $DivisionInt16;
             $RemBal16 = $DivisionRem16 * $BoxCount16;
             $EntryWeight16 = $DivisionInt16 * $BoxWeight16;
             $RemWt16 = $RemBal16 * $Weight16;

             echo '<tr>';
             echo '<td>' . $NavId16 . '</td>';
             echo '<td>' . $Qty16 . '</td>';
             echo '<td>' . $Weight16 . '</td>';
             echo '<td>' . $BoxCount16 . '</td>';
             echo '<td>' . $EntryWeight16 . '</td>';
             echo '<td>' . $DivisionInt16 . '</td>';
             echo '<td>' . $RemBal16 . '</td>';
             echo '<td>' . $RemWt16 . '</td>';
             echo '</tr>';

             if($DivisionInt16 > 0)
             {
             for ($counter = 0; $counter < $DivisionInt16; $counter++)
             {
                 $arrBoxCount[$counterBoxes] = 1;
                 $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeight16,
                      'Units' => 'LB'),
                      'Dimensions' => array('Length' => $BoxLength16,
                      'Width' => $BoxWidth16,
                      'Height' => $BoxHeight16,
                      'Units' => 'IN'));
                 $counterBoxes++;
             }
             }
             
             }

             // 17 Entry
             if ($EntryFlag17 != "True")
             {
             while($row = mssql_fetch_array($ProductWtBx17))
             {
                 $Weight17 = $row["Weight"];
                 $BoxCount17 = $row["BoxCount"];
                 $BoxWeight17 = $row["BoxWeight"];
                 $NavId17 = $row["PartNumber"];
                 $BoxLength17 = $row["BoxLength"];
                 $BoxWidth17 = $row["BoxWidth"];
                 $BoxHeight17 = $row["BoxHeight"];
             }

             // Calculate the # of Boxes Complete and the Remanent Balance
             $DivisionTotal17 = $Qty17/$BoxCount17;
             $DivisionInt17 = floor($Qty17/$BoxCount17);
             $DivisionRem17 = $DivisionTotal17 - $DivisionInt17;
             $RemBal17 = $DivisionRem17 * $BoxCount17;
             $EntryWeight17 = $DivisionInt17 * $BoxWeight17;
             $RemWt17 = $RemBal17 * $Weight17;

             echo '<tr>';
             echo '<td>' . $NavId17 . '</td>';
             echo '<td>' . $Qty17 . '</td>';
             echo '<td>' . $Weight17 . '</td>';
             echo '<td>' . $BoxCount17 . '</td>';
             echo '<td>' . $EntryWeight17 . '</td>';
             echo '<td>' . $DivisionInt17 . '</td>';
             echo '<td>' . $RemBal17 . '</td>';
             echo '<td>' . $RemWt17 . '</td>';
             echo '</tr>';

             if($DivisionInt17 > 0)
             {
             for ($counter = 0; $counter < $DivisionInt17; $counter++)
             {
                 $arrBoxCount[$counterBoxes] = 1;
                 $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeight17,
                      'Units' => 'LB'),
                      'Dimensions' => array('Length' => $BoxLength17,
                      'Width' => $BoxWidth17,
                      'Height' => $BoxHeight17,
                      'Units' => 'IN'));
                 $counterBoxes++;
             }
             }
             
             }

             // 18 Entry
             if ($EntryFlag18 != "True")
             {
             while($row = mssql_fetch_array($ProductWtBx18))
             {
                 $Weight18 = $row["Weight"];
                 $BoxCount18 = $row["BoxCount"];
                 $BoxWeight18 = $row["BoxWeight"];
                 $NavId18 = $row["PartNumber"];
                 $BoxLength18 = $row["BoxLength"];
                 $BoxWidth18 = $row["BoxWidth"];
                 $BoxHeight18 = $row["BoxHeight"];
             }

             // Calculate the # of Boxes Complete and the Remanent Balance
             $DivisionTotal18 = $Qty18/$BoxCount18;
             $DivisionInt18 = floor($Qty18/$BoxCount18);
             $DivisionRem18 = $DivisionTotal18 - $DivisionInt18;
             $RemBal18 = $DivisionRem18 * $BoxCount18;
             $EntryWeight18 = $DivisionInt18 * $BoxWeight18;
             $RemWt18 = $RemBal18 * $Weight18;

             echo '<tr>';
             echo '<td>' . $NavId18 . '</td>';
             echo '<td>' . $Qty18 . '</td>';
             echo '<td>' . $Weight18 . '</td>';
             echo '<td>' . $BoxCount18 . '</td>';
             echo '<td>' . $EntryWeight18 . '</td>';
             echo '<td>' . $DivisionInt18 . '</td>';
             echo '<td>' . $RemBal18 . '</td>';
             echo '<td>' . $RemWt18 . '</td>';
             echo '</tr>';

             if($DivisionInt18 > 0)
             {
             for ($counter = 0; $counter < $DivisionInt18; $counter++)
             {
                 $arrBoxCount[$counterBoxes] = 1;
                 $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeight18,
                      'Units' => 'LB'),
                      'Dimensions' => array('Length' => $BoxLength18,
                      'Width' => $BoxWidth18,
                      'Height' => $BoxHeight18,
                      'Units' => 'IN'));
                 $counterBoxes++;
             }
             }
             
             }

             // 19 Entry
             if ($EntryFlag19 != "True")
             {
             while($row = mssql_fetch_array($ProductWtBx19))
             {
                 $Weight19 = $row["Weight"];
                 $BoxCount19 = $row["BoxCount"];
                 $BoxWeight19 = $row["BoxWeight"];
                 $NavId19 = $row["PartNumber"];
                 $BoxLength19 = $row["BoxLength"];
                 $BoxWidth19 = $row["BoxWidth"];
                 $BoxHeight19 = $row["BoxHeight"];
             }

             // Calculate the # of Boxes Complete and the Remanent Balance
             $DivisionTotal19 = $Qty19/$BoxCount19;
             $DivisionInt19 = floor($Qty19/$BoxCount19);
             $DivisionRem19 = $DivisionTotal19 - $DivisionInt19;
             $RemBal19 = $DivisionRem19 * $BoxCount19;
             $EntryWeight19 = $DivisionInt19 * $BoxWeight19;
             $RemWt19 = $RemBal19 * $Weight19;

             echo '<tr>';
             echo '<td>' . $NavId19 . '</td>';
             echo '<td>' . $Qty19 . '</td>';
             echo '<td>' . $Weight19 . '</td>';
             echo '<td>' . $BoxCount19 . '</td>';
             echo '<td>' . $EntryWeight19 . '</td>';
             echo '<td>' . $DivisionInt19 . '</td>';
             echo '<td>' . $RemBal19 . '</td>';
             echo '<td>' . $RemWt19 . '</td>';
             echo '</tr>';

             if($DivisionInt19 > 0)
             {
             for ($counter = 0; $counter < $DivisionInt19; $counter++)
             {
                 $arrBoxCount[$counterBoxes] = 1;
                 $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeight19,
                      'Units' => 'LB'),
                      'Dimensions' => array('Length' => $BoxLength19,
                      'Width' => $BoxWidth19,
                      'Height' => $BoxHeight19,
                      'Units' => 'IN'));
                 $counterBoxes++;
             }
             }
             
             }

             // 20 Entry
             if ($EntryFlag20 != "True")
             {
             while($row = mssql_fetch_array($ProductWtBx20))
             {
                 $Weight20 = $row["Weight"];
                 $BoxCount20 = $row["BoxCount"];
                 $BoxWeight20 = $row["BoxWeight"];
                 $NavId20 = $row["PartNumber"];
                 $BoxLength20 = $row["BoxLength"];
                 $BoxWidth20 = $row["BoxWidth"];
                 $BoxHeight20 = $row["BoxHeight"];
             }

             // Calculate the # of Boxes Complete and the Remanent Balance
             $DivisionTotal20 = $Qty20/$BoxCount20;
             $DivisionInt20 = floor($Qty20/$BoxCount20);
             $DivisionRem20 = $DivisionTotal20 - $DivisionInt20;
             $RemBal20 = $DivisionRem20 * $BoxCount20;
             $EntryWeight20 = $DivisionInt20 * $BoxWeight20;
             $RemWt20 = $RemBal20 * $Weight20;

             echo '<tr>';
             echo '<td>' . $NavId20 . '</td>';
             echo '<td>' . $Qty20 . '</td>';
             echo '<td>' . $Weight20 . '</td>';
             echo '<td>' . $BoxCount20 . '</td>';
             echo '<td>' . $EntryWeight20 . '</td>';
             echo '<td>' . $DivisionInt20 . '</td>';
             echo '<td>' . $RemBal20 . '</td>';
             echo '<td>' . $RemWt20 . '</td>';
             echo '</tr>';

             if($DivisionInt20 > 0)
             {
             for ($counter = 0; $counter < $DivisionInt20; $counter++)
             {
                 $arrBoxCount[$counterBoxes] = 1;
                 $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeight20,
                      'Units' => 'LB'),
                      'Dimensions' => array('Length' => $BoxLength20,
                      'Width' => $BoxWidth20,
                      'Height' => $BoxHeight20,
                      'Units' => 'IN'));
                 $counterBoxes++;
             }
             }
             
             }

         ?>

         </table>

         <table width="900" cellpadding="3" cellspacing="0" style="margin:10px;">
         <tr><th width="*" style="text-align:left">Totals</th></tr>
         </table>

         <table width="900" cellpadding="3" cellspacing="0" style="margin:10px;">

         <tr>
	        <th width="*" style="text-align:left"># Boxes Complete</th>
            <th width="120" style="text-align:left">Total Rem Weight</th>
            <th width="120" style="text-align:left">Total Box Count</th>
         </tr>

         <?php
         
           $NoBoxesComplete =  $DivisionInt1 + $DivisionInt2 + $DivisionInt3 + $DivisionInt4 + $DivisionInt5 + $DivisionInt6 + $DivisionInt7 + $DivisionInt8 + $DivisionInt9 + $DivisionInt10 + $DivisionInt11 + $DivisionInt12 + $DivisionInt13 + $DivisionInt14 + $DivisionInt15 + $DivisionInt16 + $DivisionInt17 + $DivisionInt18 + $DivisionInt19 + $DivisionInt20;
           $TotalRemWt =  $RemWt1 + $RemWt2 + $RemWt3 + $RemWt4 + $RemWt5 + $RemWt6 + $RemWt7 + $RemWt8 + $RemWt9 + $RemWt10 + $RemWt11 + $RemWt12 + $RemWt13 + $RemWt14 + $RemWt15 + $RemWt16 + $RemWt17 + $RemWt18 + $RemWt19 + $RemWt20;
           $EntryWtComplete =  $EntryWeight1 + $EntryWeight2 + $EntryWeight3 + $EntryWeight4 + $EntryWeight5 + $EntryWeight6 + $EntryWeight7 + $EntryWeight8 + $EntryWeight9 + $EntryWeight10 + $EntryWeight11 + $EntryWeight12 + $EntryWeight13 + $EntryWeight14 + $EntryWeight15 + $EntryWeight16 + $EntryWeight17 + $EntryWeight18 + $EntryWeight19 + $EntryWeight20;

           echo $TotalRemWt;

           if($TotalRemWt > 0)
           {
               if($TotalRemWt > 0 && $TotalRemWt < 145)
               {
                   $TotalBoxCount = $NoBoxesComplete + 1;
               }
               else if($TotalRemWt > 144 && $TotalRemWt < 289)
               {
                   $TotalBoxCount = $NoBoxesComplete + 2;
               }
               else if($TotalRemWt > 288)
               {
                   $TotalBoxCount = $NoBoxesComplete + 3;
               }

               /*
               $arrBoxes[1] = array('Weight' => array('Value' => $TotalRemWt,
                      'Units' => 'LB'),
                      'Dimensions' => array('Length' => 12,
                      'Width' => 11,
                      'Height' => 9,
                      'Units' => 'IN'));
               */

               // $counterBoxes++;
               $arrBoxCount[$counterBoxes] = 1;
               $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $TotalRemWt,
                      'Units' => 'LB'),
                      'Dimensions' => array('Length' => 12,
                      'Width' => 11,
                      'Height' => 9,
                      'Units' => 'IN'));
           }
           else
           {
               $TotalBoxCount = $NoBoxesComplete;
           }

           echo '<tr>';
           echo '<td>' . $NoBoxesComplete . '</td>';
           echo '<td>' . $TotalRemWt . '</td>';
           echo '<td>' . $TotalBoxCount . '</td>';
           echo '</tr>';

         ?>

         </table>

         <table width="900" cellpadding="3" cellspacing="0" style="margin:10px;">
         <tr><th width="*" style="text-align:left">FedEx Shipping Options</th></tr>
         </table>

         <table width="900" cellpadding="3" cellspacing="0" style="margin:10px;">

         <?php
         
             ini_set("soap.wsdl_cache_enabled", "0");
             $client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information
             $request['WebAuthenticationDetail'] = array('UserCredential' =>
             array('Key' => 'hRnybIZX3PKne28q', 'Password' => 'yDjVeSkK252f1kFblX1AXy31b')); // Replace 'XXX' and 'YYY' with FedEx provided credentials
             $request['ClientDetail'] = array('AccountNumber' => '462227000', 'MeterNumber' => '100677102');// Replace 'XXX' with your account and meter number
             $request['TransactionDetail'] = array('CustomerTransactionId' => ' *** Rate Available Services Request v8 using PHP ***');
             $request['Version'] = array('ServiceId' => 'crs', 'Major' => '8', 'Intermediate' => '0', 'Minor' => '0');
             $request['ReturnTransitAndCommit'] = true;
             $request['RequestedShipment']['DropoffType'] = 'REGULAR_PICKUP'; // valid values REGULAR_PICKUP, REQUEST_COURIER, ...
             $request['RequestedShipment']['ShipTimestamp'] = date('c');
             $request['RequestedShipment']['Shipper'] = array('Address' => array(
                                          'StreetLines' => array('701 N. Green Valley Parkway St # 200'), // Origin details
                                          'City' => 'Henderson',
                                          'StateOrProvinceCode' => 'NV',
                                          'PostalCode' => '89074',
                                          'CountryCode' => 'US'));
             $request['RequestedShipment']['Recipient'] = array('Address' => array (
                                          'StreetLines' => array('123 Main St'), // Destination details
                                          'City' => $_POST["City"],
                                          'StateOrProvinceCode' => $_POST["State"],
                                          'PostalCode' => $_POST["Zip"],
                                          'CountryCode' => $_POST["Country"]));
             $request['RequestedShipment']['ShippingChargesPayment'] = array('PaymentType' => 'SENDER',
                                          'Payor' => array('AccountNumber' => '462227000', // Replace "XXX" with payor's account number
                                          'CountryCode' => 'US'));
             $request['RequestedShipment']['RateRequestTypes'] = 'ACCOUNT';
             $request['RequestedShipment']['PackageCount'] = $TotalBoxCount;
             // $request['RequestedShipment']['PackageCount'] = $arrBoxCount;
             $request['RequestedShipment']['PackageDetail'] = 'INDIVIDUAL_PACKAGES';
             $request['RequestedShipment']['RequestedPackageLineItems'] = $arrBoxes;

             // print_r($arrBoxCount);
             // echo '<br/>';
             // echo '<br/>';
             // var_dump($arrBoxCount);
             // echo '<br/>';
             // echo '<br/>';
             // print_r($arrBoxes);
             // echo '<br/>';
             // echo '<br/>';
             // var_dump($arrBoxes);
             // echo '<br/>';
             // echo '<br/>';
             // echo '<br/>';
             // echo '<br/>';

             try
             {
                 $response = $client -> getRates($request);
                 if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR')
                 {
                      foreach ($response -> RateReplyDetails as $rateReply)
                      {
                          $serviceType = $rateReply -> ServiceType;
                          if($serviceType == "FEDEX_GROUND")
                          {
                              if(is_array($response -> RateReplyDetails))
                              {
                                  $tnfec = $rateReply -> RatedShipmentDetails;
                                  foreach ($tnfec as $wha)
                                  {
                                      $ResultCode1 = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                                  }
                              }
                              echo '<tr><td>FedEx Ground ' . ' ($' . number_format($ResultCode1, 2, '.', '') . ')</td></tr>';
                              $blnIsError = 0;
                          }
                          elseif($serviceType == "FEDEX_2_DAY")
                          {
                              if(is_array($response -> RateReplyDetails))
                              {
                                  $tnfec = $rateReply -> RatedShipmentDetails;
                                  foreach ($tnfec as $wha)
                                  {
                                      $ResultCode2 = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                                  }
                              }
                              echo '<tr><td>FedEx 2Day ' . ' ($' . number_format($ResultCode2, 2, '.', '') . ')</td></tr>';
                              $blnIsError = 0;
                          }
                          elseif($serviceType == "FEDEX_EXPRESS_SAVER")
                          {
                              if(is_array($response -> RateReplyDetails))
                              {
                                  $tnfec = $rateReply -> RatedShipmentDetails;
                                  foreach ($tnfec as $wha)
                                  {
                                      $ResultCode3 = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                                  }
                              }
                              echo '<tr><td>FedEx Express Saver ' . ' ($' . number_format($ResultCode3, 2, '.', '') . ')</td></tr>';
                              $blnIsError = 0;
                          }
                          elseif($serviceType == "STANDARD_OVERNIGHT")
                          {
                              if(is_array($response -> RateReplyDetails))
                              {
                                  $tnfec = $rateReply -> RatedShipmentDetails;
                                  foreach ($tnfec as $wha)
                                  {
                                      $ResultCode4 = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                                  }
                              }
                              echo '<tr><td>FedEx Next Day Overnight ' . ' ($' . number_format($ResultCode4, 2, '.', '') . ')</td></tr>';
                              $blnIsError = 0;
                          }
                          elseif($serviceType == "FEDEX_1_DAY_FREIGHT")
                          {
                              if(is_array($response -> RateReplyDetails))
                              {
                                  $tnfec = $rateReply -> RatedShipmentDetails;
                                  foreach ($tnfec as $wha)
                                  {
                                      $ResultCode5 = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                                  }
                              }
                              echo '<tr><td>FedEx 1 Day Freight ' . ' ($' . number_format($ResultCode5, 2, '.', '') . ')</td></tr>';
                              $blnIsError = 0;
                          }
                          elseif($serviceType == "FEDEX_2_DAY_FREIGHT")
                          {
                              if(is_array($response -> RateReplyDetails))
                              {
                                  $tnfec = $rateReply -> RatedShipmentDetails;
                                  foreach ($tnfec as $wha)
                                  {
                                      $ResultCode6 = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                                  }
                              }
                              echo '<tr><td>FedEx 2 Day Freight ' . ' ($' . number_format($ResultCode6, 2, '.', '') . ')</td></tr>';
                              $blnIsError = 0;
                          }
                          elseif($serviceType == "FEDEX_3_DAY_FREIGHT")
                          {
                              if(is_array($response -> RateReplyDetails))
                              {
                                  $tnfec = $rateReply -> RatedShipmentDetails;
                                  foreach ($tnfec as $wha)
                                  {
                                      $ResultCode7 = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                                  }
                              }
                              echo '<tr><td>FedEx 3 Day Freight ' . ' ($' . number_format($ResultCode7, 2, '.', '') . ')</td></tr>';
                              $blnIsError = 0;
                          }
                          elseif($serviceType == "INTERNATIONAL_PRIORITY")
                          {
                              if(is_array($response -> RateReplyDetails))
                              {
                                  $tnfec = $rateReply -> RatedShipmentDetails;
                                  foreach ($tnfec as $wha)
                                  {
                                      $ResultCode8 = $wha -> ShipmentRateDetail -> TotalNetFedExCharge->Amount;
                                  }
                              }
                              echo '<tr><td>FedEx International Priority ' . ' ($' . number_format($ResultCode8, 2, '.', '') . ')</td></tr>';
                              $blnIsError = 0;
                          }
                          elseif($serviceType == "INTERNATIONAL_ECONOMY")
                          {
                              if(is_array($response -> RateReplyDetails))
                              {
                                  $tnfec = $rateReply -> RatedShipmentDetails;
                                  foreach ($tnfec as $wha)
                                  {
                                      $ResultCode9 = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                                  }
                              }
                              echo '<tr><td>FedEx International Economy ' . ' ($' . number_format($ResultCode9, 2, '.', '') . ')</td></tr>';
                              $blnIsError = 0;
                          }
                          elseif($serviceType == "INTERNATIONAL_PRIORITY_FREIGHT")
                          {
                              if(is_array($response -> RateReplyDetails))
                              {
                                  $tnfec = $rateReply -> RatedShipmentDetails;
                                  foreach ($tnfec as $wha)
                                  {
                                      $ResultCode10 = $wha -> ShipmentRateDetail -> TotalNetFedExCharge->Amount;
                                  }
                              }
                              echo '<tr><td>FedEx International Priority Freight' . ' ($' . number_format($ResultCode10, 2, '.', '') . ')</td></tr>';
                              $blnIsError = 0;
                          }
                          elseif($serviceType == "INTERNATIONAL_ECONOMY_FREIGHT")
                          {
                              if(is_array($response -> RateReplyDetails))
                              {
                                  $tnfec = $rateReply -> RatedShipmentDetails;
                                  foreach ($tnfec as $wha)
                                  {
                                      $ResultCode11 = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                                  }
                              }
                              echo '<tr><td>FedEx International Economy Freight' . ' ($' . number_format($ResultCode11, 2, '.', '') . ')</td></tr>';
                              $blnIsError = 0;
                          }
                          elseif($serviceType == "INTERNATIONAL_FIRST")
                          {
                              if(is_array($response -> RateReplyDetails))
                              {
                                  $tnfec = $rateReply -> RatedShipmentDetails;
                                  foreach ($tnfec as $wha)
                                  {
                                      $ResultCode12 = $wha -> ShipmentRateDetail -> TotalNetFedExCharge -> Amount;
                                  }
                              }
                              echo '<tr><td>FedEx International First ' . ' ($' . number_format($ResultCode12, 2, '.', '') . ')</td></tr>';
                              $blnIsError = 0;
                          }
                      }
                 }
                 else
                 {
                      foreach ($response -> Notifications as $notification)
                      {
                          if(is_array($response -> Notifications))
                          {
                              $blnIsError = 1;
                          }
                          else
                          {
                              $blnIsError = 1;
                          }
                      }
                 }
             }
             catch (SoapFault $exception)
             {
                $blnIsError = 1;
                
                echo $blnIsError;
             }

         ?>

         </table>

         <table width="900" cellpadding="3" cellspacing="0" style="margin:10px;">
         <tr><th width="*" style="text-align:left"><a href="boxes.php">Go Back to Enter Boxes</a></th></tr>
         </table>

		</div>

	</div>

</body>

</html>
