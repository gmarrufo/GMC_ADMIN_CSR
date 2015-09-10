<h1>Done with Select Shipping Address</h1>

<?php
     // GMC - 12/03/08 - Domestic Vs. International 3rd Phase
     echo '<table width="900" cellpadding="2" cellspacing="0" style="margin:10px;">';
     echo '<tr>';
     echo '<td>';

     // GMC - Open Media Kit to CSRs Only
     // if ($_SESSION['UserID'] == 35 || $_SESSION['UserID'] == 162 || $_SESSION['UserID'] == 188)
     if ($_SESSION['UserTypeID'] == 2)
     {
          echo '<div id="content">
          <!-- GMC - 03/26/12 - MediaKit Process -->
          <table width=100%>
          <tr>
          <td>
            You have updated the address successfully,
          </td>
          <td>
             <a href="customers.php?Action=NewOrder&CustomerID=' . $_GET['CustomerID'] . '&OrderType=Standard">click here to create a Standard New Order</a>
          </td>
          </tr>

          <!-- GMC - 10/07/13 - Cancel Create Media Kit Order for Now -->
          <!--
          <tr>
          <td>
          &nbsp;
          </td>
          <td>
             <a href="customers.php?Action=NewOrder&CustomerID=' . $_GET['CustomerID'] . '&OrderType=MediaKit">click here to create a Media Kit or a Media Kit Thank You New Order</a>
          </td>
          </tr>
          -->

          </table>
          <!--
          <p>You have updated the address successfully, <a href="customers.php?Action=NewOrder&CustomerID=' . $_GET['CustomerID'] . '">click here to create new order</a></p>
          <p>&nbsp;</p>
           -->
		  </div>';
    }
    
    // GMC - 09/29/14 - UnWash in Admin
    else if($_SESSION['UserTypeID'] == 4)
    {
          echo '<div id="content">

          <table width=100%>
          <tr>
          <td>
            You have updated the address successfully,
          </td>
          <td>
             <a href="customers.php?Action=NewOrder&CustomerID=' . $_GET['CustomerID'] . '&OrderType=Standard">click here to create an UnWash New Order</a>
          </td>
          </tr>
          </table>
		  </div>';
    }

    else
    {
          echo '<div id="content">
          <!-- GMC - 03/26/12 - MediaKit Process -->
          <table width=100%>
          <tr>
          <td>
            You have updated the address successfully,
          </td>
          <td>
             <a href="customers.php?Action=NewOrder&CustomerID=' . $_GET['CustomerID'] . '&OrderType=Standard">click here to create a Standard New Order</a>
          </td>
          </tr>
          </table>
          <!--
          <p>You have updated the address successfully, <a href="customers.php?Action=NewOrder&CustomerID=' . $_GET['CustomerID'] . '">click here to create new order</a></p>
          <p>&nbsp;</p>
           -->
		  </div>';
    }

    echo '</td>';
    echo '</tr>';
    echo '</table>';
?>
