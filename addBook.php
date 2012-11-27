<?php
define('USER', 'root');
define('PWD', 'K@T/QUJ@');

$dsn = 'mysql:host=localhost;dbname=library-sql_project';

try {
  $sql = new PDO($dsn, USER, PWD);
} catch (PDOException $e) {
  echo 'Connection failed: ' . $e->getMessage();
}
  

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Add Book</title>
        <link rel="stylesheet" media="screen" type="text/css" href="design.css" />
    </head>
    <body>
        <h1>Add Book</h1>
        <h3>Fill in below fields and click on 'Add Book'</h3>
        <form name="AddBook" method="post" action="">
            <p>
	            <label for="book">Book:</label><br />
            	<input type="text" name="book" id="book" /><br /><br />
            	<label for="branch">Branch:</label><br />
            	<select name="branch" id="branch">
            	    <?php 
                	    foreach ($sql->query('SELECT branchname FROM Branches') as $row):
                	?>
	                <option value="<?php echo $row['branchname']; ?>"><?php echo $row['branchname']; ?></option>
	                <?php endforeach; ?>
	            </select><br /><br />
	            <label for="category">Category:</label><br />
	            <select name="category" id="category">
	                <?php 
	                    foreach ($sql->query('SELECT categoryname FROM Categories') as $row):
	                ?>
	                <option value="<?php echo $row['categoryname']; ?>"><?php echo $row['categoryname']; ?></option>
	                <?php endforeach; ?>
	            </select><br /><br />
                <input type="submit" name="add" value="Add Book" />
	            <input type="button" name="return" value="Return" onClick="location.href='index.php'" />
            </p>
        </form>

        <?php
            if (isset($_POST['add'])) {
              if (!empty($_POST['book']) && !empty($_POST['branch']) && !empty($_POST['category'])) {
                $sth = $sql->query("select count(*) from Books where bookname='".$_POST['book']."' and branch='".$_POST['branch']."'");
                $row = $sth->fetch();
                $copy = $row[0];
                if ($copy == 0) {
                  $sql->exec("insert into Books (BookName, Branch, Category) values('".$_POST['book']."', '".$_POST['branch']."', '".$_POST['category']."')");
                  echo '<p>'.$_POST['book'].' successfully added in the '.$_POST['category'].' catgeory of the '.$_POST['branch'].' branch.</p>';
                }
                else 
                  echo '<p>'.$_POST['book'].' already exists in the '.$_POST['category'].' catgeory of the '.$_POST['branch'].' branch.</p>';
              }
              else
                echo '<p>All fields are required.</p>';
            }

            $sql = null;
        ?>

    </body>
</html>
