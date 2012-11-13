<html>
<head>
<meta name="viewport" content="initial-scale=2">
<script type="text/javascript">
function validate_required(field,alerttxt){
with (field){
  if (value==null||value==""){
    alert(alerttxt);return false;
    }else{
    return true;}
  }
}

function validate_form(thisform){
with (thisform){
  if (validate_required(Title,"Title must be filled out!")==false)
  {form.Title.focus();return false;}
  }
}
</script>
</head>

<body>
<?php
  $db = parse_ini_file("/etc/Lucien.conf");

  # Test the Database Connection
  $dbh = new PDO("mysql: host=".$db['host']."; dbname=".$db['db'],$db['un'], $db['pw']);
  $Select_Handle = mysql_connect($db['host'],$db['un'],$db['pw']);
  mysql_select_db($db['db'],$Select_Handle) or die( "Unable to select database");
	
  if($db['db'] == 'Lucien_Test')
    {
      echo "Test Database<br><br>";
    }

	# Setting Variables
	if(isset($_GET['Title'])){ $Title = trim($_GET['Title']);}
	if(isset($_GET['Format'])){ $Format = trim($_GET['Format']);}
	if(isset($_GET['Series'])){ $Series = trim($_GET['Series']);}
	if(isset($_GET['Category'])){ $Category = trim($_GET['Category']);}
	if(isset($_GET['Barcode'])){ $Barcode = trim($_GET['Barcode']);}
	if(isset($_GET['ISBN'])){ $ISBN = trim($_GET['ISBN']);}
	if(isset($_GET['Mode'])){ $Mode = trim($_GET['Mode']);}
	if(isset($_GET['ID'])){ $ID = trim($_GET['ID']);}
	$Codes_Validated = 0;
	$Title_Validated = 0;

  $Format_Query = "select * from Format order by ID ASC";
  $Format_Results = mysql_query($Format_Query);
  $Format_Count = mysql_num_rows($Format_Results);
  for($i=1;$i<=$Format_Count;$i++)
    {
      $Format_Array[$i] = mysql_fetch_row($Format_Results);
      //print_r($Format_Array[$i]);
    }

  $Series_Query = "select * from Series order by ID ASC";
  $Series_Results = mysql_query($Series_Query);
  $Series_Count = mysql_num_rows($Series_Results);
  for($i=1;$i<=$Series_Count;$i++)
    {
      $Series_Array[$i] = mysql_fetch_row($Series_Results);
      //print_r($Series_Array[$i]);
    }

  $Category_Query = "select * from Category order by ID ASC";
  $Category_Results = mysql_query($Category_Query);
  $Category_Count = mysql_num_rows($Category_Results);
  for($i=1;$i<=$Category_Count;$i++)
    {
      $Category_Array[$i] = mysql_fetch_row($Category_Results);
      //print_r($Category_Array[$i]);
    }

	# Get the Format Data
	$FormatQuery = "select ID, Format from Format";
	$FormatResult = MySQL_Query($FormatQuery);
	$FormatCount = MySQL_Num_Rows($FormatResult);
	if(!mysql_data_seek($FormatResult,0)){echo "Could not reset Pointer!";};
	#	var_dump(MySQL_Fetch_Array($FormatResult));
	if(!mysql_data_seek($FormatResult,0)){echo "Could not reset Pointer!";};
	#	echo "FormatCount = ".$FormatCount;
	
	# Get the Series Data
	$SeriesQuery = "select ID, Series from Series";
	$SeriesResult = MySQL_Query($SeriesQuery);
	$SeriesCount = MySQL_Num_Rows($SeriesResult);
	if(!mysql_data_seek($SeriesResult,0)){echo "Could not reset Pointer!";};
	#	var_dump(MySQL_Fetch_Array($SeriesResult));
	if(!mysql_data_seek($SeriesResult,0)){echo "Could not reset Pointer!";};
	#	echo "SeriesCount = ".$SeriesCount;

	# Get the Category Data
	$CategoryQuery = "select ID, Category from Category";
	$CategoryResult = MySQL_Query($CategoryQuery);
	$CategoryCount = MySQL_Num_Rows($CategoryResult);
	if(!mysql_data_seek($CategoryResult,0)){echo "Could not reset Pointer!";};
	#	var_dump(MySQL_Fetch_Array($CategoryResult));
	if(!mysql_data_seek($CategoryResult,0)){echo "Could not reset Pointer!";};
	#	echo "CategoryCount = ".$CategoryCount;

    ?>
	<form action="upload.php" onsubmit="return validate_form(this)" method="GET">
		Title:      <input type="text" name="Title" <?if(isset($Title)){echo " value='$Title'";};?>><br><br>
    Format:     
		<select name="Format" value="options">
			<?for ($i = 1; $i <= $FormatCount; $i++)
				{
					$FormatValue = MySQL_Fetch_Row($FormatResult);
					if($Format == $FormatValue[1])
						{
							echo "<option selected value='".$FormatValue[0]."'>".$FormatValue[1]."</option>";
						}else{
							echo "<option value='".$FormatValue[0]."'>".$FormatValue[1]."</option>";
						}
				}?>
    </select>
		<br><br>
    Series:     
      <select name="Series">
        <?php for($Series_i = 1;$Series_i <= $Series_Count;$Series_i++)
          {
						$SeriesValue = MySQL_Fetch_Row($SeriesResult);
						if($Series == $SeriesValue[1])
							{
   		          echo "<option selected value='".$SeriesValue[0]."'>".$SeriesValue[1]."</option>";
							}else{
   		          echo "<option value='".$SeriesValue[0]."'>".$SeriesValue[1]."</option>";
							}
          }?>
      </select>
		<br><br>
    Category:
      <select name="Category">
        <?php for($Category_i = 1;$Category_i <= $Category_Count;$Category_i++)
          {
						$CategoryValue = MySQL_Fetch_Row($CategoryResult);
						if($Category == $CategoryValue[1])
							{
	              echo "<option selected value='".$CategoryValue[0]."'>".$CategoryValue[1]."</option>";
							}else{
	              echo "<option value='".$CategoryValue[0]."'>".$CategoryValue[1]."</option>";
							}
          }?>
      </select>
		<br><br>
		Barcode:	<input type='text' name='Barcode' value='<?echo $Barcode;?>'>
			   <br><br>
		ISBN:  <input type="text" name="ISBN"<?if(isset($ISBN)){echo " value='$ISBN'";};?><br><br>
				<input type="hidden" Name="Mode" value="add">		
				<br>
	<input type="submit" value="Add to Database">
	</form>
