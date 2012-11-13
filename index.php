<html>
	<head>
    <meta name="viewport" content="initial-scale=2">
		<Title>
			Movie List
		</title>
	</head>
	<body>
		<?php

	  	$db = parse_ini_file("/etc/Lucien.conf");
			if($db['db'] == 'Lucien_Test')
				{
					echo "Test Database<br><br>";
				}

	  	# Test the Database Connection
  		mysql_connect($db['host'],$db['un'],$db['pw']);
  		mysql_select_db($db['db']) or die( "Unable to select database");

 			$Format_Query = "select * from Format";
 		 	$Format_Results = mysql_query($Format_Query);
  		$Format_Count = mysql_num_rows($Format_Results);
  		for($i=1;$i<=$Format_Count;$i++)
  			{
    			$Format_Array[$i] = mysql_fetch_row($Format_Results);
      		//print_r($Format_Array[$i]);
  			}

 			$Series_Query = "select * from Series";
  		$Series_Results = mysql_query($Series_Query);
  		$Series_Count = mysql_num_rows($Series_Results);
  		for($i=1;$i<=$Series_Count;$i++)
  			{
    			$Series_Array[$i] = mysql_fetch_row($Series_Results);
      		//print_r($Series_Array[$i]);
  			}

 			$Category_Query = "select * from Category";
  		$Category_Results = mysql_query($Category_Query);
  		$Category_Count = mysql_num_rows($Category_Results);
  		for($i=1;$i<=$Category_Count;$i++)
  			{
    			$Category_Array[$i] = mysql_fetch_row($Category_Results);
      		//print_r($Category_Array[$i]);
  			}
		?>
		<Table border='1' id='Table' name='Table'>
  	<Form action="search.php" method="GET">
			<tr>
			<th>
    	Title:      
			</th>
			<td>
			<input type="text" name="Title">
			</td>
			</tr>
			<tr>
			<th>
			Format: 
			</th>
			<td>
			<select name="Format">
				<option value=''></option>
				<?php for($Format_i = 1;$Format_i <= $Format_Count;$Format_i++)
					{
							echo "<option value='".$Format_Array[$Format_i][1]."'>".$Format_Array[$Format_i][1]."</option>";
					}?>
			</td>
			</tr>
			<tr>
			<th>
			</select>
			Series: 
			</th>
			<td>
			<select name="Series">
				<option value=''></option>
				<?php for($Series_i = 1;$Series_i <= $Series_Count;$Series_i++)
					{
							echo "<option value='".$Series_Array[$Series_i][1]."'>".$Series_Array[$Series_i][1]."</option>";
					}?>
			</select>
			</td>
			</tr>
			<tr>
			<th>
			Category
			</th>
			<td>
			<select name="Category">
				<option value=''></option>
				<?php for($Category_i = 1;$Category_i <= $Category_Count;$Category_i++)
					{
							echo "<option value='".$Category_Array[$Category_i][1]."'>".$Category_Array[$Category_i][1]."</option>";
					}?>
			</select>
			</td>
			</tr>
			<tr>
			<th>
    	Barcode:    
			</th>
			<td>
			<input type="text" name="Barcode">
			</td>
			</tr>
			<tr>
			<th>
    	ISBN:       
			</th>
			<td>
			<input type="text" name="ISBN">
			</td>
			</tr>
			</table>
			<br>
    <input type="submit" value="Search">
		</form>
	</body>
</html>
