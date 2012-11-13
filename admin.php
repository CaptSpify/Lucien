<html>
<head>
<Title>
Admin
</title>
</head>
<body>
<?php
  $db = parse_ini_file("/etc/Lucien.conf");
    #echo "host = ".$db['host']."<br>";
    #echo "un = ".$db['un']."<br>";
    #echo "pw = ".$db['pw']."<br>";
    #echo "db = ".$db['db']."<br>";

  # Test the Database Connection
  mysql_connect($db['host'],$db['un'],$db['pw']);
  mysql_select_db($db['db']) or die( "Unable to select database");

  if(isset($_GET['Mode'])){$Mode = $_GET['Mode'];}
  if(isset($_GET['Format'])){$Format = $_GET['Format'];}
  if(isset($_GET['Series'])){$Series = $_GET['Series'];}
  if(isset($_GET['Category'])){$Category = $_GET['Category'];}

	echo "<Table border='1' id='Table' name='Table'>";
	
	if($Mode == '')
		{
			echo "<tr>";
			echo "<th colspan='2' bgcolor='#F0F8FF'>";
			echo "Add Format";
			echo "</th>";
			echo "</tr>";
			echo "<td>";
			echo "<form method='GET' action='admin.php'>";
			echo "Format: <input type='text' name='Format'/><br>";
			echo "<input type='hidden' name='Mode' value='Format'/>";
			echo "</td>";
			echo "<td>";
			echo "<input type='submit' value='Add Format'/></form>";
			echo "</td>";

			echo "<tr>";
			echo "<th colspan='2' bgcolor='#F0F8FF'>";
			echo "Add Series";
			echo "</th>";
			echo "</tr>";
			echo "<td>";
			echo "<form method='GET' action='admin.php'>";
			echo "Series: <input type='text' name='Series'/><br>";
			echo "<input type='hidden' name='Mode' value='Series'/>";
			echo "</td>";
			echo "<td>";
			echo "<input type='submit' value='Add Series'/></form>";
			echo "</td>";

			echo "<tr>";
			echo "<th colspan='2' bgcolor='#F0F8FF'>";
			echo "Add Category";
			echo "</th>";
			echo "</tr>";
			echo "<td>";
			echo "<form method='GET' action='admin.php'>";
			echo "Category: <input type='text' name='Category'/><br>";
			echo "<input type='hidden' name='Mode' value='Category'/>";
			echo "</td>";
			echo "<td>";
			echo "<input type='submit' value='Add Category'/></form>";
			echo "</td>";

		}

	echo "</table>";

	if($Mode == 'Format')
		{
			if($Format <> '')
			{
				$Format_SQL = "select max(ID) from Format";
				$Format_Count = MySQL_Query($Format_SQL);
				$Format_Fetch = MySQL_fetch_row($Format_Count);
				$Format_Max = $Format_Fetch[0] + 1;
				$Format_SQL = "Insert into Format (ID,Format) values (".$Format_Max.",'".$Format."')";
					#echo "Format_Mode_SQL = ".$Format_SQL."<br>";
				MySQL_Query($Format_SQL);
				echo "Added the '".$Format."' Format into Lucien";
			}else{
				die('Insert Error');
			}
		}

	if($Mode == 'Series')
		{
			if($Series <> '')
			{
				$Series_SQL = "select max(ID) from Series";
				$Series_Count = MySQL_Query($Series_SQL);
				$Series_Fetch = MySQL_fetch_row($Series_Count);
				$Series_Max = $Series_Fetch[0] + 1;
				$Series_SQL = "Insert into Series (ID,Series) values (".$Series_Max.",'".$Series."')";
					#echo "Series_Mode_SQL = ".$Series_SQL."<br>";
				MySQL_Query($Series_SQL);
				echo "Added the '".$Series."' Series into Lucien";
			}else{
				die('Insert Error');
			}
		}

	if($Mode == 'Category')
		{
			if($Category <> '')
			{
				$Category_SQL = "select max(ID) from Category";
				$Category_Count = MySQL_Query($Category_SQL);
				$Category_Fetch = MySQL_fetch_row($Category_Count);
				$Category_Max = $Category_Fetch[0] + 1;
				$Category_SQL = "Insert into Category (ID,Category) values (".$Category_Max.",'".$Category."')";
					#echo "Category_Mode_SQL = ".$Category_SQL."<br>";
				MySQL_Query($Category_SQL);
				echo "Added the '".$Category."' Category into Lucien";
			}else{
				die('Insert Error');
			}
		}


?>
</body>
</html>