<br>
<br>

<?if($Mode == 'add')
{
	#Check to make sure our Database is consitent
	$MaxCodes = "SELECT Max(ID) FROM `Codes` limit 1";
	$MaxTitle = "SELECT Max(ID) FROM `Title` limit 1";
	$CodesResult = MySQL_Query($MaxCodes);
	$CodesRow = MySQL_Fetch_Row($CodesResult);
	$CodesLineNumber = $CodesRow[0];
	$TitleResult = MySQL_Query($MaxTitle);
	$TitleRow = MySQL_Fetch_Row($TitleResult);
	$TitleLineNumber = $TitleRow[0];

	if($TitleLineNumber != $CodesLineNumber || !isset($TitleLineNumber) || !isset($CodesLineNumber) || $CodesLineNumber == '' || $TitleLineNumber == '' )
	{
		Echo "Table Codes has ".$CodesLineNumber." lines and Table Title has ".$TitleLineNumber." lines! Something went wrong!<br>"; 
		die();
	}else{
			#$Title_Query = "INSERT INTO `Title` (`Title`) VALUES ('".$Title."');";
			$Title_Query = "INSERT INTO `Title` (`Title`) VALUES (:Title);";
			$PD_Handle_Title = $dbh->prepare($Title_Query);
      $PD_Handle_Title->bindParam(":Title",$Title);
/* Removing, as I don't need to worry if $Barcode is blank or not. I should be able to enter NULL
			if($Barcode == "")
			{
			    #$Codes_Query = "INSERT INTO `Codes` (`Barcode`,`ISBN`,`Format`) VALUES (NULL,'".$ISBN."','".$Format."');";	
			    $Codes_Query = "INSERT INTO `Codes` (`Barcode`,`ISBN`,`Format`) VALUES (NULL,:ISBN,:Format);";	
					$PD_Handle = $dbh->prepare($Codes_Query);
    		  $qresults->bindParam(":ISBN",$ISBN);
    		  $qresults->bindParam(":Format",$Format);
					$PD_Handle->execute();
			} else {
*/
			$Codes_Query = "INSERT INTO `Codes` (`Barcode`,`ISBN`,`Format`,`Series`,`Category`) VALUES (:Barcode,:ISBN,:Format,:Series,:Category);";
			$PD_Handle_Codes = $dbh->prepare($Codes_Query);
      $PD_Handle_Codes->bindParam(":Barcode",$Barcode);
      $PD_Handle_Codes->bindParam(":ISBN",$ISBN);
      $PD_Handle_Codes->bindParam(":Format",$Format);
      $PD_Handle_Codes->bindParam(":Series",$Series);
      $PD_Handle_Codes->bindParam(":Category",$Category);
			#}

	#echo "Format = ".$Format;
	#echo "Codes_Query = ".$Codes_Query;
	#echo "<br>";
	#echo "Title_Query = ".$Title_Query;
	#			mysql_query(begin);
				# Testing Transactions
			try
			{
			#	MySQL_Query('SET autocommit=0');
				#MySQL_Query('start transaction');
				#$Title_Insert = MySQL_Query($Title_Query);
				#$Codes_Insert = MySQL_Query($Codes_Query);
				$dbh->beginTransaction();
				$Title_Insert = $PD_Handle_Codes->execute();
				$Codes_Insert = $PD_Handle_Title->execute();

					if($Title_Insert and $Codes_Insert)
					{
						#MySQL_Query('commit');
						$dbh->commit();
					}else{
						#MySQL_Query('rollback');
						$dbh->rollBack();
					}
			}
			catch (Exception $e)
			{
				#MySQL_Query('rollback');
				$dbh->rollBack();
			}
	#echo $Title_Query."<br>";
	#echo $Codes_Query."<br>";	
				# Validate that our input worked
				$TitleLineNumber = $TitleLineNumber + 1;
				$CodesLineNumber = $CodesLineNumber + 1;
				$Title_Validation_Query = "select `Title` from `Title` where `id` = '".$TitleLineNumber."' limit 1;";
				$Codes_Validation_Query = "select `Barcode` from `Codes` where `id` = '".$CodesLineNumber."' limit 1;";
				$Title_Validation_Result = MySQL_Query($Title_Validation_Query);
				$Codes_Validation_Result = MySQL_Query($Codes_Validation_Query);
				$Title_Fetch = MySQL_Fetch_Row($Title_Validation_Result);
				$Codes_Fetch = MySQL_Fetch_Row($Codes_Validation_Result);
				$Title_Validation_Data = $Title_Fetch[0];
				$Codes_Validation_Data = $Codes_Fetch[0];
				# Finally we get to Validate...
				if($Title_Validation_Data ==  $Title)
					{
						$Title_Validated = 0;
					}else{
						$Title_Validated = 1;
					}
				if($Codes_Validation_Data == $Barcode)
					{
						$Codes_Validated = 0;
					}else{
						$Codes_Validated = 1;
					}
				
				if($Title_Validated == 1 || $Codes_Validated == 1)
					{
						echo "<html><h1>There was an error updating</h1></html>";
						# Attempt to fix Auto-Increment
						$Auto_Increment_Fix_Title = "ALTER TABLE Title AUTO_INCREMENT = ".$TitleLineNumber;
						$Auto_Increment_Fix_Codes = "ALTER TABLE Codes AUTO_INCREMENT = ".$CodesLineNumber;
	
						#echo "<br><br>Fixing Title with ".$Auto_Increment_Fix_Title."<br><br>";
						if(!$Title_Fix = MySQL_Query($Auto_Increment_Fix_Title)) 
							{
								#echo "Could not fix Title!<br><br>";
								$Codes_Fixed = 1;
							}else{
								#echo "<br><br>Title Fixed!<Br><br>";
								$Title_Fixed = 0;
							}
						#echo "<br><br>Fixing Codes with ".$Auto_Increment_Fix_Codes."<br><br>";
						if(!$Codes_Fix = MySQL_Query($Auto_Increment_Fix_Codes)) 
							{
								#echo "Could not fix Codes!<br><br>";
								$Codes_Fixed = 1;
							}else{
								#echo "<br><br>Codes Fixed!<br><br>";
								$Codes_Fixed = 0;
							}
						if(!$Codes_Fixed == 0 || !$Title_Fixed == 0)
							{
								echo "<br><br>Database Error, Please tell the Administrator!<br><br>";
							}else{
								echo "<br><br>Please Try Again<br><br>";
							}
					}else{
					echo "<html><h1>Added to Database</h1></html>";}
					
				MySQL_Close();
			}
		}
