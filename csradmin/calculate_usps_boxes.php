<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php

require_once("../modules/session.php");
require_once("../modules/db.php");
require_once("../modules/xmlparser.php");
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
$RemBal1 = 0;
$RemBal2 = 0;
$RemBal3 = 0;
$RemBal4 = 0;
$RemBal5 = 0;
$RemBal6 = 0;
$RemBal7 = 0;
$RemBal8 = 0;
$RemBal9 = 0;
$RemBal10 = 0;
$RemBal11 = 0;
$RemBal12 = 0;
$RemBal13 = 0;
$RemBal14 = 0;
$RemBal15 = 0;
$RemBal16 = 0;
$RemBal17 = 0;
$RemBal18 = 0;
$RemBal19 = 0;
$RemBal20 = 0;
$ItmQtyWt1 = 0;
$ItmQtyWt2 = 0;
$ItmQtyWt3 = 0;
$ItmQtyWt4 = 0;
$ItmQtyWt5 = 0;
$ItmQtyWt6 = 0;
$ItmQtyWt7 = 0;
$ItmQtyWt8 = 0;
$ItmQtyWt9 = 0;
$ItmQtyWt10 = 0;
$ItmQtyWt11 = 0;
$ItmQtyWt12 = 0;
$ItmQtyWt13 = 0;
$ItmQtyWt14 = 0;
$ItmQtyWt15 = 0;
$ItmQtyWt16 = 0;
$ItmQtyWt17 = 0;
$ItmQtyWt18 = 0;
$ItmQtyWt19 = 0;
$ItmQtyWt20 = 0;

// GMC - 09/06/12 - To handle Bundles in the Box Calculation
$_SESSION["Bundle1"] = "";
$_SESSION["Bundle2"] = "";
$_SESSION["Bundle3"] = "";
$_SESSION["Bundle4"] = "";
$_SESSION["Bundle5"] = "";
$_SESSION["Bundle6"] = "";
$_SESSION["Bundle7"] = "";
$_SESSION["Bundle8"] = "";
$_SESSION["Bundle9"] = "";
$_SESSION["Bundle10"] = "";
$_SESSION["Bundle11"] = "";
$_SESSION["Bundle12"] = "";
$_SESSION["Bundle13"] = "";
$_SESSION["Bundle14"] = "";
$_SESSION["Bundle15"] = "";
$_SESSION["Bundle16"] = "";
$_SESSION["Bundle17"] = "";
$_SESSION["Bundle18"] = "";
$_SESSION["Bundle19"] = "";
$_SESSION["Bundle20"] = "";

// GMC - 10/03/12 - Display Bundle Item Number, Description and Qty
$_SESSION["BundlesInformation"] = "";

// Now get the Weight and BoxCount for each entry
if (isset($_POST['ItemID1']) && $_POST['ItemID1'] != 0)
{
    $ProductWtBx1 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID1);

    // GMC - 09/06/12 - To handle Bundles in the Box Calculation
    $Bundle1 = mssql_query("SELECT ProductID FROM tblBundles WHERE ProductID = " . $ProductID1);

    while($row = mssql_fetch_array($Bundle1))
    {
        $_SESSION["Bundle1"] = $row["ProductID"];
    }
}
else
{
    $EntryFlag1 = "True";
}

if (isset($_POST['ItemID2']) && $_POST['ItemID2'] != 0)
{
    $ProductWtBx2 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID2);

    // GMC - 09/06/12 - To handle Bundles in the Box Calculation
    $Bundle2 = mssql_query("SELECT ProductID FROM tblBundles WHERE ProductID = " . $ProductID2);

    while($row = mssql_fetch_array($Bundle2))
    {
        $_SESSION["Bundle2"] = $row["ProductID"];
    }
}
else
{
    $EntryFlag2 = "True";
}

if (isset($_POST['ItemID3']) && $_POST['ItemID3'] != 0)
{
    $ProductWtBx3 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID3);

    // GMC - 09/06/12 - To handle Bundles in the Box Calculation
    $Bundle3 = mssql_query("SELECT ProductID FROM tblBundles WHERE ProductID = " . $ProductID3);

    while($row = mssql_fetch_array($Bundle3))
    {
        $_SESSION["Bundle3"] = $row["ProductID"];
    }
}
else
{
    $EntryFlag3 = "True";
}

if (isset($_POST['ItemID4']) && $_POST['ItemID4'] != 0)
{
    $ProductWtBx4 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID4);

    // GMC - 09/06/12 - To handle Bundles in the Box Calculation
    $Bundle4 = mssql_query("SELECT ProductID FROM tblBundles WHERE ProductID = " . $ProductID4);

    while($row = mssql_fetch_array($Bundle4))
    {
        $_SESSION["Bundle4"] = $row["ProductID"];
    }
}
else
{
    $EntryFlag4 = "True";
}

if (isset($_POST['ItemID5']) && $_POST['ItemID5'] != 0)
{
    $ProductWtBx5 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID5);

    // GMC - 09/06/12 - To handle Bundles in the Box Calculation
    $Bundle5 = mssql_query("SELECT ProductID FROM tblBundles WHERE ProductID = " . $ProductID5);

    while($row = mssql_fetch_array($Bundle5))
    {
        $_SESSION["Bundle5"] = $row["ProductID"];
    }
}
else
{
    $EntryFlag5 = "True";
}

if (isset($_POST['ItemID6']) && $_POST['ItemID6'] != 0)
{
    $ProductWtBx6 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID6);

    // GMC - 09/06/12 - To handle Bundles in the Box Calculation
    $Bundle6 = mssql_query("SELECT ProductID FROM tblBundles WHERE ProductID = " . $ProductID6);

    while($row = mssql_fetch_array($Bundle6))
    {
        $_SESSION["Bundle6"] = $row["ProductID"];
    }
}
else
{
    $EntryFlag6 = "True";
}

if (isset($_POST['ItemID7']) && $_POST['ItemID7'] != 0)
{
    $ProductWtBx7 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID7);

    // GMC - 09/06/12 - To handle Bundles in the Box Calculation
    $Bundle7 = mssql_query("SELECT ProductID FROM tblBundles WHERE ProductID = " . $ProductID7);

    while($row = mssql_fetch_array($Bundle7))
    {
        $_SESSION["Bundle7"] = $row["ProductID"];
    }
}
else
{
    $EntryFlag7 = "True";
}

if (isset($_POST['ItemID8']) && $_POST['ItemID8'] != 0)
{
    $ProductWtBx8 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID8);

    // GMC - 09/06/12 - To handle Bundles in the Box Calculation
    $Bundle8 = mssql_query("SELECT ProductID FROM tblBundles WHERE ProductID = " . $ProductID8);

    while($row = mssql_fetch_array($Bundle8))
    {
        $_SESSION["Bundle8"] = $row["ProductID"];
    }
}
else
{
    $EntryFlag8 = "True";
}

if (isset($_POST['ItemID9']) && $_POST['ItemID9'] != 0)
{
    $ProductWtBx9 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID9);

    // GMC - 09/06/12 - To handle Bundles in the Box Calculation
    $Bundle9 = mssql_query("SELECT ProductID FROM tblBundles WHERE ProductID = " . $ProductID9);

    while($row = mssql_fetch_array($Bundle9))
    {
        $_SESSION["Bundle9"] = $row["ProductID"];
    }
}
else
{
    $EntryFlag9 = "True";
}

if (isset($_POST['ItemID10']) && $_POST['ItemID10'] != 0)
{
    $ProductWtBx10 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID10);

    // GMC - 09/06/12 - To handle Bundles in the Box Calculation
    $Bundle10 = mssql_query("SELECT ProductID FROM tblBundles WHERE ProductID = " . $ProductID10);

    while($row = mssql_fetch_array($Bundle10))
    {
        $_SESSION["Bundle10"] = $row["ProductID"];
    }
}
else
{
    $EntryFlag10 = "True";
}

if (isset($_POST['ItemID11']) && $_POST['ItemID11'] != 0)
{
    $ProductWtBx11 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID11);

    // GMC - 09/06/12 - To handle Bundles in the Box Calculation
    $Bundle11 = mssql_query("SELECT ProductID FROM tblBundles WHERE ProductID = " . $ProductID11);

    while($row = mssql_fetch_array($Bundle11))
    {
        $_SESSION["Bundle11"] = $row["ProductID"];
    }
}
else
{
    $EntryFlag11 = "True";
}

if (isset($_POST['ItemID12']) && $_POST['ItemID12'] != 0)
{
    $ProductWtBx12 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID12);

    // GMC - 09/06/12 - To handle Bundles in the Box Calculation
    $Bundle12 = mssql_query("SELECT ProductID FROM tblBundles WHERE ProductID = " . $ProductID12);

    while($row = mssql_fetch_array($Bundle12))
    {
        $_SESSION["Bundle12"] = $row["ProductID"];
    }
}
else
{
    $EntryFlag12 = "True";
}

if (isset($_POST['ItemID13']) && $_POST['ItemID13'] != 0)
{
    $ProductWtBx13 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID13);

    // GMC - 09/06/12 - To handle Bundles in the Box Calculation
    $Bundle13 = mssql_query("SELECT ProductID FROM tblBundles WHERE ProductID = " . $ProductID13);

    while($row = mssql_fetch_array($Bundle13))
    {
        $_SESSION["Bundle13"] = $row["ProductID"];
    }
}
else
{
    $EntryFlag13 = "True";
}

if (isset($_POST['ItemID14']) && $_POST['ItemID14'] != 0)
{
    $ProductWtBx14 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID14);

    // GMC - 09/06/12 - To handle Bundles in the Box Calculation
    $Bundle14 = mssql_query("SELECT ProductID FROM tblBundles WHERE ProductID = " . $ProductID14);

    while($row = mssql_fetch_array($Bundle14))
    {
        $_SESSION["Bundle14"] = $row["ProductID"];
    }
}
else
{
    $EntryFlag14 = "True";
}

if (isset($_POST['ItemID15']) && $_POST['ItemID15'] != 0)
{
    $ProductWtBx15 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID15);

    // GMC - 09/06/12 - To handle Bundles in the Box Calculation
    $Bundle15 = mssql_query("SELECT ProductID FROM tblBundles WHERE ProductID = " . $ProductID15);

    while($row = mssql_fetch_array($Bundle15))
    {
        $_SESSION["Bundle15"] = $row["ProductID"];
    }
}
else
{
    $EntryFlag15 = "True";
}

if (isset($_POST['ItemID16']) && $_POST['ItemID16'] != 0)
{
    $ProductWtBx16 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID16);

    // GMC - 09/06/12 - To handle Bundles in the Box Calculation
    $Bundle16 = mssql_query("SELECT ProductID FROM tblBundles WHERE ProductID = " . $ProductID16);

    while($row = mssql_fetch_array($Bundle16))
    {
        $_SESSION["Bundle16"] = $row["ProductID"];
    }
}
else
{
    $EntryFlag16 = "True";
}

if (isset($_POST['ItemID17']) && $_POST['ItemID17'] != 0)
{
    $ProductWtBx17 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID17);

    // GMC - 09/06/12 - To handle Bundles in the Box Calculation
    $Bundle17 = mssql_query("SELECT ProductID FROM tblBundles WHERE ProductID = " . $ProductID17);

    while($row = mssql_fetch_array($Bundle17))
    {
        $_SESSION["Bundle17"] = $row["ProductID"];
    }
}
else
{
    $EntryFlag17 = "True";
}

if (isset($_POST['ItemID18']) && $_POST['ItemID18'] != 0)
{
    $ProductWtBx18 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID18);

    // GMC - 09/06/12 - To handle Bundles in the Box Calculation
    $Bundle18 = mssql_query("SELECT ProductID FROM tblBundles WHERE ProductID = " . $ProductID18);

    while($row = mssql_fetch_array($Bundle18))
    {
        $_SESSION["Bundle18"] = $row["ProductID"];
    }
}
else
{
    $EntryFlag18 = "True";
}

if (isset($_POST['ItemID19']) && $_POST['ItemID19'] != 0)
{
    $ProductWtBx19 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID19);

    // GMC - 09/06/12 - To handle Bundles in the Box Calculation
    $Bundle19 = mssql_query("SELECT ProductID FROM tblBundles WHERE ProductID = " . $ProductID19);

    while($row = mssql_fetch_array($Bundle19))
    {
        $_SESSION["Bundle19"] = $row["ProductID"];
    }
}
else
{
    $EntryFlag19 = "True";
}

