<?php
define('USER', 'root');
define('PWD', 'K@T/QUJ@');

$dsn = 'mysql:host=localhost;dbname=library-sql_project';

$cardholder = '';
$balance = '';
$numberCheckedOut = '';

try {
  $sql = new PDO($dsn, USER, PWD);
} catch (PDOException $e) {
  echo 'Connection failed: ' . $e->getMessage();
  }

if (isset($_POST['checkout']))
  if (!empty($_POST['cardholder']) && !empty($_POST['book']) && !empty($_POST['balance']) && !empty($_POST['due'])) {
    $sql->exec('UPDATE Books SET Cardholder="'.$_POST['cardholder'].'" WHERE BookName="'.$_POST['book'].'"');
    $sql->exec('UPDATE Books SET DueDate="'.date('Y-m-d', strtotime($_POST['due'])).'" WHERE BookName="'.$_POST['book'].'"');
    $sql->exec('UPDATE Cardholders SET balanceowing='.$_POST['balance'].' WHERE cardholdername="'.$_POST['cardholder'].'"');
  }
  else {
    echo '<p>All fields are required.</p>';
  }

if (!isset($_POST['checkout'])) {
  if (!isset($_POST['cardholder'])) {
    $sth = $sql->query('SELECT CardholderName FROM Cardholders ORDER BY cardholdername LIMIT 1');
    $row = $sth->fetch();
    $cardholder = $row[0];
  }
  else
    $cardholder = $_POST['cardholder'];
}
else
    $cardholder = $_POST['cardholder'];

$sth = $sql->query('SELECT COUNT(*) FROM Books WHERE cardholder="'.$cardholder.'"');
$row = $sth->fetch();
$numberCheckedOut = $row[0];

$sth = $sql->query('SELECT balanceowing FROM Cardholders WHERE cardholdername="'.$cardholder.'"');
$row = $sth->fetch();
$balance = $row[0];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Check Out</title>
        <link rel="stylesheet" media="screen" type="text/css" href="design.css" />
    </head>
    <body>
        <h1>Check Out</h1>
        <h3>Fill in below fields and click on 'Check Out'</h3>
        <form name="Checkout" method="post" action="">
            <p>
            	<label for="cardholder">Cardholder:</label><br />
            	<select name="cardholder" id="cardholder" onchange="Checkout.submit()">
                    <?php 
	                    foreach ($sql->query('SELECT cardholdername FROM Cardholders') as $row):
	                ?>
	                <option  value="<?php echo $row['cardholdername']; ?>" 
	                  <?php if ($row['cardholdername'] == $cardholder) echo 'selected="selected"' ?>>
	                    <?php echo $row['cardholdername']; ?>
	                </option>
	                <?php endforeach; ?>
	            </select><br /><br />
	            <label for="book">Book:</label><br />
	            <select name="book" id="book">
	                <?php 
	                    foreach ($sql->query('SELECT bookname FROM Books WHERE cardholder is null') as $row):
	                ?>
	                <option value="<?php echo $row['bookname']; ?>"><?php echo $row['bookname']; ?></option>
	                <?php endforeach; ?>
	            </select><br /><br />
                <p>Number Already Checked Out: <?php echo $numberCheckedOut; ?><br /></p>
	            <label for="balance">Balance Owing ($):</label><br />
	            <input type="text" name="balance" id="balance" value="<?php echo $balance ?>" /><br /><br />
	            <label for="due">Due Date:</label><br />
	            <input type="text" name="due" id="due" value="<?php echo date('M-d-o',time()+14*60*60*24); ?>" /><br /><br />
	            <input type="submit" name="checkout" value="Check Out" />
	            <input type="button" name="return" value="Return" onClick="location.href='index.php'" />
	        </p>
        </form>
    </body>
</html>
  
<?php 
  $sql = null;
?>
