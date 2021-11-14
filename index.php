<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Jade Delight</title>
</head>

<body>
    
    
<?php
    $server = "localhost";
    $userid = "admin";
    $pw = "password";
    $db = "menuItems";


    $conn = new mysqli($server, $userid, $pw);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    echo "Connected successfully";


    $conn -> select_db($db);
    
    
    $conn->close();
?>

<script language="javascript">

function makeSelect(name, minRange, maxRange)
{
	var t= "";
	t = "<select id='" + name + "' onchange = 'calc_c()' size='1'>";
	for (j=minRange; j<=maxRange; j++)
	   t += "<option value ='" + j + "'>" + j + "</option>";
	t+= "</select>"; 
	return t;
}
    
</script>

<h1>Jade Delight</h1>
    
<form action = "form.php" method = "post">

<p>First Name: <input type="text"  name='fname' /></p>
<p>Last Name*:  <input type="text"  name='lname' /></p>
<p id = 'street'>Street: <input type="text"  id='streeti' /></p>
<p id = 'city'>City: <input type="text"  id='cityi' /></p>
<p>Phone*: <input type="text"  name='phone' /></p>
<p> 
	<input type="radio"  name="p_or_d" value = "pickup" onclick = "pickup()"/>Pickup  
	<input type="radio"  name='p_or_d' value = "delivery" onclick = "delivery()"/>Delivery
</p>
<table border="0" cellpadding="3">
  <tr>
    <th>Select Item</th>
    <th>Item Name</th>
    <th>Cost Each</th>
    <th>Total Cost</th>
  </tr>
<script language="javascript">

  var s = "";
  for (i=0; i< menuItems.length; i++)
  {
	  s += "<tr><td>";
	  s += makeSelect("quan" + i, 0, 10);
	  s += "</td><td>" + menuItems[i].name + "</td>";
	  s += "<td> $ " + menuItems[i].cost.toFixed(2) + "</td>";
	  s += "<td>$<input type = text id = 'tbx" + i + "'></input></td></tr>";
  }  
  document.writeln(s);
    
</script>

</table>
<p>Subtotal: 
   $<input type="text"  name='subtotal' id="subtotal" />
</p>
<p>Mass tax 6.25%:
  $ <input type="text"  name='tax' id="tax" />
</p>
<p>Total: $ <input type="text"  name='total' id="total" />
</p>

<script language = "javascript">
    calc_c();

    function calc_c() {
      var ba;
      for (n = 0; n < menuItems.length; n++) {
        ba = (document.getElementById("quan" + n).value);
        document.getElementById('tbx' + n).value = (ba * (menuItems[n].cost.toFixed(2)));
      } 
      sum = 0;
      for (i = 0; i < menuItems.length; i++) {
        sum += Number(document.getElementById('tbx' + i).value);
      }
      document.getElementById("subtotal").value = sum;
      
      tax = Number((sum * 0.0625).toFixed(2));
      document.getElementById("tax").value = tax;
        
      total = Number(sum + tax);
      document.getElementById("total").value = total;        
    }
    
    function pickup() {
        document.getElementById("city").style.visibility = "hidden";
        document.getElementById("street").style.visibility = "hidden";
        document.getElementById("cityi").style.visibility = "hidden";
        document.getElementById("streeti").style.visibility = "hidden";
    }
        
    function delivery() {
        document.getElementById("city").style.visibility = "visible";
        document.getElementById("street").style.visibility = "visible";
        document.getElementById("cityi").style.visibility = "visible";
        document.getElementById("streeti").style.visibility = "visible";
        
    }
    
</script>

<input type = "button" value = "Submit Order" onclick = "verify()"/>

<script language = 'javascript'>
   function verify() {
       verifystr = "";
       lastn = document.querySelector('input[name=lname]').value;
       if (lastn == "") {
           verifystr += "Please enter a last name.\n";
       }
       phonen = document.querySelector('input[name=phone]').value;
       var phonev = false;
       for (i = 0; i < phonen.length; i++) {
               if (isNaN(phonen[i])) {
                   
                   if (phonen[i] == '(') {
                       if (i != 0) {
                           phonev = false;
                           break;
                       }
                   }
                   if (phonen[i] == ')'){
                       if (i != 4) {
                           phonev = false;
                           break
                       }
                   }
                   
                   if (phonen[i] == '-') {
                       if ((i != 3) && (i != 7) && (i != 8)) {
                           phonev = false;
                           break;
                       }
                   }
                   
               } else {
                   phonev = true;
               }
        }
       if ((phonen[0] == '(') && (phonen.length != 13)) {
           phonev = false;
       } 
       if ((phonen[3] == '-') && (phonen.length != 12)){
           phonev = false;
       } 
       if (((phonen[3] != '-') && (phonen[0] != '(')) && (phonen.length != 10)) {
           phonev = false;
       }
       
       if (!phonev) {
           verifystr += "Number is not in correct format.\n"
       }
       
       
       
       rslt_str = "Thank you for your order!\n\n";
       for (i = 0; i < menuItems.length; i++) {
           if (document.getElementById("quan" + i).value != 0) {
               rslt_str += ((document.getElementById("quan" + i).value) + "x " +
                   menuItems[i].name + "\n");
           }
       }
       pdval = document.querySelector('input[name="p_or_d"]:checked').value;
       if (pdval == "pickup") {
           rslt_str += "\n15 minutes until order is ready for pickup!"
       } else if (pdval == "delivery") {
           if ((document.getElementById("streeti").value == "") || (document.getElementById("cityi").value == "")) {
               verifystr += "Street and city required for delivery.\n";
           } else {
               rslt_str += "\n30 minutes until order is delivered!";
           }
       } else {
           verifystr += "Must select pickup or delivery.\n";
       }
       
       if (document.getElementById("total").value == 0) {
           verifystr += "At least one item must be ordered.\n";
       }
       
       rslt_str += ("\n$" + document.getElementById("total").value + " is your total today.");
             
       if (verifystr != "") {
           alert(verifystr);
       } else {
           alert(rslt_str);
       }
   }
</script>
    
</form>
</body>
</html>