if (isset($_POST['ItemID20']) && $_POST['ItemID20'] != 0)
{
    $ProductWtBx20 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $ProductID20);

    // GMC - 09/06/12 - To handle Bundles in the Box Calculation
    $Bundle20 = mssql_query("SELECT ProductID FROM tblBundles WHERE ProductID = " . $ProductID20);

    while($row = mssql_fetch_array($Bundle20))
    {
        $_SESSION["Bundle20"] = $row["ProductID"];
    }
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
            <th width="120" style="text-align:left">Unit WT</th>
            <th width="120" style="text-align:left">Box Count</th>
            <th width="100" style="text-align:left;">Box WT</th>
            <th width="100" style="text-align:left"># Boxes</th>
            <th width="100" style="text-align:left;">Rem Bal</th>
            <th width="100" style="text-align:left;">Rem WT</th>
            <th width="100" style="text-align:left;">ITM QTY WT</th>
         </tr>

         <?php

             // 1 Entry
             if ($EntryFlag1 != "True" && $_SESSION["Bundle1"] == "")
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

                 // $EntryWeight1 = $DivisionInt1 * $BoxWeight1;
                 $EntryWeight1 = 1 * $BoxWeight1;

                 $RemWt1 = $RemBal1 * $Weight1;

                 // ITM QTY WT calculation
                 $ItmQtyWt1 = ($DivisionInt1 * $EntryWeight1) + $RemWt1;

                 echo '<tr>';
                 echo '<td>' . $NavId1 . '</td>';
                 echo '<td>' . $Qty1 . '</td>';
                 echo '<td>' . $Weight1 . '</td>';
                 echo '<td>' . $BoxCount1 . '</td>';
                 echo '<td>' . $EntryWeight1 . '</td>';
                 echo '<td>' . $DivisionInt1 . '</td>';
                 echo '<td>' . $RemBal1 . '</td>';
                 echo '<td>' . $RemWt1 . '</td>';
                 echo '<td>' . $ItmQtyWt1 . '</td>';
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
             if ($EntryFlag2 != "True" && $_SESSION["Bundle2"] == "")
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

                 // $EntryWeight2 = $DivisionInt2 * $BoxWeight2;
                 $EntryWeight2 = 1 * $BoxWeight2;

                 $RemWt2 = $RemBal2 * $Weight2;

                 // ITM QTY WT calculation
                 $ItmQtyWt2 = ($DivisionInt2 * $EntryWeight2) + $RemWt2;

                 echo '<tr>';
                 echo '<td>' . $NavId2 . '</td>';
                 echo '<td>' . $Qty2 . '</td>';
                 echo '<td>' . $Weight2 . '</td>';
                 echo '<td>' . $BoxCount2 . '</td>';
                 echo '<td>' . $EntryWeight2 . '</td>';
                 echo '<td>' . $DivisionInt2 . '</td>';
                 echo '<td>' . $RemBal2 . '</td>';
                 echo '<td>' . $RemWt2 . '</td>';
                 echo '<td>' . $ItmQtyWt2 . '</td>';
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
             if ($EntryFlag3 != "True" && $_SESSION["Bundle3"] == "")
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

             // $EntryWeight3 = $DivisionInt3 * $BoxWeight3;
             $EntryWeight3 = 1 * $BoxWeight3;

             $RemWt3 = $RemBal3 * $Weight3;

             // ITM QTY WT calculation
             $ItmQtyWt3 = ($DivisionInt3 * $EntryWeight3) + $RemWt3;

             echo '<tr>';
             echo '<td>' . $NavId3 . '</td>';
             echo '<td>' . $Qty3 . '</td>';
             echo '<td>' . $Weight3 . '</td>';
             echo '<td>' . $BoxCount3 . '</td>';
             echo '<td>' . $EntryWeight3 . '</td>';
             echo '<td>' . $DivisionInt3 . '</td>';
             echo '<td>' . $RemBal3 . '</td>';
             echo '<td>' . $RemWt3 . '</td>';
             echo '<td>' . $ItmQtyWt3 . '</td>';
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
             if ($EntryFlag4 != "True" && $_SESSION["Bundle4"] == "")
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

             // $EntryWeight4 = $DivisionInt4 * $BoxWeight4;
             $EntryWeight4 = 1 * $BoxWeight4;

             $RemWt4 = $RemBal4 * $Weight4;

             // ITM QTY WT calculation
             $ItmQtyWt4 = ($DivisionInt4 * $EntryWeight4) + $RemWt4;

             echo '<tr>';
             echo '<td>' . $NavId4 . '</td>';
             echo '<td>' . $Qty4 . '</td>';
             echo '<td>' . $Weight4 . '</td>';
             echo '<td>' . $BoxCount4 . '</td>';
             echo '<td>' . $EntryWeight4 . '</td>';
             echo '<td>' . $DivisionInt4 . '</td>';
             echo '<td>' . $RemBal4 . '</td>';
             echo '<td>' . $RemWt4 . '</td>';
             echo '<td>' . $ItmQtyWt4 . '</td>';
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
             if ($EntryFlag5 != "True" && $_SESSION["Bundle5"] == "")
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

             // $EntryWeight5 = $DivisionInt5 * $BoxWeight5;
             $EntryWeight5 = 1 * $BoxWeight5;

             $RemWt5 = $RemBal5 * $Weight5;

             // ITM QTY WT calculation
             $ItmQtyWt5 = ($DivisionInt5 * $EntryWeight5) + $RemWt5;

             echo '<tr>';
             echo '<td>' . $NavId5 . '</td>';
             echo '<td>' . $Qty5 . '</td>';
             echo '<td>' . $Weight5 . '</td>';
             echo '<td>' . $BoxCount5 . '</td>';
             echo '<td>' . $EntryWeight5 . '</td>';
             echo '<td>' . $DivisionInt5 . '</td>';
             echo '<td>' . $RemBal5 . '</td>';
             echo '<td>' . $RemWt5 . '</td>';
             echo '<td>' . $ItmQtyWt5 . '</td>';
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
             if ($EntryFlag6 != "True" && $_SESSION["Bundle6"] == "")
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

             // $EntryWeight6 = $DivisionInt6 * $BoxWeight6;
             $EntryWeight6 = 1 * $BoxWeight6;

             $RemWt6 = $RemBal6 * $Weight6;

             // ITM QTY WT calculation
             $ItmQtyWt6 = ($DivisionInt6 * $EntryWeight6) + $RemWt6;

             echo '<tr>';
             echo '<td>' . $NavId6 . '</td>';
             echo '<td>' . $Qty6 . '</td>';
             echo '<td>' . $Weight6 . '</td>';
             echo '<td>' . $BoxCount6 . '</td>';
             echo '<td>' . $EntryWeight6 . '</td>';
             echo '<td>' . $DivisionInt6 . '</td>';
             echo '<td>' . $RemBal6 . '</td>';
             echo '<td>' . $RemWt6 . '</td>';
             echo '<td>' . $ItmQtyWt6 . '</td>';
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
             if ($EntryFlag7 != "True" && $_SESSION["Bundle7"] == "")
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

             // $EntryWeight7 = $DivisionInt7 * $BoxWeight7;
             $EntryWeight7 = 1 * $BoxWeight7;

             $RemWt7 = $RemBal7 * $Weight7;

             // ITM QTY WT calculation
             $ItmQtyWt7 = ($DivisionInt7 * $EntryWeight7) + $RemWt7;

             echo '<tr>';
             echo '<td>' . $NavId7 . '</td>';
             echo '<td>' . $Qty7 . '</td>';
             echo '<td>' . $Weight7 . '</td>';
             echo '<td>' . $BoxCount7 . '</td>';
             echo '<td>' . $EntryWeight7 . '</td>';
             echo '<td>' . $DivisionInt7 . '</td>';
             echo '<td>' . $RemBal7 . '</td>';
             echo '<td>' . $RemWt7 . '</td>';
             echo '<td>' . $ItmQtyWt7 . '</td>';
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
             if ($EntryFlag8 != "True" && $_SESSION["Bundle8"] == "")
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

             // $EntryWeight8 = $DivisionInt8 * $BoxWeight8;
             $EntryWeight8 = 1 * $BoxWeight8;

             $RemWt8 = $RemBal8 * $Weight8;

             // ITM QTY WT calculation
             $ItmQtyWt8 = ($DivisionInt8 * $EntryWeight8) + $RemWt8;

             echo '<tr>';
             echo '<td>' . $NavId8 . '</td>';
             echo '<td>' . $Qty8 . '</td>';
             echo '<td>' . $Weight8 . '</td>';
             echo '<td>' . $BoxCount8 . '</td>';
             echo '<td>' . $EntryWeight8 . '</td>';
             echo '<td>' . $DivisionInt8 . '</td>';
             echo '<td>' . $RemBal8 . '</td>';
             echo '<td>' . $RemWt8 . '</td>';
             echo '<td>' . $ItmQtyWt8 . '</td>';
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
             if ($EntryFlag9 != "True" && $_SESSION["Bundle9"] == "")
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

             // $EntryWeight9 = $DivisionInt9 * $BoxWeight9;
             $EntryWeight9 = 1 * $BoxWeight9;

             $RemWt9 = $RemBal9 * $Weight9;

             // ITM QTY WT calculation
             $ItmQtyWt9 = ($DivisionInt9 * $EntryWeight9) + $RemWt9;

             echo '<tr>';
             echo '<td>' . $NavId9 . '</td>';
             echo '<td>' . $Qty9 . '</td>';
             echo '<td>' . $Weight9 . '</td>';
             echo '<td>' . $BoxCount9 . '</td>';
             echo '<td>' . $EntryWeight9 . '</td>';
             echo '<td>' . $DivisionInt9 . '</td>';
             echo '<td>' . $RemBal9 . '</td>';
             echo '<td>' . $RemWt9 . '</td>';
             echo '<td>' . $ItmQtyWt9 . '</td>';
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
             if ($EntryFlag10 != "True" && $_SESSION["Bundle10"] == "")
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

             // $EntryWeight10 = $DivisionInt10 * $BoxWeight10;
             $EntryWeight10 = 1 * $BoxWeight10;

             $RemWt10 = $RemBal10 * $Weight10;

             // ITM QTY WT calculation
             $ItmQtyWt10 = ($DivisionInt10 * $EntryWeight10) + $RemWt10;

             echo '<tr>';
             echo '<td>' . $NavId10 . '</td>';
             echo '<td>' . $Qty10 . '</td>';
             echo '<td>' . $Weight10 . '</td>';
             echo '<td>' . $BoxCount10 . '</td>';
             echo '<td>' . $EntryWeight10 . '</td>';
             echo '<td>' . $DivisionInt10 . '</td>';
             echo '<td>' . $RemBal10 . '</td>';
             echo '<td>' . $RemWt10 . '</td>';
             echo '<td>' . $ItmQtyWt10 . '</td>';
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
             if ($EntryFlag11 != "True"  && $_SESSION["Bundle11"] == "")
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

             // $EntryWeight11 = $DivisionInt11 * $BoxWeight11;
             $EntryWeight11 = 1 * $BoxWeight11;

             $RemWt11 = $RemBal11 * $Weight11;

             // ITM QTY WT calculation
             $ItmQtyWt11 = ($DivisionInt11 * $EntryWeight11) + $RemWt11;

             echo '<tr>';
             echo '<td>' . $NavId11 . '</td>';
             echo '<td>' . $Qty11 . '</td>';
             echo '<td>' . $Weight11 . '</td>';
             echo '<td>' . $BoxCount11 . '</td>';
             echo '<td>' . $EntryWeight11 . '</td>';
             echo '<td>' . $DivisionInt11 . '</td>';
             echo '<td>' . $RemBal11 . '</td>';
             echo '<td>' . $RemWt11 . '</td>';
             echo '<td>' . $ItmQtyWt11 . '</td>';
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
             if ($EntryFlag12 != "True" && $_SESSION["Bundle12"] == "")
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

             // $EntryWeight12 = $DivisionInt12 * $BoxWeight12;
             $EntryWeight12 = 1 * $BoxWeight12;

             $RemWt12 = $RemBal12 * $Weight12;

             // ITM QTY WT calculation
             $ItmQtyWt12 = ($DivisionInt12 * $EntryWeight12) + $RemWt12;

             echo '<tr>';
             echo '<td>' . $NavId12 . '</td>';
             echo '<td>' . $Qty12 . '</td>';
             echo '<td>' . $Weight12 . '</td>';
             echo '<td>' . $BoxCount12 . '</td>';
             echo '<td>' . $EntryWeight12 . '</td>';
             echo '<td>' . $DivisionInt12 . '</td>';
             echo '<td>' . $RemBal12 . '</td>';
             echo '<td>' . $RemWt12 . '</td>';
             echo '<td>' . $ItmQtyWt12 . '</td>';
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
             if ($EntryFlag13 != "True" && $_SESSION["Bundle13"] == "")
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

             // $EntryWeight13 = $DivisionInt13 * $BoxWeight13;
             $EntryWeight13 = 1 * $BoxWeight13;

             $RemWt13 = $RemBal13 * $Weight13;

             // ITM QTY WT calculation
             $ItmQtyWt13 = ($DivisionInt13 * $EntryWeight13) + $RemWt13;

             echo '<tr>';
             echo '<td>' . $NavId13 . '</td>';
             echo '<td>' . $Qty13 . '</td>';
             echo '<td>' . $Weight13 . '</td>';
             echo '<td>' . $BoxCount13 . '</td>';
             echo '<td>' . $EntryWeight13 . '</td>';
             echo '<td>' . $DivisionInt13 . '</td>';
             echo '<td>' . $RemBal13 . '</td>';
             echo '<td>' . $RemWt13 . '</td>';
             echo '<td>' . $ItmQtyWt13 . '</td>';
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
             if ($EntryFlag14 != "True" && $_SESSION["Bundle14"] == "")
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

             // $EntryWeight14 = $DivisionInt14 * $BoxWeight14;
             $EntryWeight14 = 1 * $BoxWeight14;

             $RemWt14 = $RemBal14 * $Weight14;

             // ITM QTY WT calculation
             $ItmQtyWt14 = ($DivisionInt14 * $EntryWeight14) + $RemWt14;

             echo '<tr>';
             echo '<td>' . $NavId14 . '</td>';
             echo '<td>' . $Qty14 . '</td>';
             echo '<td>' . $Weight14 . '</td>';
             echo '<td>' . $BoxCount14 . '</td>';
             echo '<td>' . $EntryWeight14 . '</td>';
             echo '<td>' . $DivisionInt14 . '</td>';
             echo '<td>' . $RemBal14 . '</td>';
             echo '<td>' . $RemWt14 . '</td>';
             echo '<td>' . $ItmQtyWt14 . '</td>';
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
             if ($EntryFlag15 != "True" && $_SESSION["Bundle15"] == "")
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

             // $EntryWeight15 = $DivisionInt15 * $BoxWeight15;
             $EntryWeight15 = 1 * $BoxWeight15;

             $RemWt15 = $RemBal15 * $Weight15;

             // ITM QTY WT calculation
             $ItmQtyWt15 = ($DivisionInt15 * $EntryWeight15) + $RemWt15;

             echo '<tr>';
             echo '<td>' . $NavId15 . '</td>';
             echo '<td>' . $Qty15 . '</td>';
             echo '<td>' . $Weight15 . '</td>';
             echo '<td>' . $BoxCount15 . '</td>';
             echo '<td>' . $EntryWeight15 . '</td>';
             echo '<td>' . $DivisionInt15 . '</td>';
             echo '<td>' . $RemBal15 . '</td>';
             echo '<td>' . $RemWt15 . '</td>';
             echo '<td>' . $ItmQtyWt15 . '</td>';
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
             if ($EntryFlag16 != "True" && $_SESSION["Bundle16"] == "")
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

             // $EntryWeight16 = $DivisionInt16 * $BoxWeight16;
             $EntryWeight16 = 1 * $BoxWeight16;

             $RemWt16 = $RemBal16 * $Weight16;

             // ITM QTY WT calculation
             $ItmQtyWt16 = ($DivisionInt16 * $EntryWeight16) + $RemWt16;

             echo '<tr>';
             echo '<td>' . $NavId16 . '</td>';
             echo '<td>' . $Qty16 . '</td>';
             echo '<td>' . $Weight16 . '</td>';
             echo '<td>' . $BoxCount16 . '</td>';
             echo '<td>' . $EntryWeight16 . '</td>';
             echo '<td>' . $DivisionInt16 . '</td>';
             echo '<td>' . $RemBal16 . '</td>';
             echo '<td>' . $RemWt16 . '</td>';
             echo '<td>' . $ItmQtyWt16 . '</td>';
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
             if ($EntryFlag17 != "True" && $_SESSION["Bundle17"] == "")
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

             // $EntryWeight17 = $DivisionInt17 * $BoxWeight17;
             $EntryWeight17 = 1 * $BoxWeight17;

             $RemWt17 = $RemBal17 * $Weight17;

             // ITM QTY WT calculation
             $ItmQtyWt17 = ($DivisionInt17 * $EntryWeight17) + $RemWt17;

             echo '<tr>';
             echo '<td>' . $NavId17 . '</td>';
             echo '<td>' . $Qty17 . '</td>';
             echo '<td>' . $Weight17 . '</td>';
             echo '<td>' . $BoxCount17 . '</td>';
             echo '<td>' . $EntryWeight17 . '</td>';
             echo '<td>' . $DivisionInt17 . '</td>';
             echo '<td>' . $RemBal17 . '</td>';
             echo '<td>' . $RemWt17 . '</td>';
             echo '<td>' . $ItmQtyWt17 . '</td>';
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
             if ($EntryFlag18 != "True" && $_SESSION["Bundle18"] == "")
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

             // $EntryWeight18 = $DivisionInt18 * $BoxWeight18;
             $EntryWeight18 = 1 * $BoxWeight18;

             $RemWt18 = $RemBal18 * $Weight18;

             // ITM QTY WT calculation
             $ItmQtyWt18 = ($DivisionInt18 * $EntryWeight18) + $RemWt18;

             echo '<tr>';
             echo '<td>' . $NavId18 . '</td>';
             echo '<td>' . $Qty18 . '</td>';
             echo '<td>' . $Weight18 . '</td>';
             echo '<td>' . $BoxCount18 . '</td>';
             echo '<td>' . $EntryWeight18 . '</td>';
             echo '<td>' . $DivisionInt18 . '</td>';
             echo '<td>' . $RemBal18 . '</td>';
             echo '<td>' . $RemWt18 . '</td>';
             echo '<td>' . $ItmQtyWt18 . '</td>';
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
             if ($EntryFlag19 != "True" && $_SESSION["Bundle19"] == "")
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

             // $EntryWeight19 = $DivisionInt19 * $BoxWeight19;
             $EntryWeight19 = 1 * $BoxWeight19;

             $RemWt19 = $RemBal19 * $Weight19;

             // ITM QTY WT calculation
             $ItmQtyWt19 = ($DivisionInt19 * $EntryWeight19) + $RemWt19;

             echo '<tr>';
             echo '<td>' . $NavId19 . '</td>';
             echo '<td>' . $Qty19 . '</td>';
             echo '<td>' . $Weight19 . '</td>';
             echo '<td>' . $BoxCount19 . '</td>';
             echo '<td>' . $EntryWeight19 . '</td>';
             echo '<td>' . $DivisionInt19 . '</td>';
             echo '<td>' . $RemBal19 . '</td>';
             echo '<td>' . $RemWt19 . '</td>';
             echo '<td>' . $ItmQtyWt19 . '</td>';
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
             if ($EntryFlag20 != "True" && $_SESSION["Bundle20"] == "")
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

                 // $EntryWeight20 = $DivisionInt20 * $BoxWeight20;
                 $EntryWeight20 = 1 * $BoxWeight20;

                 $RemWt20 = $RemBal20 * $Weight20;

                 // ITM QTY WT calculation
                 $ItmQtyWt20 = ($DivisionInt20 * $EntryWeight20) + $RemWt20;

                 echo '<tr>';
                 echo '<td>' . $NavId20 . '</td>';
                 echo '<td>' . $Qty20 . '</td>';
                 echo '<td>' . $Weight20 . '</td>';
                 echo '<td>' . $BoxCount20 . '</td>';
                 echo '<td>' . $EntryWeight20 . '</td>';
                 echo '<td>' . $DivisionInt20 . '</td>';
                 echo '<td>' . $RemBal20 . '</td>';
                 echo '<td>' . $RemWt20 . '</td>';
                 echo '<td>' . $ItmQtyWt20 . '</td>';
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

             // GMC - 09/06/12 - To handle Bundles in the Box Calculation
             if ($_SESSION["Bundle1"] != "")
             {
                 // Get all items from the bundle
                 $BundleItems1 =  mssql_query("SELECT NavID, Qty FROM tblBundles WHERE ProductID = " . $_SESSION["Bundle1"]);
                 $arrayCounter = 0;
                 while($row = mssql_fetch_array($BundleItems1))
                 {
                     $BI1NavID[$arrayCounter] = $row["NavID"];
                     $BI1Qty[$arrayCounter] = $row["Qty"];
                     $arrayCounter++;
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $ProductBI1 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $BI1NavID[$c]);
                     while($row = mssql_fetch_array($ProductBI1))
                     {
                         $WeightBI1[$c] = $row["Weight"];
                         $BoxCountBI1[$c] = $row["BoxCount"];
                         $BoxWeightBI1[$c] = $row["BoxWeight"];
                         $BoxLengthBI1[$c] = $row["BoxLength"];
                         $BoxWidthBI1[$c] = $row["BoxWidth"];
                         $BoxHeightBI1[$c] = $row["BoxHeight"];
                     }
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $DivisionTotalBI1[$c] = ($Qty1 * $BI1Qty[$c])/$BoxCountBI1[$c];
                     $DivisionIntBI1[$c] = floor(($Qty1 * $BI1Qty[$c])/$BoxCountBI1[$c]);
                     $DivisionRemBI1[$c] = $DivisionTotalBI1[$c] - $DivisionIntBI1[$c];
                     $RemBalBI1[$c] = $DivisionRemBI1[$c] * $BoxCountBI1[$c];
                     $EntryWeightBI1[$c] = 1 * $BoxWeightBI1[$c];
                     $RemWtBI1[$c] = $RemBalBI1[$c] * $WeightBI1[$c];
                     $ItmQtyWtBI1[$c] = ($DivisionIntBI1[$c] * $EntryWeightBI1[$c]) + $RemWtBI1[$c];
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     if($DivisionIntBI1[$c] > 0)
                     {
                         for ($counter = 0; $counter < $DivisionIntBI1[$c]; $counter++)
                         {
                             $arrBoxCount[$counterBoxes] = 1;
                             $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeightBI1[$c],
                                 'Units' => 'LB'),
                                 'Dimensions' => array('Length' => $BoxLengthBI1[$c],
                                 'Width' => $BoxWidthBI1[$c],
                                 'Height' => $BoxHeightBI1[$c],
                                 'Units' => 'IN'));
                             $counterBoxes++;
                         }
                     }
                 }
             }

             if ($_SESSION["Bundle2"] != "")
             {
                 // Get all items from the bundle
                 $BundleItems2 =  mssql_query("SELECT NavID, Qty FROM tblBundles WHERE ProductID = " . $_SESSION["Bundle2"]);
                 $arrayCounter = 0;
                 while($row = mssql_fetch_array($BundleItems2))
                 {
                     $BI2NavID[$arrayCounter] = $row["NavID"];
                     $BI2Qty[$arrayCounter] = $row["Qty"];
                     $arrayCounter++;
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $ProductBI2 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $BI2NavID[$c]);
                     while($row = mssql_fetch_array($ProductBI2))
                     {
                         $WeightBI2[$c] = $row["Weight"];
                         $BoxCountBI2[$c] = $row["BoxCount"];
                         $BoxWeightBI2[$c] = $row["BoxWeight"];
                         $BoxLengthBI2[$c] = $row["BoxLength"];
                         $BoxWidthBI2[$c] = $row["BoxWidth"];
                         $BoxHeightBI2[$c] = $row["BoxHeight"];
                     }
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $DivisionTotalBI2[$c] = ($Qty2 * $BI2Qty[$c])/$BoxCountBI2[$c];
                     $DivisionIntBI2[$c] = floor(($Qty2 * $BI2Qty[$c])/$BoxCountBI2[$c]);
                     $DivisionRemBI2[$c] = $DivisionTotalBI2[$c] - $DivisionIntBI2[$c];
                     $RemBalBI2[$c] = $DivisionRemBI2[$c] * $BoxCountBI2[$c];
                     $EntryWeightBI2[$c] = 1 * $BoxWeightBI2[$c];
                     $RemWtBI2[$c] = $RemBalBI2[$c] * $WeightBI2[$c];
                     $ItmQtyWtBI2[$c] = ($DivisionIntBI2[$c] * $EntryWeightBI2[$c]) + $RemWtBI2[$c];
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     if($DivisionIntBI2[$c] > 0)
                     {
                         for ($counter = 0; $counter < $DivisionIntBI2[$c]; $counter++)
                         {
                             $arrBoxCount[$counterBoxes] = 1;
                             $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeightBI2[$c],
                                 'Units' => 'LB'),
                                 'Dimensions' => array('Length' => $BoxLengthBI2[$c],
                                 'Width' => $BoxWidthBI2[$c],
                                 'Height' => $BoxHeightBI2[$c],
                                 'Units' => 'IN'));
                             $counterBoxes++;
                         }
                     }
                 }
             }

             if ($_SESSION["Bundle3"] != "")
             {
                 // Get all items from the bundle
                 $BundleItems3 =  mssql_query("SELECT NavID, Qty FROM tblBundles WHERE ProductID = " . $_SESSION["Bundle3"]);
                 $arrayCounter = 0;
                 while($row = mssql_fetch_array($BundleItems3))
                 {
                     $BI3NavID[$arrayCounter] = $row["NavID"];
                     $BI3Qty[$arrayCounter] = $row["Qty"];
                     $arrayCounter++;
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $ProductBI3 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $BI3NavID[$c]);
                     while($row = mssql_fetch_array($ProductBI3))
                     {
                         $WeightBI3[$c] = $row["Weight"];
                         $BoxCountBI3[$c] = $row["BoxCount"];
                         $BoxWeightBI3[$c] = $row["BoxWeight"];
                         $BoxLengthBI3[$c] = $row["BoxLength"];
                         $BoxWidthBI3[$c] = $row["BoxWidth"];
                         $BoxHeightBI3[$c] = $row["BoxHeight"];
                     }
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $DivisionTotalBI3[$c] = ($Qty3 * $BI3Qty[$c])/$BoxCountBI3[$c];
                     $DivisionIntBI3[$c] = floor(($Qty3 * $BI3Qty[$c])/$BoxCountBI3[$c]);
                     $DivisionRemBI3[$c] = $DivisionTotalBI3[$c] - $DivisionIntBI3[$c];
                     $RemBalBI3[$c] = $DivisionRemBI3[$c] * $BoxCountBI3[$c];
                     $EntryWeightBI3[$c] = 1 * $BoxWeightBI3[$c];
                     $RemWtBI3[$c] = $RemBalBI3[$c] * $WeightBI3[$c];
                     $ItmQtyWtBI3[$c] = ($DivisionIntBI3[$c] * $EntryWeightBI3[$c]) + $RemWtBI3[$c];
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     if($DivisionIntBI3[$c] > 0)
                     {
                         for ($counter = 0; $counter < $DivisionIntBI3[$c]; $counter++)
                         {
                             $arrBoxCount[$counterBoxes] = 1;
                             $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeightBI3[$c],
                                 'Units' => 'LB'),
                                 'Dimensions' => array('Length' => $BoxLengthBI3[$c],
                                 'Width' => $BoxWidthBI3[$c],
                                 'Height' => $BoxHeightBI3[$c],
                                 'Units' => 'IN'));
                             $counterBoxes++;
                         }
                     }
                 }
             }

             if ($_SESSION["Bundle4"] != "")
             {
                 // Get all items from the bundle
                 $BundleItems4 =  mssql_query("SELECT NavID, Qty FROM tblBundles WHERE ProductID = " . $_SESSION["Bundle4"]);
                 $arrayCounter = 0;
                 while($row = mssql_fetch_array($BundleItems4))
                 {
                     $BI4NavID[$arrayCounter] = $row["NavID"];
                     $BI4Qty[$arrayCounter] = $row["Qty"];
                     $arrayCounter++;
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $ProductBI4 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $BI4NavID[$c]);
                     while($row = mssql_fetch_array($ProductBI4))
                     {
                         $WeightBI4[$c] = $row["Weight"];
                         $BoxCountBI4[$c] = $row["BoxCount"];
                         $BoxWeightBI4[$c] = $row["BoxWeight"];
                         $BoxLengthBI4[$c] = $row["BoxLength"];
                         $BoxWidthBI4[$c] = $row["BoxWidth"];
                         $BoxHeightBI4[$c] = $row["BoxHeight"];
                     }
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $DivisionTotalBI4[$c] = ($Qty4 * $BI4Qty[$c])/$BoxCountBI4[$c];
                     $DivisionIntBI4[$c] = floor(($Qty4 * $BI4Qty[$c])/$BoxCountBI4[$c]);
                     $DivisionRemBI4[$c] = $DivisionTotalBI4[$c] - $DivisionIntBI4[$c];
                     $RemBalBI4[$c] = $DivisionRemBI4[$c] * $BoxCountBI4[$c];
                     $EntryWeightBI4[$c] = 1 * $BoxWeightBI4[$c];
                     $RemWtBI4[$c] = $RemBalBI4[$c] * $WeightBI4[$c];
                     $ItmQtyWtBI4[$c] = ($DivisionIntBI4[$c] * $EntryWeightBI4[$c]) + $RemWtBI4[$c];
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     if($DivisionIntBI4[$c] > 0)
                     {
                         for ($counter = 0; $counter < $DivisionIntBI4[$c]; $counter++)
                         {
                             $arrBoxCount[$counterBoxes] = 1;
                             $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeightBI4[$c],
                                 'Units' => 'LB'),
                                 'Dimensions' => array('Length' => $BoxLengthBI4[$c],
                                 'Width' => $BoxWidthBI4[$c],
                                 'Height' => $BoxHeightBI4[$c],
                                 'Units' => 'IN'));
                             $counterBoxes++;
                         }
                     }
                 }
             }

             if ($_SESSION["Bundle5"] != "")
             {
                 // Get all items from the bundle
                 $BundleItems5 =  mssql_query("SELECT NavID, Qty FROM tblBundles WHERE ProductID = " . $_SESSION["Bundle5"]);
                 $arrayCounter = 0;
                 while($row = mssql_fetch_array($BundleItems5))
                 {
                     $BI5NavID[$arrayCounter] = $row["NavID"];
                     $BI5Qty[$arrayCounter] = $row["Qty"];
                     $arrayCounter++;
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $ProductBI5 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $BI5NavID[$c]);
                     while($row = mssql_fetch_array($ProductBI5))
                     {
                         $WeightBI5[$c] = $row["Weight"];
                         $BoxCountBI5[$c] = $row["BoxCount"];
                         $BoxWeightBI5[$c] = $row["BoxWeight"];
                         $BoxLengthBI5[$c] = $row["BoxLength"];
                         $BoxWidthBI5[$c] = $row["BoxWidth"];
                         $BoxHeightBI5[$c] = $row["BoxHeight"];
                     }
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $DivisionTotalBI5[$c] = ($Qty5 * $BI5Qty[$c])/$BoxCountBI5[$c];
                     $DivisionIntBI5[$c] = floor(($Qty5 * $BI5Qty[$c])/$BoxCountBI5[$c]);
                     $DivisionRemBI5[$c] = $DivisionTotalBI5[$c] - $DivisionIntBI5[$c];
                     $RemBalBI5[$c] = $DivisionRemBI5[$c] * $BoxCountBI5[$c];
                     $EntryWeightBI5[$c] = 1 * $BoxWeightBI5[$c];
                     $RemWtBI5[$c] = $RemBalBI5[$c] * $WeightBI5[$c];
                     $ItmQtyWtBI5[$c] = ($DivisionIntBI5[$c] * $EntryWeightBI5[$c]) + $RemWtBI5[$c];
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     if($DivisionIntBI5[$c] > 0)
                     {
                         for ($counter = 0; $counter < $DivisionIntBI5[$c]; $counter++)
                         {
                             $arrBoxCount[$counterBoxes] = 1;
                             $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeightBI5[$c],
                                 'Units' => 'LB'),
                                 'Dimensions' => array('Length' => $BoxLengthBI5[$c],
                                 'Width' => $BoxWidthBI5[$c],
                                 'Height' => $BoxHeightBI5[$c],
                                 'Units' => 'IN'));
                             $counterBoxes++;
                         }
                     }
                 }
             }

             if ($_SESSION["Bundle6"] != "")
             {
                 // Get all items from the bundle
                 $BundleItems6 =  mssql_query("SELECT NavID, Qty FROM tblBundles WHERE ProductID = " . $_SESSION["Bundle6"]);
                 $arrayCounter = 0;
                 while($row = mssql_fetch_array($BundleItems6))
                 {
                     $BI6NavID[$arrayCounter] = $row["NavID"];
                     $BI6Qty[$arrayCounter] = $row["Qty"];
                     $arrayCounter++;
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $ProductBI6 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $BI6NavID[$c]);
                     while($row = mssql_fetch_array($ProductBI6))
                     {
                         $WeightBI6[$c] = $row["Weight"];
                         $BoxCountBI6[$c] = $row["BoxCount"];
                         $BoxWeightBI6[$c] = $row["BoxWeight"];
                         $BoxLengthBI6[$c] = $row["BoxLength"];
                         $BoxWidthBI6[$c] = $row["BoxWidth"];
                         $BoxHeightBI6[$c] = $row["BoxHeight"];
                     }
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $DivisionTotalBI6[$c] = ($Qty6 * $BI6Qty[$c])/$BoxCountBI6[$c];
                     $DivisionIntBI6[$c] = floor(($Qty6 * $BI6Qty[$c])/$BoxCountBI6[$c]);
                     $DivisionRemBI6[$c] = $DivisionTotalBI6[$c] - $DivisionIntBI6[$c];
                     $RemBalBI6[$c] = $DivisionRemBI6[$c] * $BoxCountBI6[$c];
                     $EntryWeightBI6[$c] = 1 * $BoxWeightBI6[$c];
                     $RemWtBI6[$c] = $RemBalBI6[$c] * $WeightBI6[$c];
                     $ItmQtyWtBI6[$c] = ($DivisionIntBI6[$c] * $EntryWeightBI6[$c]) + $RemWtBI6[$c];
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     if($DivisionIntBI6[$c] > 0)
                     {
                         for ($counter = 0; $counter < $DivisionIntBI6[$c]; $counter++)
                         {
                             $arrBoxCount[$counterBoxes] = 1;
                             $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeightBI6[$c],
                                 'Units' => 'LB'),
                                 'Dimensions' => array('Length' => $BoxLengthBI6[$c],
                                 'Width' => $BoxWidthBI6[$c],
                                 'Height' => $BoxHeightBI6[$c],
                                 'Units' => 'IN'));
                             $counterBoxes++;
                         }
                     }
                 }
             }

             if ($_SESSION["Bundle7"] != "")
             {
                 // Get all items from the bundle
                 $BundleItems7 =  mssql_query("SELECT NavID, Qty FROM tblBundles WHERE ProductID = " . $_SESSION["Bundle7"]);
                 $arrayCounter = 0;
                 while($row = mssql_fetch_array($BundleItems7))
                 {
                     $BI7NavID[$arrayCounter] = $row["NavID"];
                     $BI7Qty[$arrayCounter] = $row["Qty"];
                     $arrayCounter++;
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $ProductBI7 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $BI7NavID[$c]);
                     while($row = mssql_fetch_array($ProductBI7))
                     {
                         $WeightBI7[$c] = $row["Weight"];
                         $BoxCountBI7[$c] = $row["BoxCount"];
                         $BoxWeightBI7[$c] = $row["BoxWeight"];
                         $BoxLengthBI7[$c] = $row["BoxLength"];
                         $BoxWidthBI7[$c] = $row["BoxWidth"];
                         $BoxHeightBI7[$c] = $row["BoxHeight"];
                     }
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $DivisionTotalBI7[$c] = ($Qty7 * $BI7Qty[$c])/$BoxCountBI7[$c];
                     $DivisionIntBI7[$c] = floor(($Qty7 * $BI7Qty[$c])/$BoxCountBI7[$c]);
                     $DivisionRemBI7[$c] = $DivisionTotalBI7[$c] - $DivisionIntBI7[$c];
                     $RemBalBI7[$c] = $DivisionRemBI7[$c] * $BoxCountBI7[$c];
                     $EntryWeightBI7[$c] = 1 * $BoxWeightBI7[$c];
                     $RemWtBI7[$c] = $RemBalBI7[$c] * $WeightBI7[$c];
                     $ItmQtyWtBI7[$c] = ($DivisionIntBI7[$c] * $EntryWeightBI7[$c]) + $RemWtBI7[$c];
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     if($DivisionIntBI7[$c] > 0)
                     {
                         for ($counter = 0; $counter < $DivisionIntBI7[$c]; $counter++)
                         {
                             $arrBoxCount[$counterBoxes] = 1;
                             $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeightBI7[$c],
                                 'Units' => 'LB'),
                                 'Dimensions' => array('Length' => $BoxLengthBI7[$c],
                                 'Width' => $BoxWidthBI7[$c],
                                 'Height' => $BoxHeightBI7[$c],
                                 'Units' => 'IN'));
                             $counterBoxes++;
                         }
                     }
                 }
             }

             if ($_SESSION["Bundle8"] != "")
             {
                 // Get all items from the bundle
                 $BundleItems8 =  mssql_query("SELECT NavID, Qty FROM tblBundles WHERE ProductID = " . $_SESSION["Bundle8"]);
                 $arrayCounter = 0;
                 while($row = mssql_fetch_array($BundleItems8))
                 {
                     $BI8NavID[$arrayCounter] = $row["NavID"];
                     $BI8Qty[$arrayCounter] = $row["Qty"];
                     $arrayCounter++;
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $ProductBI8 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $BI8NavID[$c]);
                     while($row = mssql_fetch_array($ProductBI8))
                     {
                         $WeightBI8[$c] = $row["Weight"];
                         $BoxCountBI8[$c] = $row["BoxCount"];
                         $BoxWeightBI8[$c] = $row["BoxWeight"];
                         $BoxLengthBI8[$c] = $row["BoxLength"];
                         $BoxWidthBI8[$c] = $row["BoxWidth"];
                         $BoxHeightBI8[$c] = $row["BoxHeight"];
                     }
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $DivisionTotalBI8[$c] = ($Qty8 * $BI8Qty[$c])/$BoxCountBI8[$c];
                     $DivisionIntBI8[$c] = floor(($Qty8 * $BI8Qty[$c])/$BoxCountBI8[$c]);
                     $DivisionRemBI8[$c] = $DivisionTotalBI8[$c] - $DivisionIntBI8[$c];
                     $RemBalBI8[$c] = $DivisionRemBI8[$c] * $BoxCountBI8[$c];
                     $EntryWeightBI8[$c] = 1 * $BoxWeightBI8[$c];
                     $RemWtBI8[$c] = $RemBalBI8[$c] * $WeightBI8[$c];
                     $ItmQtyWtBI8[$c] = ($DivisionIntBI8[$c] * $EntryWeightBI8[$c]) + $RemWtBI8[$c];
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     if($DivisionIntBI8[$c] > 0)
                     {
                         for ($counter = 0; $counter < $DivisionIntBI8[$c]; $counter++)
                         {
                             $arrBoxCount[$counterBoxes] = 1;
                             $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeightBI8[$c],
                                 'Units' => 'LB'),
                                 'Dimensions' => array('Length' => $BoxLengthBI8[$c],
                                 'Width' => $BoxWidthBI8[$c],
                                 'Height' => $BoxHeightBI8[$c],
                                 'Units' => 'IN'));
                             $counterBoxes++;
                         }
                     }
                 }
             }

             if ($_SESSION["Bundle9"] != "")
             {
                 // Get all items from the bundle
                 $BundleItems9 =  mssql_query("SELECT NavID, Qty FROM tblBundles WHERE ProductID = " . $_SESSION["Bundle9"]);
                 $arrayCounter = 0;
                 while($row = mssql_fetch_array($BundleItems9))
                 {
                     $BI9NavID[$arrayCounter] = $row["NavID"];
                     $BI9Qty[$arrayCounter] = $row["Qty"];
                     $arrayCounter++;
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $ProductBI9 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $BI9NavID[$c]);
                     while($row = mssql_fetch_array($ProductBI9))
                     {
                         $WeightBI9[$c] = $row["Weight"];
                         $BoxCountBI9[$c] = $row["BoxCount"];
                         $BoxWeightBI9[$c] = $row["BoxWeight"];
                         $BoxLengthBI9[$c] = $row["BoxLength"];
                         $BoxWidthBI9[$c] = $row["BoxWidth"];
                         $BoxHeightBI9[$c] = $row["BoxHeight"];
                     }
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $DivisionTotalBI9[$c] = ($Qty9 * $BI9Qty[$c])/$BoxCountBI9[$c];
                     $DivisionIntBI9[$c] = floor(($Qty9 * $BI9Qty[$c])/$BoxCountBI9[$c]);
                     $DivisionRemBI9[$c] = $DivisionTotalBI9[$c] - $DivisionIntBI9[$c];
                     $RemBalBI9[$c] = $DivisionRemBI9[$c] * $BoxCountBI9[$c];
                     $EntryWeightBI9[$c] = 1 * $BoxWeightBI9[$c];
                     $RemWtBI9[$c] = $RemBalBI9[$c] * $WeightBI9[$c];
                     $ItmQtyWtBI9[$c] = ($DivisionIntBI9[$c] * $EntryWeightBI9[$c]) + $RemWtBI9[$c];
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     if($DivisionIntBI9[$c] > 0)
                     {
                         for ($counter = 0; $counter < $DivisionIntBI9[$c]; $counter++)
                         {
                             $arrBoxCount[$counterBoxes] = 1;
                             $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeightBI9[$c],
                                 'Units' => 'LB'),
                                 'Dimensions' => array('Length' => $BoxLengthBI9[$c],
                                 'Width' => $BoxWidthBI9[$c],
                                 'Height' => $BoxHeightBI9[$c],
                                 'Units' => 'IN'));
                             $counterBoxes++;
                         }
                     }
                 }
             }

             if ($_SESSION["Bundle10"] != "")
             {
                 // Get all items from the bundle
                 $BundleItems10 =  mssql_query("SELECT NavID, Qty FROM tblBundles WHERE ProductID = " . $_SESSION["Bundle10"]);
                 $arrayCounter = 0;
                 while($row = mssql_fetch_array($BundleItems10))
                 {
                     $BI10NavID[$arrayCounter] = $row["NavID"];
                     $BI10Qty[$arrayCounter] = $row["Qty"];
                     $arrayCounter++;
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $ProductBI10 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $BI10NavID[$c]);
                     while($row = mssql_fetch_array($ProductBI10))
                     {
                         $WeightBI10[$c] = $row["Weight"];
                         $BoxCountBI10[$c] = $row["BoxCount"];
                         $BoxWeightBI10[$c] = $row["BoxWeight"];
                         $BoxLengthBI10[$c] = $row["BoxLength"];
                         $BoxWidthBI10[$c] = $row["BoxWidth"];
                         $BoxHeightBI10[$c] = $row["BoxHeight"];
                     }
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $DivisionTotalBI10[$c] = ($Qty10 * $BI10Qty[$c])/$BoxCountBI10[$c];
                     $DivisionIntBI10[$c] = floor(($Qty10 * $BI10Qty[$c])/$BoxCountBI10[$c]);
                     $DivisionRemBI10[$c] = $DivisionTotalBI10[$c] - $DivisionIntBI10[$c];
                     $RemBalBI10[$c] = $DivisionRemBI10[$c] * $BoxCountBI10[$c];
                     $EntryWeightBI10[$c] = 1 * $BoxWeightBI10[$c];
                     $RemWtBI10[$c] = $RemBalBI10[$c] * $WeightBI10[$c];
                     $ItmQtyWtBI10[$c] = ($DivisionIntBI10[$c] * $EntryWeightBI10[$c]) + $RemWtBI10[$c];
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     if($DivisionIntBI10[$c] > 0)
                     {
                         for ($counter = 0; $counter < $DivisionIntBI10[$c]; $counter++)
                         {
                             $arrBoxCount[$counterBoxes] = 1;
                             $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeightBI10[$c],
                                 'Units' => 'LB'),
                                 'Dimensions' => array('Length' => $BoxLengthBI10[$c],
                                 'Width' => $BoxWidthBI10[$c],
                                 'Height' => $BoxHeightBI10[$c],
                                 'Units' => 'IN'));
                             $counterBoxes++;
                         }
                     }
                 }
             }

             if ($_SESSION["Bundle11"] != "")
             {
                 // Get all items from the bundle
                 $BundleItems11 =  mssql_query("SELECT NavID, Qty FROM tblBundles WHERE ProductID = " . $_SESSION["Bundle11"]);
                 $arrayCounter = 0;
                 while($row = mssql_fetch_array($BundleItems11))
                 {
                     $BI11NavID[$arrayCounter] = $row["NavID"];
                     $BI11Qty[$arrayCounter] = $row["Qty"];
                     $arrayCounter++;
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $ProductBI11 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $BI11NavID[$c]);
                     while($row = mssql_fetch_array($ProductBI11))
                     {
                         $WeightBI11[$c] = $row["Weight"];
                         $BoxCountBI11[$c] = $row["BoxCount"];
                         $BoxWeightBI11[$c] = $row["BoxWeight"];
                         $BoxLengthBI11[$c] = $row["BoxLength"];
                         $BoxWidthBI11[$c] = $row["BoxWidth"];
                         $BoxHeightBI11[$c] = $row["BoxHeight"];
                     }
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $DivisionTotalBI11[$c] = ($Qty11 * $BI11Qty[$c])/$BoxCountBI11[$c];
                     $DivisionIntBI11[$c] = floor(($Qty11 * $BI11Qty[$c])/$BoxCountBI11[$c]);
                     $DivisionRemBI11[$c] = $DivisionTotalBI11[$c] - $DivisionIntBI11[$c];
                     $RemBalBI11[$c] = $DivisionRemBI11[$c] * $BoxCountBI11[$c];
                     $EntryWeightBI11[$c] = 1 * $BoxWeightBI11[$c];
                     $RemWtBI11[$c] = $RemBalBI11[$c] * $WeightBI11[$c];
                     $ItmQtyWtBI11[$c] = ($DivisionIntBI11[$c] * $EntryWeightBI11[$c]) + $RemWtBI11[$c];
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     if($DivisionIntBI11[$c] > 0)
                     {
                         for ($counter = 0; $counter < $DivisionIntBI11[$c]; $counter++)
                         {
                             $arrBoxCount[$counterBoxes] = 1;
                             $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeightBI11[$c],
                                 'Units' => 'LB'),
                                 'Dimensions' => array('Length' => $BoxLengthBI11[$c],
                                 'Width' => $BoxWidthBI11[$c],
                                 'Height' => $BoxHeightBI11[$c],
                                 'Units' => 'IN'));
                             $counterBoxes++;
                         }
                     }
                 }
             }

             if ($_SESSION["Bundle12"] != "")
             {
                 // Get all items from the bundle
                 $BundleItems12 =  mssql_query("SELECT NavID, Qty FROM tblBundles WHERE ProductID = " . $_SESSION["Bundle12"]);
                 $arrayCounter = 0;
                 while($row = mssql_fetch_array($BundleItems12))
                 {
                     $BI12NavID[$arrayCounter] = $row["NavID"];
                     $BI12Qty[$arrayCounter] = $row["Qty"];
                     $arrayCounter++;
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $ProductBI12 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $BI12NavID[$c]);
                     while($row = mssql_fetch_array($ProductBI12))
                     {
                         $WeightBI12[$c] = $row["Weight"];
                         $BoxCountBI12[$c] = $row["BoxCount"];
                         $BoxWeightBI12[$c] = $row["BoxWeight"];
                         $BoxLengthBI12[$c] = $row["BoxLength"];
                         $BoxWidthBI12[$c] = $row["BoxWidth"];
                         $BoxHeightBI12[$c] = $row["BoxHeight"];
                     }
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $DivisionTotalBI12[$c] = ($Qty12 * $BI12Qty[$c])/$BoxCountBI12[$c];
                     $DivisionIntBI12[$c] = floor(($Qty12 * $BI12Qty[$c])/$BoxCountBI12[$c]);
                     $DivisionRemBI12[$c] = $DivisionTotalBI12[$c] - $DivisionIntBI12[$c];
                     $RemBalBI12[$c] = $DivisionRemBI12[$c] * $BoxCountBI12[$c];
                     $EntryWeightBI12[$c] = 1 * $BoxWeightBI12[$c];
                     $RemWtBI12[$c] = $RemBalBI12[$c] * $WeightBI12[$c];
                     $ItmQtyWtBI12[$c] = ($DivisionIntBI12[$c] * $EntryWeightBI12[$c]) + $RemWtBI12[$c];
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     if($DivisionIntBI12[$c] > 0)
                     {
                         for ($counter = 0; $counter < $DivisionIntBI12[$c]; $counter++)
                         {
                             $arrBoxCount[$counterBoxes] = 1;
                             $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeightBI12[$c],
                                 'Units' => 'LB'),
                                 'Dimensions' => array('Length' => $BoxLengthBI12[$c],
                                 'Width' => $BoxWidthBI12[$c],
                                 'Height' => $BoxHeightBI12[$c],
                                 'Units' => 'IN'));
                             $counterBoxes++;
                         }
                     }
                 }
             }

             if ($_SESSION["Bundle13"] != "")
             {
                 // Get all items from the bundle
                 $BundleItems13 =  mssql_query("SELECT NavID, Qty FROM tblBundles WHERE ProductID = " . $_SESSION["Bundle13"]);
                 $arrayCounter = 0;
                 while($row = mssql_fetch_array($BundleItems13))
                 {
                     $BI13NavID[$arrayCounter] = $row["NavID"];
                     $BI13Qty[$arrayCounter] = $row["Qty"];
                     $arrayCounter++;
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $ProductBI13 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $BI13NavID[$c]);
                     while($row = mssql_fetch_array($ProductBI13))
                     {
                         $WeightBI13[$c] = $row["Weight"];
                         $BoxCountBI13[$c] = $row["BoxCount"];
                         $BoxWeightBI13[$c] = $row["BoxWeight"];
                         $BoxLengthBI13[$c] = $row["BoxLength"];
                         $BoxWidthBI13[$c] = $row["BoxWidth"];
                         $BoxHeightBI13[$c] = $row["BoxHeight"];
                     }
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $DivisionTotalBI13[$c] = ($Qty13 * $BI13Qty[$c])/$BoxCountBI13[$c];
                     $DivisionIntBI13[$c] = floor(($Qty13 * $BI13Qty[$c])/$BoxCountBI13[$c]);
                     $DivisionRemBI13[$c] = $DivisionTotalBI13[$c] - $DivisionIntBI13[$c];
                     $RemBalBI13[$c] = $DivisionRemBI13[$c] * $BoxCountBI13[$c];
                     $EntryWeightBI13[$c] = 1 * $BoxWeightBI13[$c];
                     $RemWtBI13[$c] = $RemBalBI13[$c] * $WeightBI13[$c];
                     $ItmQtyWtBI13[$c] = ($DivisionIntBI13[$c] * $EntryWeightBI13[$c]) + $RemWtBI13[$c];
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     if($DivisionIntBI13[$c] > 0)
                     {
                         for ($counter = 0; $counter < $DivisionIntBI13[$c]; $counter++)
                         {
                             $arrBoxCount[$counterBoxes] = 1;
                             $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeightBI13[$c],
                                 'Units' => 'LB'),
                                 'Dimensions' => array('Length' => $BoxLengthBI13[$c],
                                 'Width' => $BoxWidthBI13[$c],
                                 'Height' => $BoxHeightBI13[$c],
                                 'Units' => 'IN'));
                             $counterBoxes++;
                         }
                     }
                 }
             }

             if ($_SESSION["Bundle14"] != "")
             {
                 // Get all items from the bundle
                 $BundleItems14 =  mssql_query("SELECT NavID, Qty FROM tblBundles WHERE ProductID = " . $_SESSION["Bundle14"]);
                 $arrayCounter = 0;
                 while($row = mssql_fetch_array($BundleItems14))
                 {
                     $BI14NavID[$arrayCounter] = $row["NavID"];
                     $BI14Qty[$arrayCounter] = $row["Qty"];
                     $arrayCounter++;
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $ProductBI14 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $BI14NavID[$c]);
                     while($row = mssql_fetch_array($ProductBI14))
                     {
                         $WeightBI14[$c] = $row["Weight"];
                         $BoxCountBI14[$c] = $row["BoxCount"];
                         $BoxWeightBI14[$c] = $row["BoxWeight"];
                         $BoxLengthBI14[$c] = $row["BoxLength"];
                         $BoxWidthBI14[$c] = $row["BoxWidth"];
                         $BoxHeightBI14[$c] = $row["BoxHeight"];
                     }
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $DivisionTotalBI14[$c] = ($Qty14 * $BI14Qty[$c])/$BoxCountBI14[$c];
                     $DivisionIntBI14[$c] = floor(($Qty14 * $BI14Qty[$c])/$BoxCountBI14[$c]);
                     $DivisionRemBI14[$c] = $DivisionTotalBI14[$c] - $DivisionIntBI14[$c];
                     $RemBalBI14[$c] = $DivisionRemBI14[$c] * $BoxCountBI14[$c];
                     $EntryWeightBI14[$c] = 1 * $BoxWeightBI14[$c];
                     $RemWtBI14[$c] = $RemBalBI14[$c] * $WeightBI14[$c];
                     $ItmQtyWtBI14[$c] = ($DivisionIntBI14[$c] * $EntryWeightBI14[$c]) + $RemWtBI14[$c];
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     if($DivisionIntBI14[$c] > 0)
                     {
                         for ($counter = 0; $counter < $DivisionIntBI14[$c]; $counter++)
                         {
                             $arrBoxCount[$counterBoxes] = 1;
                             $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeightBI14[$c],
                                 'Units' => 'LB'),
                                 'Dimensions' => array('Length' => $BoxLengthBI14[$c],
                                 'Width' => $BoxWidthBI14[$c],
                                 'Height' => $BoxHeightBI14[$c],
                                 'Units' => 'IN'));
                             $counterBoxes++;
                         }
                     }
                 }
             }

             if ($_SESSION["Bundle15"] != "")
             {
                 // Get all items from the bundle
                 $BundleItems15 =  mssql_query("SELECT NavID, Qty FROM tblBundles WHERE ProductID = " . $_SESSION["Bundle15"]);
                 $arrayCounter = 0;
                 while($row = mssql_fetch_array($BundleItems15))
                 {
                     $BI15NavID[$arrayCounter] = $row["NavID"];
                     $BI15Qty[$arrayCounter] = $row["Qty"];
                     $arrayCounter++;
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $ProductBI15 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $BI15NavID[$c]);
                     while($row = mssql_fetch_array($ProductBI15))
                     {
                         $WeightBI15[$c] = $row["Weight"];
                         $BoxCountBI15[$c] = $row["BoxCount"];
                         $BoxWeightBI15[$c] = $row["BoxWeight"];
                         $BoxLengthBI15[$c] = $row["BoxLength"];
                         $BoxWidthBI15[$c] = $row["BoxWidth"];
                         $BoxHeightBI15[$c] = $row["BoxHeight"];
                     }
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $DivisionTotalBI15[$c] = ($Qty15 * $BI15Qty[$c])/$BoxCountBI15[$c];
                     $DivisionIntBI15[$c] = floor(($Qty15 * $BI15Qty[$c])/$BoxCountBI15[$c]);
                     $DivisionRemBI15[$c] = $DivisionTotalBI15[$c] - $DivisionIntBI15[$c];
                     $RemBalBI15[$c] = $DivisionRemBI15[$c] * $BoxCountBI15[$c];
                     $EntryWeightBI15[$c] = 1 * $BoxWeightBI15[$c];
                     $RemWtBI15[$c] = $RemBalBI15[$c] * $WeightBI15[$c];
                     $ItmQtyWtBI15[$c] = ($DivisionIntBI15[$c] * $EntryWeightBI15[$c]) + $RemWtBI15[$c];
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     if($DivisionIntBI15[$c] > 0)
                     {
                         for ($counter = 0; $counter < $DivisionIntBI15[$c]; $counter++)
                         {
                             $arrBoxCount[$counterBoxes] = 1;
                             $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeightBI15[$c],
                                 'Units' => 'LB'),
                                 'Dimensions' => array('Length' => $BoxLengthBI15[$c],
                                 'Width' => $BoxWidthBI15[$c],
                                 'Height' => $BoxHeightBI15[$c],
                                 'Units' => 'IN'));
                             $counterBoxes++;
                         }
                     }
                 }
             }

             if ($_SESSION["Bundle16"] != "")
             {
                 // Get all items from the bundle
                 $BundleItems16 =  mssql_query("SELECT NavID, Qty FROM tblBundles WHERE ProductID = " . $_SESSION["Bundle16"]);
                 $arrayCounter = 0;
                 while($row = mssql_fetch_array($BundleItems16))
                 {
                     $BI16NavID[$arrayCounter] = $row["NavID"];
                     $BI16Qty[$arrayCounter] = $row["Qty"];
                     $arrayCounter++;
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $ProductBI16 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $BI16NavID[$c]);
                     while($row = mssql_fetch_array($ProductBI16))
                     {
                         $WeightBI16[$c] = $row["Weight"];
                         $BoxCountBI16[$c] = $row["BoxCount"];
                         $BoxWeightBI16[$c] = $row["BoxWeight"];
                         $BoxLengthBI16[$c] = $row["BoxLength"];
                         $BoxWidthBI16[$c] = $row["BoxWidth"];
                         $BoxHeightBI16[$c] = $row["BoxHeight"];
                     }
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $DivisionTotalBI16[$c] = ($Qty16 * $BI16Qty[$c])/$BoxCountBI16[$c];
                     $DivisionIntBI16[$c] = floor(($Qty16 * $BI16Qty[$c])/$BoxCountBI16[$c]);
                     $DivisionRemBI16[$c] = $DivisionTotalBI16[$c] - $DivisionIntBI16[$c];
                     $RemBalBI16[$c] = $DivisionRemBI16[$c] * $BoxCountBI16[$c];
                     $EntryWeightBI16[$c] = 1 * $BoxWeightBI16[$c];
                     $RemWtBI16[$c] = $RemBalBI16[$c] * $WeightBI16[$c];
                     $ItmQtyWtBI16[$c] = ($DivisionIntBI16[$c] * $EntryWeightBI16[$c]) + $RemWtBI16[$c];
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     if($DivisionIntBI16[$c] > 0)
                     {
                         for ($counter = 0; $counter < $DivisionIntBI16[$c]; $counter++)
                         {
                             $arrBoxCount[$counterBoxes] = 1;
                             $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeightBI16[$c],
                                 'Units' => 'LB'),
                                 'Dimensions' => array('Length' => $BoxLengthBI16[$c],
                                 'Width' => $BoxWidthBI16[$c],
                                 'Height' => $BoxHeightBI16[$c],
                                 'Units' => 'IN'));
                             $counterBoxes++;
                         }
                     }
                 }
             }

             if ($_SESSION["Bundle17"] != "")
             {
                 // Get all items from the bundle
                 $BundleItems17 =  mssql_query("SELECT NavID, Qty FROM tblBundles WHERE ProductID = " . $_SESSION["Bundle17"]);
                 $arrayCounter = 0;
                 while($row = mssql_fetch_array($BundleItems17))
                 {
                     $BI17NavID[$arrayCounter] = $row["NavID"];
                     $BI17Qty[$arrayCounter] = $row["Qty"];
                     $arrayCounter++;
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $ProductBI17 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $BI17NavID[$c]);
                     while($row = mssql_fetch_array($ProductBI17))
                     {
                         $WeightBI17[$c] = $row["Weight"];
                         $BoxCountBI17[$c] = $row["BoxCount"];
                         $BoxWeightBI17[$c] = $row["BoxWeight"];
                         $BoxLengthBI17[$c] = $row["BoxLength"];
                         $BoxWidthBI17[$c] = $row["BoxWidth"];
                         $BoxHeightBI17[$c] = $row["BoxHeight"];
                     }
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $DivisionTotalBI17[$c] = ($Qty17 * $BI17Qty[$c])/$BoxCountBI17[$c];
                     $DivisionIntBI17[$c] = floor(($Qty17 * $BI17Qty[$c])/$BoxCountBI17[$c]);
                     $DivisionRemBI17[$c] = $DivisionTotalBI17[$c] - $DivisionIntBI17[$c];
                     $RemBalBI17[$c] = $DivisionRemBI17[$c] * $BoxCountBI17[$c];
                     $EntryWeightBI17[$c] = 1 * $BoxWeightBI17[$c];
                     $RemWtBI17[$c] = $RemBalBI17[$c] * $WeightBI17[$c];
                     $ItmQtyWtBI17[$c] = ($DivisionIntBI17[$c] * $EntryWeightBI17[$c]) + $RemWtBI17[$c];
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     if($DivisionIntBI17[$c] > 0)
                     {
                         for ($counter = 0; $counter < $DivisionIntBI17[$c]; $counter++)
                         {
                             $arrBoxCount[$counterBoxes] = 1;
                             $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeightBI17[$c],
                                 'Units' => 'LB'),
                                 'Dimensions' => array('Length' => $BoxLengthBI17[$c],
                                 'Width' => $BoxWidthBI17[$c],
                                 'Height' => $BoxHeightBI17[$c],
                                 'Units' => 'IN'));
                             $counterBoxes++;
                         }
                     }
                 }
             }

             if ($_SESSION["Bundle18"] != "")
             {
                 // Get all items from the bundle
                 $BundleItems18 =  mssql_query("SELECT NavID, Qty FROM tblBundles WHERE ProductID = " . $_SESSION["Bundle18"]);
                 $arrayCounter = 0;
                 while($row = mssql_fetch_array($BundleItems18))
                 {
                     $BI18NavID[$arrayCounter] = $row["NavID"];
                     $BI18Qty[$arrayCounter] = $row["Qty"];
                     $arrayCounter++;
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $ProductBI18 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $BI18NavID[$c]);
                     while($row = mssql_fetch_array($ProductBI18))
                     {
                         $WeightBI18[$c] = $row["Weight"];
                         $BoxCountBI18[$c] = $row["BoxCount"];
                         $BoxWeightBI18[$c] = $row["BoxWeight"];
                         $BoxLengthBI18[$c] = $row["BoxLength"];
                         $BoxWidthBI18[$c] = $row["BoxWidth"];
                         $BoxHeightBI18[$c] = $row["BoxHeight"];
                     }
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $DivisionTotalBI18[$c] = ($Qty18 * $BI18Qty[$c])/$BoxCountBI18[$c];
                     $DivisionIntBI18[$c] = floor(($Qty18 * $BI18Qty[$c])/$BoxCountBI18[$c]);
                     $DivisionRemBI18[$c] = $DivisionTotalBI18[$c] - $DivisionIntBI18[$c];
                     $RemBalBI18[$c] = $DivisionRemBI18[$c] * $BoxCountBI18[$c];
                     $EntryWeightBI18[$c] = 1 * $BoxWeightBI18[$c];
                     $RemWtBI18[$c] = $RemBalBI18[$c] * $WeightBI18[$c];
                     $ItmQtyWtBI18[$c] = ($DivisionIntBI18[$c] * $EntryWeightBI18[$c]) + $RemWtBI18[$c];
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     if($DivisionIntBI18[$c] > 0)
                     {
                         for ($counter = 0; $counter < $DivisionIntBI18[$c]; $counter++)
                         {
                             $arrBoxCount[$counterBoxes] = 1;
                             $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeightBI18[$c],
                                 'Units' => 'LB'),
                                 'Dimensions' => array('Length' => $BoxLengthBI18[$c],
                                 'Width' => $BoxWidthBI18[$c],
                                 'Height' => $BoxHeightBI18[$c],
                                 'Units' => 'IN'));
                             $counterBoxes++;
                         }
                     }
                 }
             }

             if ($_SESSION["Bundle19"] != "")
             {
                 // Get all items from the bundle
                 $BundleItems19 =  mssql_query("SELECT NavID, Qty FROM tblBundles WHERE ProductID = " . $_SESSION["Bundle19"]);
                 $arrayCounter = 0;
                 while($row = mssql_fetch_array($BundleItems19))
                 {
                     $BI19NavID[$arrayCounter] = $row["NavID"];
                     $BI19Qty[$arrayCounter] = $row["Qty"];
                     $arrayCounter++;
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $ProductBI19 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $BI19NavID[$c]);
                     while($row = mssql_fetch_array($ProductBI19))
                     {
                         $WeightBI19[$c] = $row["Weight"];
                         $BoxCountBI19[$c] = $row["BoxCount"];
                         $BoxWeightBI19[$c] = $row["BoxWeight"];
                         $BoxLengthBI19[$c] = $row["BoxLength"];
                         $BoxWidthBI19[$c] = $row["BoxWidth"];
                         $BoxHeightBI19[$c] = $row["BoxHeight"];
                     }
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $DivisionTotalBI19[$c] = ($Qty19 * $BI19Qty[$c])/$BoxCountBI19[$c];
                     $DivisionIntBI19[$c] = floor(($Qty19 * $BI19Qty[$c])/$BoxCountBI19[$c]);
                     $DivisionRemBI19[$c] = $DivisionTotalBI19[$c] - $DivisionIntBI19[$c];
                     $RemBalBI19[$c] = $DivisionRemBI19[$c] * $BoxCountBI19[$c];
                     $EntryWeightBI19[$c] = 1 * $BoxWeightBI19[$c];
                     $RemWtBI19[$c] = $RemBalBI19[$c] * $WeightBI19[$c];
                     $ItmQtyWtBI19[$c] = ($DivisionIntBI19[$c] * $EntryWeightBI19[$c]) + $RemWtBI19[$c];
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     if($DivisionIntBI19[$c] > 0)
                     {
                         for ($counter = 0; $counter < $DivisionIntBI19[$c]; $counter++)
                         {
                             $arrBoxCount[$counterBoxes] = 1;
                             $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeightBI19[$c],
                                 'Units' => 'LB'),
                                 'Dimensions' => array('Length' => $BoxLengthBI19[$c],
                                 'Width' => $BoxWidthBI19[$c],
                                 'Height' => $BoxHeightBI19[$c],
                                 'Units' => 'IN'));
                             $counterBoxes++;
                         }
                     }
                 }
             }

             if ($_SESSION["Bundle20"] != "")
             {
                 // Get all items from the bundle
                 $BundleItems20 =  mssql_query("SELECT NavID, Qty FROM tblBundles WHERE ProductID = " . $_SESSION["Bundle20"]);
                 $arrayCounter = 0;
                 while($row = mssql_fetch_array($BundleItems20))
                 {
                     $BI20NavID[$arrayCounter] = $row["NavID"];
                     $BI20Qty[$arrayCounter] = $row["Qty"];
                     $arrayCounter++;
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $ProductBI20 = mssql_query("SELECT Weight, BoxCount, BoxWeight, BoxLength, BoxWidth, BoxHeight, PartNumber FROM tblProducts WHERE RecordId = " . $BI20NavID[$c]);
                     while($row = mssql_fetch_array($ProductBI20))
                     {
                         $WeightBI20[$c] = $row["Weight"];
                         $BoxCountBI20[$c] = $row["BoxCount"];
                         $BoxWeightBI20[$c] = $row["BoxWeight"];
                         $BoxLengthBI20[$c] = $row["BoxLength"];
                         $BoxWidthBI20[$c] = $row["BoxWidth"];
                         $BoxHeightBI20[$c] = $row["BoxHeight"];
                     }
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     $DivisionTotalBI20[$c] = ($Qty20 * $BI20Qty[$c])/$BoxCountBI20[$c];
                     $DivisionIntBI20[$c] = floor(($Qty20 * $BI20Qty[$c])/$BoxCountBI20[$c]);
                     $DivisionRemBI20[$c] = $DivisionTotalBI20[$c] - $DivisionIntBI20[$c];
                     $RemBalBI20[$c] = $DivisionRemBI20[$c] * $BoxCountBI20[$c];
                     $EntryWeightBI20[$c] = 1 * $BoxWeightBI20[$c];
                     $RemWtBI20[$c] = $RemBalBI20[$c] * $WeightBI20[$c];
                     $ItmQtyWtBI20[$c] = ($DivisionIntBI20[$c] * $EntryWeightBI20[$c]) + $RemWtBI20[$c];
                 }

                 for ($c = 0; $c < $arrayCounter; $c++)
                 {
                     if($DivisionIntBI20[$c] > 0)
                     {
                         for ($counter = 0; $counter < $DivisionIntBI20[$c]; $counter++)
                         {
                             $arrBoxCount[$counterBoxes] = 1;
                             $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $EntryWeightBI20[$c],
                                 'Units' => 'LB'),
                                 'Dimensions' => array('Length' => $BoxLengthBI20[$c],
                                 'Width' => $BoxWidthBI20[$c],
                                 'Height' => $BoxHeightBI20[$c],
                                 'Units' => 'IN'));
                             $counterBoxes++;
                         }
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
            <th width="120" style="text-align:left"></th>
            <th width="120" style="text-align:left">Total Box Count</th>
	        <th width="120" style="text-align:left">Total ITM WT</th>
            <th width="120" style="text-align:left"></th>
	        <th width="120" style="text-align:left">Total Rem Bal Box</th>
            <th width="120" style="text-align:left">Total Rem Weight</th>
            <th width="120" style="text-align:left">Total Order WT</th>
         </tr>

         <?php

           $DIB1 = $DIB2 = $DIB3 = $DIB4 = $DIB5 = $DIB6 = $DIB7 = $DIB8 = $DIB9 = $DIB10 = $DIB11 = $DIB12 = $DIB13 = $DIB14 = $DIB15 = $DIB16 = $DIB17 = $DIB18 = $DIB19 = $DIB20 = 0;
           $RWB1 = $RWB2 = $RWB3 = $RWB4 = $RWB5 = $RWB6 = $RWB7 = $RWB8 = $RWB9 = $RWB10 = $RWB11 = $RWB12 = $RWB13 = $RWB14 = $RWB15 = $RWB16 = $RWB17 = $RWB18 = $RWB19 = $RWB20 = 0;
           $EWB1 = $EWB2 = $EWB3 = $EWB4 = $EWB5 = $EWB6 = $EWB7 = $EWB8 = $EWB9 = $EWB10 = $EWB11 = $EWB12 = $EWB13 = $EWB14 = $EWB15 = $EWB16 = $EWB17 = $EWB18 = $EWB19 = $EWB20 = 0;
           $RBB1 = $RBB2 = $RBB3 = $RBB4 = $RBB5 = $RBB6 = $RBB7 = $RBB8 = $RBB9 = $RBB10 = $RBB11 = $RBB12 = $RBB13 = $RBB14 = $RBB15 = $RBB16 = $RBB17 = $RBB18 = $RBB19 = $RBB20 = 0;
           $IQB1 = $IQB2 = $IQB3 = $IQB4 = $IQB5 = $IQB6 = $IQB7 = $IQB8 = $IQB9 = $IQB10 = $IQB11 = $IQB12 = $IQB13 = $IQB14 = $IQB15 = $IQB16 = $IQB17 = $IQB18 = $IQB19 = $IQB20 = 0;

           if ($_SESSION["Bundle1"] != "")
           {
               for ($c = 0; $c < $arrayCounter; $c++)
               {
                   $DIB1 = $DIB1 + $DivisionIntBI1[$c];
                   $RWB1 = $RWB1 + $RemWtBI1[$c];
                   $EWB1 = $EWB1 + $EntryWeightBI1[$c];
                   $RBB1 = $RBB1 + $RemBalBI1[$c];
                   $IQB1 = $IQB1 + $ItmQtyWtBI1[$c];
               }

               // GMC - 10/03/12 - Display Bundle Item Number, Description and Qty
               $BundleInfo1 = mssql_query("SELECT ProductName, PartNumber FROM tblProducts WHERE RecordId = " . $_SESSION["Bundle1"]);
               while($row = mssql_fetch_array($BundleInfo1))
               {
                   $_SESSION["BundlesInformation"] = "Admin RecordID: " .  $_SESSION["Bundle1"] . " - Product Name: " . $row["ProductName"] . " - NavID : " . $row["PartNumber"] . " - Qty: " . $Qty1 . "\n";
               }
           }

           if ($_SESSION["Bundle2"] != "")
           {
               for ($c = 0; $c < $arrayCounter; $c++)
               {
                   $DIB2 = $DIB2 + $DivisionIntBI2[$c];
                   $RWB2 = $RWB2 + $RemWtBI2[$c];
                   $EWB2 = $EWB2 + $EntryWeightBI2[$c];
                   $RBB2 = $RBB2 + $RemBalBI2[$c];
                   $IQB2 = $IQB2 + $ItmQtyWtBI2[$c];
               }

               // GMC - 10/03/12 - Display Bundle Item Number, Description and Qty
               $BundleInfo2 = mssql_query("SELECT ProductName, PartNumber FROM tblProducts WHERE RecordId = " . $_SESSION["Bundle2"]);
               while($row = mssql_fetch_array($BundleInfo2))
               {
                   $_SESSION["BundlesInformation"] += "Admin RecordID: " .  $_SESSION["Bundle2"] . " - Product Name: " . $row["ProductName"] . " - NavID : " . $row["PartNumber"] . " - Qty: " . $Qty2 . "\n";
               }
           }

           if ($_SESSION["Bundle3"] != "")
           {
               for ($c = 0; $c < $arrayCounter; $c++)
               {
                   $DIB3 = $DIB3 + $DivisionIntBI3[$c];
                   $RWB3 = $RWB3 + $RemWtBI3[$c];
                   $EWB3 = $EWB3 + $EntryWeightBI3[$c];
                   $RBB3 = $RBB3 + $RemBalBI3[$c];
                   $IQB3 = $IQB3 + $ItmQtyWtBI3[$c];
               }

               // GMC - 10/03/12 - Display Bundle Item Number, Description and Qty
               $BundleInfo3 = mssql_query("SELECT ProductName, PartNumber FROM tblProducts WHERE RecordId = " . $_SESSION["Bundle3"]);
               while($row = mssql_fetch_array($BundleInfo3))
               {
                   $_SESSION["BundlesInformation"] += "Admin RecordID: " .  $_SESSION["Bundle3"] . " - Product Name: " . $row["ProductName"] . " - NavID : " . $row["PartNumber"] . " - Qty: " . $Qty3 . "\n";
               }
           }

           if ($_SESSION["Bundle4"] != "")
           {
               for ($c = 0; $c < $arrayCounter; $c++)
               {
                   $DIB4 = $DIB4 + $DivisionIntBI4[$c];
                   $RWB4 = $RWB4 + $RemWtBI4[$c];
                   $EWB4 = $EWB4 + $EntryWeightBI4[$c];
                   $RBB4 = $RBB4 + $RemBalBI4[$c];
                   $IQB4 = $IQB4 + $ItmQtyWtBI4[$c];
               }

               // GMC - 10/03/12 - Display Bundle Item Number, Description and Qty
               $BundleInfo4 = mssql_query("SELECT ProductName, PartNumber FROM tblProducts WHERE RecordId = " . $_SESSION["Bundle4"]);
               while($row = mssql_fetch_array($BundleInfo4))
               {
                   $_SESSION["BundlesInformation"] += "Admin RecordID: " .  $_SESSION["Bundle4"] . " - Product Name: " . $row["ProductName"] . " - NavID : " . $row["PartNumber"] . " - Qty: " . $Qty4 . "\n";
               }
           }

           if ($_SESSION["Bundle5"] != "")
           {
               for ($c = 0; $c < $arrayCounter; $c++)
               {
                   $DIB5 = $DIB5 + $DivisionIntBI5[$c];
                   $RWB5 = $RWB5 + $RemWtBI5[$c];
                   $EWB5 = $EWB5 + $EntryWeightBI5[$c];
                   $RBB5 = $RBB5 + $RemBalBI5[$c];
                   $IQB5 = $IQB5 + $ItmQtyWtBI5[$c];
               }

               // GMC - 10/03/12 - Display Bundle Item Number, Description and Qty
               $BundleInfo5 = mssql_query("SELECT ProductName, PartNumber FROM tblProducts WHERE RecordId = " . $_SESSION["Bundle5"]);
               while($row = mssql_fetch_array($BundleInfo5))
               {
                   $_SESSION["BundlesInformation"] += "Admin RecordID: " .  $_SESSION["Bundle5"] . " - Product Name: " . $row["ProductName"] . " - NavID : " . $row["PartNumber"] . " - Qty: " . $Qty5 . "\n";
               }
           }

           if ($_SESSION["Bundle6"] != "")
           {
               for ($c = 0; $c < $arrayCounter; $c++)
               {
                   $DIB6 = $DIB6 + $DivisionIntBI6[$c];
                   $RWB6 = $RWB6 + $RemWtBI6[$c];
                   $EWB6 = $EWB6 + $EntryWeightBI6[$c];
                   $RBB6 = $RBB6 + $RemBalBI6[$c];
                   $IQB6 = $IQB6 + $ItmQtyWtBI6[$c];
               }

               // GMC - 10/03/12 - Display Bundle Item Number, Description and Qty
               $BundleInfo6 = mssql_query("SELECT ProductName, PartNumber FROM tblProducts WHERE RecordId = " . $_SESSION["Bundle6"]);
               while($row = mssql_fetch_array($BundleInfo6))
               {
                   $_SESSION["BundlesInformation"] += "Admin RecordID: " .  $_SESSION["Bundle6"] . " - Product Name: " . $row["ProductName"] . " - NavID : " . $row["PartNumber"] . " - Qty: " . $Qty6 . "\n";
               }
           }

           if ($_SESSION["Bundle7"] != "")
           {
               for ($c = 0; $c < $arrayCounter; $c++)
               {
                   $DIB7 = $DIB7 + $DivisionIntBI7[$c];
                   $RWB7 = $RWB7 + $RemWtBI7[$c];
                   $EWB7 = $EWB7 + $EntryWeightBI7[$c];
                   $RBB7 = $RBB7 + $RemBalBI7[$c];
                   $IQB7 = $IQB7 + $ItmQtyWtBI7[$c];
               }

               // GMC - 10/03/12 - Display Bundle Item Number, Description and Qty
               $BundleInfo7 = mssql_query("SELECT ProductName, PartNumber FROM tblProducts WHERE RecordId = " . $_SESSION["Bundle7"]);
               while($row = mssql_fetch_array($BundleInfo7))
               {
                   $_SESSION["BundlesInformation"] += "Admin RecordID: " .  $_SESSION["Bundle7"] . " - Product Name: " . $row["ProductName"] . " - NavID : " . $row["PartNumber"] . " - Qty: " . $Qty7 . "\n";
               }
           }

           if ($_SESSION["Bundle8"] != "")
           {
               for ($c = 0; $c < $arrayCounter; $c++)
               {
                   $DIB8 = $DIB8 + $DivisionIntBI8[$c];
                   $RWB8 = $RWB8 + $RemWtBI8[$c];
                   $EWB8 = $EWB8 + $EntryWeightBI8[$c];
                   $RBB8 = $RBB8 + $RemBalBI8[$c];
                   $IQB8 = $IQB8 + $ItmQtyWtBI8[$c];
               }

               // GMC - 10/03/12 - Display Bundle Item Number, Description and Qty
               $BundleInfo8 = mssql_query("SELECT ProductName, PartNumber FROM tblProducts WHERE RecordId = " . $_SESSION["Bundle8"]);
               while($row = mssql_fetch_array($BundleInfo8))
               {
                   $_SESSION["BundlesInformation"] += "Admin RecordID: " .  $_SESSION["Bundle8"] . " - Product Name: " . $row["ProductName"] . " - NavID : " . $row["PartNumber"] . " - Qty: " . $Qty8 . "\n";
               }
           }

           if ($_SESSION["Bundle9"] != "")
           {
               for ($c = 0; $c < $arrayCounter; $c++)
               {
                   $DIB9 = $DIB9 + $DivisionIntBI9[$c];
                   $RWB9 = $RWB9 + $RemWtBI9[$c];
                   $EWB9 = $EWB9 + $EntryWeightBI9[$c];
                   $RBB9 = $RBB9 + $RemBalBI9[$c];
                   $IQB9 = $IQB9 + $ItmQtyWtBI9[$c];
               }

               // GMC - 10/03/12 - Display Bundle Item Number, Description and Qty
               $BundleInfo9 = mssql_query("SELECT ProductName, PartNumber FROM tblProducts WHERE RecordId = " . $_SESSION["Bundle9"]);
               while($row = mssql_fetch_array($BundleInfo9))
               {
                   $_SESSION["BundlesInformation"] += "Admin RecordID: " .  $_SESSION["Bundle9"] . " - Product Name: " . $row["ProductName"] . " - NavID : " . $row["PartNumber"] . " - Qty: " . $Qty9 . "\n";
               }
           }

           if ($_SESSION["Bundle10"] != "")
           {
               for ($c = 0; $c < $arrayCounter; $c++)
               {
                   $DIB10 = $DIB10 + $DivisionIntBI10[$c];
                   $RWB10 = $RWB10 + $RemWtBI10[$c];
                   $EWB10 = $EWB10 + $EntryWeightBI10[$c];
                   $RBB10 = $RBB10 + $RemBalBI10[$c];
                   $IQB10 = $IQB10 + $ItmQtyWtBI10[$c];
               }

               // GMC - 10/03/12 - Display Bundle Item Number, Description and Qty
               $BundleInfo10 = mssql_query("SELECT ProductName, PartNumber FROM tblProducts WHERE RecordId = " . $_SESSION["Bundle10"]);
               while($row = mssql_fetch_array($BundleInfo10))
               {
                   $_SESSION["BundlesInformation"] += "Admin RecordID: " .  $_SESSION["Bundle10"] . " - Product Name: " . $row["ProductName"] . " - NavID : " . $row["PartNumber"] . " - Qty: " . $Qty10 . "\n";
               }
           }

           if ($_SESSION["Bundle11"] != "")
           {
               for ($c = 0; $c < $arrayCounter; $c++)
               {
                   $DIB11 = $DIB11 + $DivisionIntBI11[$c];
                   $RWB11 = $RWB11 + $RemWtBI11[$c];
                   $EWB11 = $EWB11 + $EntryWeightBI11[$c];
                   $RBB11 = $RBB11 + $RemBalBI11[$c];
                   $IQB11 = $IQB11 + $ItmQtyWtBI11[$c];
               }

               // GMC - 10/03/12 - Display Bundle Item Number, Description and Qty
               $BundleInfo11 = mssql_query("SELECT ProductName, PartNumber FROM tblProducts WHERE RecordId = " . $_SESSION["Bundle11"]);
               while($row = mssql_fetch_array($BundleInfo11))
               {
                   $_SESSION["BundlesInformation"] += "Admin RecordID: " .  $_SESSION["Bundle11"] . " - Product Name: " . $row["ProductName"] . " - NavID : " . $row["PartNumber"] . " - Qty: " . $Qty11 . "\n";
               }
           }

           if ($_SESSION["Bundle12"] != "")
           {
               for ($c = 0; $c < $arrayCounter; $c++)
               {
                   $DIB12 = $DIB12 + $DivisionIntBI12[$c];
                   $RWB12 = $RWB12 + $RemWtBI12[$c];
                   $EWB12 = $EWB12 + $EntryWeightBI12[$c];
                   $RBB12 = $RBB12 + $RemBalBI12[$c];
                   $IQB12 = $IQB12 + $ItmQtyWtBI12[$c];
               }

               // GMC - 10/03/12 - Display Bundle Item Number, Description and Qty
               $BundleInfo12 = mssql_query("SELECT ProductName, PartNumber FROM tblProducts WHERE RecordId = " . $_SESSION["Bundle12"]);
               while($row = mssql_fetch_array($BundleInfo12))
               {
                   $_SESSION["BundlesInformation"] += "Admin RecordID: " .  $_SESSION["Bundle12"] . " - Product Name: " . $row["ProductName"] . " - NavID : " . $row["PartNumber"] . " - Qty: " . $Qty12 . "\n";
               }
           }

           if ($_SESSION["Bundle13"] != "")
           {
               for ($c = 0; $c < $arrayCounter; $c++)
               {
                   $DIB13 = $DIB13 + $DivisionIntBI13[$c];
                   $RWB13 = $RWB13 + $RemWtBI13[$c];
                   $EWB13 = $EWB13 + $EntryWeightBI13[$c];
                   $RBB13 = $RBB13 + $RemBalBI13[$c];
                   $IQB13 = $IQB13 + $ItmQtyWtBI13[$c];
               }

               // GMC - 10/03/12 - Display Bundle Item Number, Description and Qty
               $BundleInfo13 = mssql_query("SELECT ProductName, PartNumber FROM tblProducts WHERE RecordId = " . $_SESSION["Bundle13"]);
               while($row = mssql_fetch_array($BundleInfo13))
               {
                   $_SESSION["BundlesInformation"] += "Admin RecordID: " .  $_SESSION["Bundle13"] . " - Product Name: " . $row["ProductName"] . " - NavID : " . $row["PartNumber"] . " - Qty: " . $Qty13 . "\n";
               }
           }

           if ($_SESSION["Bundle14"] != "")
           {
               for ($c = 0; $c < $arrayCounter; $c++)
               {
                   $DIB14 = $DIB14 + $DivisionIntBI14[$c];
                   $RWB14 = $RWB14 + $RemWtBI14[$c];
                   $EWB14 = $EWB14 + $EntryWeightBI14[$c];
                   $RBB14 = $RBB14 + $RemBalBI14[$c];
                   $IQB14 = $IQB14 + $ItmQtyWtBI14[$c];
               }

               // GMC - 10/03/12 - Display Bundle Item Number, Description and Qty
               $BundleInfo14 = mssql_query("SELECT ProductName, PartNumber FROM tblProducts WHERE RecordId = " . $_SESSION["Bundle14"]);
               while($row = mssql_fetch_array($BundleInfo14))
               {
                   $_SESSION["BundlesInformation"] += "Admin RecordID: " .  $_SESSION["Bundle14"] . " - Product Name: " . $row["ProductName"] . " - NavID : " . $row["PartNumber"] . " - Qty: " . $Qty14 . "\n";
               }
           }

           if ($_SESSION["Bundle15"] != "")
           {
               for ($c = 0; $c < $arrayCounter; $c++)
               {
                   $DIB15 = $DIB15 + $DivisionIntBI15[$c];
                   $RWB15 = $RWB15 + $RemWtBI15[$c];
                   $EWB15 = $EWB15 + $EntryWeightBI15[$c];
                   $RBB15 = $RBB15 + $RemBalBI15[$c];
                   $IQB15 = $IQB15 + $ItmQtyWtBI15[$c];
               }

               // GMC - 10/03/12 - Display Bundle Item Number, Description and Qty
               $BundleInfo15 = mssql_query("SELECT ProductName, PartNumber FROM tblProducts WHERE RecordId = " . $_SESSION["Bundle15"]);
               while($row = mssql_fetch_array($BundleInfo15))
               {
                   $_SESSION["BundlesInformation"] += "Admin RecordID: " .  $_SESSION["Bundle15"] . " - Product Name: " . $row["ProductName"] . " - NavID : " . $row["PartNumber"] . " - Qty: " . $Qty15 . "\n";
               }
           }

           if ($_SESSION["Bundle16"] != "")
           {
               for ($c = 0; $c < $arrayCounter; $c++)
               {
                   $DIB16 = $DIB16 + $DivisionIntBI16[$c];
                   $RWB16 = $RWB16 + $RemWtBI16[$c];
                   $EWB16 = $EWB16 + $EntryWeightBI16[$c];
                   $RBB16 = $RBB16 + $RemBalBI16[$c];
                   $IQB16 = $IQB16 + $ItmQtyWtBI16[$c];
               }

               // GMC - 10/03/12 - Display Bundle Item Number, Description and Qty
               $BundleInfo16 = mssql_query("SELECT ProductName, PartNumber FROM tblProducts WHERE RecordId = " . $_SESSION["Bundle16"]);
               while($row = mssql_fetch_array($BundleInfo16))
               {
                   $_SESSION["BundlesInformation"] += "Admin RecordID: " .  $_SESSION["Bundle16"] . " - Product Name: " . $row["ProductName"] . " - NavID : " . $row["PartNumber"] . " - Qty: " . $Qty16 . "\n";
               }
           }

           if ($_SESSION["Bundle17"] != "")
           {
               for ($c = 0; $c < $arrayCounter; $c++)
               {
                   $DIB17 = $DIB17 + $DivisionIntBI17[$c];
                   $RWB17 = $RWB17 + $RemWtBI17[$c];
                   $EWB17 = $EWB17 + $EntryWeightBI17[$c];
                   $RBB17 = $RBB17 + $RemBalBI17[$c];
                   $IQB17 = $IQB17 + $ItmQtyWtBI17[$c];
               }

               // GMC - 10/03/12 - Display Bundle Item Number, Description and Qty
               $BundleInfo17 = mssql_query("SELECT ProductName, PartNumber FROM tblProducts WHERE RecordId = " . $_SESSION["Bundle17"]);
               while($row = mssql_fetch_array($BundleInfo17))
               {
                   $_SESSION["BundlesInformation"] += "Admin RecordID: " .  $_SESSION["Bundle17"] . " - Product Name: " . $row["ProductName"] . " - NavID : " . $row["PartNumber"] . " - Qty: " . $Qty17 . "\n";
               }
           }

           if ($_SESSION["Bundle18"] != "")
           {
               for ($c = 0; $c < $arrayCounter; $c++)
               {
                   $DIB18 = $DIB18 + $DivisionIntBI18[$c];
                   $RWB18 = $RWB18 + $RemWtBI18[$c];
                   $EWB18 = $EWB18 + $EntryWeightBI18[$c];
                   $RBB18 = $RBB18 + $RemBalBI18[$c];
                   $IQB18 = $IQB18 + $ItmQtyWtBI18[$c];
               }

               // GMC - 10/03/12 - Display Bundle Item Number, Description and Qty
               $BundleInfo18 = mssql_query("SELECT ProductName, PartNumber FROM tblProducts WHERE RecordId = " . $_SESSION["Bundle18"]);
               while($row = mssql_fetch_array($BundleInfo18))
               {
                   $_SESSION["BundlesInformation"] += "Admin RecordID: " .  $_SESSION["Bundle18"] . " - Product Name: " . $row["ProductName"] . " - NavID : " . $row["PartNumber"] . " - Qty: " . $Qty18 . "\n";
               }
           }

           if ($_SESSION["Bundle19"] != "")
           {
               for ($c = 0; $c < $arrayCounter; $c++)
               {
                   $DIB19 = $DIB19 + $DivisionIntBI19[$c];
                   $RWB19 = $RWB19 + $RemWtBI19[$c];
                   $EWB19 = $EWB19 + $EntryWeightBI19[$c];
                   $RBB19 = $RBB19 + $RemBalBI19[$c];
                   $IQB19 = $IQB19 + $ItmQtyWtBI19[$c];
               }

               // GMC - 10/03/12 - Display Bundle Item Number, Description and Qty
               $BundleInfo19 = mssql_query("SELECT ProductName, PartNumber FROM tblProducts WHERE RecordId = " . $_SESSION["Bundle19"]);
               while($row = mssql_fetch_array($BundleInfo19))
               {
                   $_SESSION["BundlesInformation"] += "Admin RecordID: " .  $_SESSION["Bundle19"] . " - Product Name: " . $row["ProductName"] . " - NavID : " . $row["PartNumber"] . " - Qty: " . $Qty19 . "\n";
               }
           }

           if ($_SESSION["Bundle20"] != "")
           {
               for ($c = 0; $c < $arrayCounter; $c++)
               {
                   $DIB20 = $DIB20 + $DivisionIntBI20[$c];
                   $RWB20 = $RWB20 + $RemWtBI20[$c];
                   $EWB20 = $EWB20 + $EntryWeightBI20[$c];
                   $RBB20 = $RBB20 + $RemBalBI20[$c];
                   $IQB20 = $IQB20 + $ItmQtyWtBI20[$c];
               }

               // GMC - 10/03/12 - Display Bundle Item Number, Description and Qty
               $BundleInfo20 = mssql_query("SELECT ProductName, PartNumber FROM tblProducts WHERE RecordId = " . $_SESSION["Bundle20"]);
               while($row = mssql_fetch_array($BundleInfo20))
               {
                   $_SESSION["BundlesInformation"] += "Admin RecordID: " .  $_SESSION["Bundle20"] . " - Product Name: " . $row["ProductName"] . " - NavID : " . $row["PartNumber"] . " - Qty: " . $Qty20 . "\n";
               }
           }

           $NoBoxesComplete =  $DivisionInt1 + $DivisionInt2 + $DivisionInt3 + $DivisionInt4 + $DivisionInt5 + $DivisionInt6 + $DivisionInt7 + $DivisionInt8 + $DivisionInt9 + $DivisionInt10 + $DivisionInt11 + $DivisionInt12 + $DivisionInt13 + $DivisionInt14 + $DivisionInt15 + $DivisionInt16 + $DivisionInt17 + $DivisionInt18 + $DivisionInt19 + $DivisionInt20 + $DIB1 + $DIB2 + $DIB3 + $DIB4 + $DIB5 + $DIB6 + $DIB7 + $DIB8 + $DIB9 + $DIB10 + $DIB11 + $DIB12 + $DIB13 + $DIB14 + $DIB15 + $DIB16 + $DIB17 + $DIB18 + $DIB19 + $DIB20;
           $TotalRemWt =  $RemWt1 + $RemWt2 + $RemWt3 + $RemWt4 + $RemWt5 + $RemWt6 + $RemWt7 + $RemWt8 + $RemWt9 + $RemWt10 + $RemWt11 + $RemWt12 + $RemWt13 + $RemWt14 + $RemWt15 + $RemWt16 + $RemWt17 + $RemWt18 + $RemWt19 + $RemWt20 + $RWB1 + $RWB2 + $RWB3 + $RWB4 + $RWB5 + $RWB6 + $RWB7 + $RWB8 + $RWB9 + $RWB10 + $RWB11 + $RWB12 + $RWB13 + $RWB14 + $RWB15 + $RWB16 + $RWB17 + $RWB18 + $RWB19 + $RWB20;
           $EntryWtComplete =  $EntryWeight1 + $EntryWeight2 + $EntryWeight3 + $EntryWeight4 + $EntryWeight5 + $EntryWeight6 + $EntryWeight7 + $EntryWeight8 + $EntryWeight9 + $EntryWeight10 + $EntryWeight11 + $EntryWeight12 + $EntryWeight13 + $EntryWeight14 + $EntryWeight15 + $EntryWeight16 + $EntryWeight17 + $EntryWeight18 + $EntryWeight19 + $EntryWeight20 + $EWB1 + $EWB2 + $EWB3 + $EWB4 + $EWB5 + $EWB6 + $EWB7 + $EWB8 + $EWB9 + $EWB10 + $EWB11 + $EWB12 + $EWB13 + $EWB14 + $EWB15 + $EWB16 + $EWB17 + $EWB18 + $EWB19 + $EWB20;
           $TotalRemBalBox = $RemBal1 +  $RemBal2 + $RemBal3 + $RemBal4 + $RemBal5 + $RemBal6 + $RemBal7 + $RemBal8 + $RemBal9 + $RemBal10 + $RemBal11 + $RemBal12 + $RemBal13 + $RemBal14 + $RemBal15 + $RemBal16 + $RemBal17 + $RemBal18 + $RemBal19 + $RemBal20 + $RBB1 + $RBB2 + $RBB3 + $RBB4 + $RBB5 + $RBB6 + $RBB7 + $RBB8 + $RBB9 + $RBB10 + $RBB11 + $RBB12 + $RBB13 + $RBB14 + $RBB15 + $RBB16 + $RBB17 + $RBB18 + $RBB19 + $RBB20;
           $TotalITMWT =  $ItmQtyWt1 + $ItmQtyWt2 + $ItmQtyWt3 + $ItmQtyWt4 + $ItmQtyWt5 + $ItmQtyWt6 + $ItmQtyWt7 + $ItmQtyWt8 + $ItmQtyWt9 + $ItmQtyWt10 + $ItmQtyWt11 + $ItmQtyWt12 + $ItmQtyWt13 + $ItmQtyWt14 + $ItmQtyWt15 + $ItmQtyWt16 + $ItmQtyWt17 + $ItmQtyWt18 + $ItmQtyWt19 + $ItmQtyWt20 + $IQB1 + $IQB2 + $IQB3 + $IQB4 + $IQB5 + $IQB6 + $IQB7 + $IQB8 + $IQB9 + $IQB10 + $IQB11 + $IQB12 + $IQB13 + $IQB14 + $IQB15 + $IQB16 + $IQB17 + $IQB18 + $IQB19 + $IQB20;
           $TotalOrderWT = $TotalRemWt + $TotalITMWT;

           if($TotalRemBalBox > 0)
           {
		       if(($TotalRemBalBox > 0) && ($TotalRemBalBox < 145))
               {
                   $TotalBoxCount = $NoBoxesComplete + 1;

                   $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $TotalRemWt,
                      'Units' => 'LB'),
                      'Dimensions' => array('Length' => 12,
                      'Width' => 11,
                      'Height' => 9,
                      'Units' => 'IN'));
               }
               else if(($TotalRemBalBox > 144) && ($TotalRemBalBox < 289))
               {
                   $TotalBoxCount = $NoBoxesComplete + 2;

                   $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $TotalRemWt/2,
                      'Units' => 'LB'),
                      'Dimensions' => array('Length' => 12,
                      'Width' => 11,
                      'Height' => 9,
                      'Units' => 'IN'));

                   $counterBoxes++;

                   $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $TotalRemWt/2,
                      'Units' => 'LB'),
                      'Dimensions' => array('Length' => 12,
                      'Width' => 11,
                      'Height' => 9,
                      'Units' => 'IN'));
               }
               else if($TotalRemBalBox > 288)
               {
                   $TotalBoxCount = $NoBoxesComplete + 3;

                   $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $TotalRemWt/3,
                      'Units' => 'LB'),
                      'Dimensions' => array('Length' => 12,
                      'Width' => 11,
                      'Height' => 9,
                      'Units' => 'IN'));

                   $counterBoxes++;

                   $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $TotalRemWt/3,
                      'Units' => 'LB'),
                      'Dimensions' => array('Length' => 12,
                      'Width' => 11,
                      'Height' => 9,
                      'Units' => 'IN'));

                   $counterBoxes++;

                   $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $TotalRemWt/3,
                      'Units' => 'LB'),
                      'Dimensions' => array('Length' => 12,
                      'Width' => 11,
                      'Height' => 9,
                      'Units' => 'IN'));
               }

               /*
               $arrBoxes[1] = array('Weight' => array('Value' => $TotalRemWt,
                      'Units' => 'LB'),
                      'Dimensions' => array('Length' => 12,
                      'Width' => 11,
                      'Height' => 9,
                      'Units' => 'IN'));
               */

               /*
               // $counterBoxes++;
               $arrBoxCount[$counterBoxes] = 1;
               $arrBoxes[$counterBoxes] = array('Weight' => array('Value' => $TotalRemWt,
                      'Units' => 'LB'),
                      'Dimensions' => array('Length' => 12,
                      'Width' => 11,
                      'Height' => 9,
                      'Units' => 'IN'));
               */

           }
           else
           {
               $TotalBoxCount = $NoBoxesComplete;
           }

           echo '<tr>';
           echo '<td>' . $NoBoxesComplete . '</td>';
           echo '<td></td>';
           echo '<td>' . $TotalBoxCount . '</td>';
           echo '<td>' . $TotalITMWT . '</td>';
           echo '<td></td>';
           echo '<td>' . $TotalRemBalBox . '</td>';
           echo '<td>' . $TotalRemWt . '</td>';
           echo '<td>' . $TotalOrderWT . '</td>';
           echo '</tr>';

         ?>

         </table>

         <table width="900" cellpadding="3" cellspacing="0" style="margin:10px;">
         <tr><th width="*" style="text-align:left">Bundle Information if selected</th></tr>
         <tr><td><?php echo $_SESSION["BundlesInformation"]; ?></td></tr>
         </table>

         <table width="900" cellpadding="3" cellspacing="0" style="margin:10px;">
         <tr><th width="*" style="text-align:left">USPS Shipping Options</th></tr>
         </table>

         <table width="900" cellpadding="3" cellspacing="0" style="margin:10px;">

         <?php

         // may need to urlencode xml portion
         $str2 = "http://production.shippingapis.com/ShippingAPI.dll" . "?API=RateV4&XML=<RateV4Request%20USERID=\"";
         $str2 .= "004GEEKT1462" . "\"%20PASSWORD=\"" . "931HN41XW201" . "\"><Package%20ID=\"0\"><Service>";
         $str2 .= "All" . "</Service><ZipOrigination>" . "89074" . "</ZipOrigination>";
         $str2 .= "<ZipDestination>" . $_POST["Zip"] . "</ZipDestination>";
         $str2 .= "<Pounds>" . floor($TotalOrderWT) . "</Pounds><Ounces>" . ceil(($TotalOrderWT - floor($TotalOrderWT)) * 16) . "</Ounces>";
         $str2 .= "<Container>VARIABLE</Container><Size>REGULAR</Size>";
         $str2 .= "<Machinable>true</Machinable></Package></RateV4Request>";
         $ch2 = curl_init();

         // set URL and other appropriate options
         curl_setopt($ch2, CURLOPT_URL, $str2);
         curl_setopt($ch2, CURLOPT_HEADER, 0);
         curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);

         // grab URL and pass it to the browser
         $data2 = curl_exec($ch2);

         // close curl resource, and free up system resources
         curl_close($ch2);

         $xmlParser2 = new xmlparser();
         $array2 = $xmlParser2->GetXMLTree($data2);

         // $xmlParser2->printa($array2);

                    foreach ($array2['RATEV4RESPONSE'][0]['PACKAGE'][0]['POSTAGE'] as $value2)
                    {
                        if ($value2['MAILSERVICE'][0]['VALUE'] == 'Priority Mail&lt;sup&gt;&amp;reg;&lt;/sup&gt;')
                        {
                            $ResultCode1 = $value2['RATE'][0]['VALUE'];
                            echo '<tr><td>Priority Mail ' . ' ($' . number_format($ResultCode1, 2, '.', '') . ')</td></tr>';
                        }

                        if ($value2['MAILSERVICE'][0]['VALUE'] == 'Express Mail&lt;sup&gt;&amp;reg;&lt;/sup&gt;')
                        {
                            $ResultCode3 = $value2['RATE'][0]['VALUE'];
                            echo '<tr><td>Express Mail ' . ' ($' . number_format($ResultCode3, 2, '.', '') . ')</td></tr>';
                        }

                        // GMC - 05/31/13 - Remove USPS Standard Ground and Post
                        /*
                        if ($value2['MAILSERVICE'][0]['VALUE'] == 'Standard Post&lt;sup&gt;&amp;reg;&lt;/sup&gt;')
                        {
                            $ResultCode5 = $value2['RATE'][0]['VALUE'];
                            echo '<tr><td>Standard Post' . ' ($' . number_format($ResultCode5, 2, '.', '') . ')</td></tr>';
                        }
                        */

                        if ($value2['MAILSERVICE'][0]['VALUE'] == 'First-Class Mail&lt;sup&gt;&amp;reg;&lt;/sup&gt; Parcel')
                        {
                            $ResultCode6 = $value2['RATE'][0]['VALUE'];
                            echo '<tr><td>First Class' . ' ($' . number_format($ResultCode6, 2, '.', '') . ')</td></tr>';
                        }
                        
                        if ($value2['MAILSERVICE'][0]['VALUE'] == 'Priority Mail Express 2-Day&lt;sup&gt;&amp;reg;&lt;/sup&gt;')
                        {
                            $ResultCode7 = $value2['RATE'][0]['VALUE'];
                            echo '<tr><td>Priority Mail Express 2-Day' . ' ($' . number_format($ResultCode7, 2, '.', '') . ')</td></tr>';
                        }

                        if ($value2['MAILSERVICE'][0]['VALUE'] == 'Priority Mail 2-Day&lt;sup&gt;&amp;reg;&lt;/sup&gt;')
                        {
                            $ResultCode8 = $value2['RATE'][0]['VALUE'];
                            echo '<tr><td>Priority Mail 2-Day' . ' ($' . number_format($ResultCode8, 2, '.', '') . ')</td></tr>';
                        }
                    }
         ?>

         </table>

         <table width="900" cellpadding="3" cellspacing="0" style="margin:10px;">
         <tr><th width="*" style="text-align:left"><a href="boxes_menu.php">Go Back to Boxes Menu</a></th></tr>
         </table>

		</div>

	</div>

</body>

</html>