?>

<?
if($Mode == 'Update')
	{
		if(isset($Title) && (!$Title == ''))
			{
					#echo "<br>Updating Title<br>";
				$Title_Update_Query = "UPDATE `Title` SET `Title` = :Title WHERE `Title`.`ID` = ".$ID." LIMIT 1 ;";
				$Title_Update_Results = $dbh->prepare($Title_Update_Query);
				$Title_Update_Results->bindParam(":Title",$Title);
				$Title_Update_Results->execute();
					#print_r($Title_Update_Results->errorInfo());
			}

		if(isset($Format) && (!$Format == ''))
			{
					#echo "<br>Updating Format<br>";
				$Format_Update_Query = "UPDATE `Codes` SET `Format` = :Format WHERE Codes.ID = ".$ID." LIMIT 1 ;";
				$Format_Update_Results = $dbh->prepare($Format_Update_Query);
				$Format_Update_Results->bindParam(":Format",$Format);
				$Format_Update_Results->execute();
					#print_r($Format_Update_Results->errorInfo());
			}

		if(isset($Series) && (!$Series == ''))
			{
					#echo "<br>Updating Series<br>";
				$Series_Update_Query = "UPDATE Codes SET Series = :Series WHERE Codes.ID = ".$ID." LIMIT 1;";
				$Series_Update_Results = $dbh->prepare($Series_Update_Query);
				$Series_Update_Results->bindParam(":Series",$Series);
				$Series_Update_Results->execute();
					#print_r($Series_Update_Results->errorInfo());
			}

		if(isset($Category) && (!$Category == ''))
			{
					#echo "<br>Updating Category<br>";
				$Category_Update_Query = "UPDATE `Codes` SET  `Category` =  :Category WHERE  Codes.ID = ".$ID." LIMIT 1 ;";
				$Category_Update_Results = $dbh->prepare($Category_Update_Query);
				$Category_Update_Results->bindParam(":Category",$Category);
				$Category_Update_Results->execute();
					#print_r($Category_Update_Results->errorInfo());
			}

		if(isset($Barcode) && (!$Barcode == ''))
			{
					#echo "<br>Updating Barcode<br>";
				$Barcode_Update_Query = "UPDATE `Codes` SET  `Barcode` =  :Barcode WHERE  Codes.ID = ".$ID." LIMIT 1 ;";
				$Barcode_Update_Results = $dbh->prepare($Barcode_Update_Query);
				$Barcode_Update_Results->bindParam(":Barcode",$Barcode);
				$Barcode_Update_Results->execute();
					#print_r($Barcode_Update_Results->errorInfo());
			}

		if(isset($ISBN) && (!$ISBN == ''))
			{
					#echo "<br>Updating ISBN<br>";
				$ISBN_Update_Query = "UPDATE `Codes` SET  `ISBN` = :ISBN WHERE Codes.ID = ".$ID." LIMIT 1 ;";
				$ISBN_Update_Results = $dbh->prepare($ISBN_Update_Query);
				$ISBN_Update_Results->bindParam(":ISBN",$ISBN);
				$ISBN_Update_Results->execute();
					#print_r($ISBN_Update_Results->errorInfo());
			}

/*
				echo "Title = ".$Title."<br><br>";
				echo "Format = ".$Format."<br><br>";
				echo "Series = ".$Series."<br><br>";
				echo "Category = ".$Category."<br><br>";
				echo "Barcode = ".$Barcode."<br><br>";
				echo "ISBN = ".$ISBN."<br><br>";
*/
/*

			$Update = MySQL_Query($Title_Update_Query);
			$Update = MySQL_Query($Format_Update_Query);
			$Update = MySQL_Query($Series_Update_Query);
			$Update = MySQL_Query($Category_Update_Query);
			$Update = MySQL_Query($Barcode_Update_Query);
			$Update = MySQL_Query($ISBN_Update_Query);
*/
			echo "<html><h1>Updated!</h1></html>";
					
			MySQL_Close();
	}
?>
<br><br><br>
<a href="index.php">Take Me Home</a>
<br><br><br>
</body>
</html>
