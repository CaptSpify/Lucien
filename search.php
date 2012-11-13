<html>
<head>
<meta Format="viewport" content="initial-scale=1">
<title> Results </title>

<script type="text/javascript">
function validate_required(field,alerttxt)
	{
		with (field)
			{
	  		if (value==null||value=="")
					{
    				alert(alerttxt);
						return false;
    			}else{
    				return true;
					}
  		}	
	}	

function validate_form(thisform)
	{
		with (thisform)	
			{
			  if (validate_required(Title,"Title must be filled out!")==false)
			  	{
						form.Title.focus();
						return false;
					}	
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

	$Ready = 0;

  # Initialize variables
  if(isset($_GET['Title']))
		{
			$Title = $_GET['Title'];
			if($Title <> '')
				{
					$Ready = 1;
				}
		}
  if(isset($_GET['Format']))
		{
			$Format = $_GET['Format'];
			if($Format <> '')
				{
					$Ready = 1;
				}
		}

  if(isset($_GET['Series']))
		{
			$Series = $_GET['Series'];
			if($Series <> '')
				{
					$Ready = 1;
				}
		}

  if(isset($_GET['Category']))
		{
			$Category = $_GET['Category'];
			if($Category <> '')
				{
					$Ready = 1;
				}
		}

  if(isset($_GET['Barcode']))
		{
			$Barcode = $_GET['Barcode'];
			if($Barcode <> '')
				{
					$Ready = 1;
				}
		}

  if(isset($_GET['ISBN']))
		{
			$ISBN = $_GET['ISBN'];
			if($ISBN <> '')
				{
					$Ready = 1;
				}
		}
 
  if(isset($_GET['ID']))
		{
			$ID = $_GET['ID'];
			if($ID <> '')
				{
					$Ready = 1;
				}
		}

	if($Ready == 0)
		{
			die ('No Search Criteria!');
		}

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

	#### Search based on priority
	# First > Barcode
		$query = "select ";
			$query .= "Codes.ID";
			$query .= ",Title.Title";
			$query .= ",Format.Format";
			$query .= ",Codes.ISBN";
			$query .= ",Codes.Barcode";
			$query .= ",Series.Series";
			$query .= ",Category.Category";
		$query .= " from ";
			$query .= "Codes";
			$query .= ",Title";
			$query .= ",Format";
			$query .= ",Series";
			$query .= ",Category";
		$query .= " where ";
			$query .= "Codes.ID = Title.ID";
			$query .= " AND Codes.Format = Format.ID";
			$query .= " AND Codes.Series = Series.ID";
			$query .= " AND Codes.Category = Category.ID";
	if(!$Title == '')
		{
			#echo "Searched by Title";
			$query .= " AND Title.Title like :Title";
			$Title_Param = "%".$Title."%";
		}
	if(!$Format == '')
		{
			#echo "Searched by Format";
			$query .= " AND Format.Format like :Format";
			$Format_Param = "%".$Format."%";
		}
	if(!$Series == '')
		{
			#echo "Searched by Series";
			$query .= " AND Series.Series like :Series";
			$Series_Param = "%".$Series."%";
		}
	if(!$Category == '')
		{
			#echo "Searched by Category";
			$query .= " AND Category.Category like :Category";
			$Category_Param = "%".$Category."%";
		}
	if(!$Barcode == '')
		{	
			#echo "Searched by Barcode";
			$query .= " AND Codes.Barcode = :Barcode";
			$Barcode_Param = $Barcode;
		}
	if(!$ISBN == '')
		{
			#echo "Searched by ISBN";
			$query .= " AND ISBN.ISBN = :ISBN";
			$ISBN_Param = $ISBN;
		}
	if(!$ID == '')
		{
			#echo "Searched by Category";
			$query .= " AND Codes.ID = :ID";
			$ID_Param = "%".$ID."%";
		}

		#echo $query;

	# Assign results to our output variables
	$qresults = $dbh->prepare($query);
	if(!$Title == '')
		{
			$qresults->bindParam(":Title",$Title_Param);
		}
	if(!$Format == '')
		{
			$qresults->bindParam(":Format",$Format_Param);
		}
	if(!$Series == '')
		{
			$qresults->bindParam(":Series",$Series_Param);
		}
	if(!$Category == '')
		{
			$qresults->bindParam(":Category",$Category_Param);
		}
	if(!$Barcode == '')
		{
			$qresults->bindParam(":Barcode",$Barcode_Param);
		}
	if(!$ISBN == '')
		{
			$qresults->bindParam(":ISBN",$ISBN_Param);
		}
	$qresults->execute();
	$resultsCount = $qresults->rowCount();

	for($i=1;$i<=$resultsCount;$i++)
	{
		$row[$i] = $qresults->fetch(PDO::FETCH_BOTH);
		$IDResult[$i] = $row[$i][0];
		$TitleResult[$i] = $row[$i][1];
		$FormatResult[$i] = $row[$i][2];
		$ISBNResult[$i] = $row[$i][3];
		$BarcodeResult[$i] = $row[$i][4];
		$SeriesResult[$i] = $row[$i][5];
		$CategoryResult[$i] = $row[$i][6];
	}

  # Close the Database
  mysql_close();
	
	# Validate that we own it
	if(!$TitleResult == '' ){
		if($resultsCount <= 1)
		{
			#echo "ResultsCount = ".$resultsCount."<br>";
			#echo "Query = ".$query."<br>";
			echo "<h1>Is this what your looking for?</h1><br>";
		}else{
			#echo "ResultsCount = ".$resultsCount."<br>";
			#echo "Query = ".$query."<br>";
			echo "<h1>We might own it. Here are some potential matches</h1><br>";
		}
		$found = 0;
	}else{
		echo "<h1>No! We don't own it!</h1><br><br>";
		echo '<FORM ACTION="upload.php" method="GET">';
		echo '<input type="hidden" value="';
		echo $Title;
		echo '" Name="Title">';
		echo '<input type="hidden" value="';
		echo $Format;
		echo '" Name="Format">';
		echo '<input type="hidden" value="';
		echo $Series;
		echo '" Name="Series">';
		echo '<input type="hidden" value="';
		echo $Category;
		echo '" Name="Category">';
		echo '<input type="hidden" value="';
		echo $Barcode;
		echo '" Name="Barcode">';
		echo '<input type="hidden" value="';
		echo $ISBN;
		echo '" Name="ISBN">';
		echo '<INPUT TYPE="submit" VALUE="Add it!"></FORM>';
		$found = 1;
	}


for($i=1;$i<=$resultsCount;$i++){
	$ResultID = $IDResult[$i];
	$ResultTitle = $TitleResult[$i];
	$ResultFormat = $FormatResult[$i];
	$ResultBarcode = $BarcodeResult[$i];
	$ResultISBN = $ISBNResult[$i];
	$ResultSeries = $SeriesResult[$i];
	$ResultCategory = $CategoryResult[$i];

	$IDWidth = strlen($ResultID);
	$TitleWidth = strlen($ResultTitle);
	$FormatWidth = strlen($ResultFormat);
	$BarcodeWidth = strlen($ResultBarcode);
	$ISBNWidth = strlen($ResultISBN);
	$SeriesWidth = strlen($ResultSeries);
	$CategoryWidth = strlen($ResultCategory);

?>
	<script language="javascript" type="text/javascript">

		function editRecord<?echo $i;?>()
			{
					//alert('editRecord Clicked');
				var buttonplain<?echo $i;?> = document.getElementById("ButtonPlain<?echo $i;?>");
				var buttonplaintext<?echo $i;?> = document.getElementById("plainText<?echo $i;?>");
				var buttonedit<?echo $i;?> = document.getElementById("ButtonEdit<?echo $i;?>");
				var buttonedittext<?echo $i;?> = document.getElementById("editText<?echo $i;?>");

				var titleplain<?echo $i;?> = document.getElementById("TitlePlain<?echo $i;?>");
				var titleplaintext<?echo $i;?> = document.getElementById("plainText<?echo $i;?>");
				var titleedit<?echo $i;?> = document.getElementById("TitleEdit<?echo $i;?>");
				var titleedittext<?echo $i;?> = document.getElementById("editText<?echo $i;?>");

				var formatedit<?echo $i;?> = document.getElementById("FormatEdit<?echo $i;?>");
				var formatedittext<?echo $i;?> = document.getElementById("editText<?echo $i;?>");
				var formatplain<?echo $i;?> = document.getElementById("FormatPlain<?echo $i;?>");
				var formatplaintext<?echo $i;?> = document.getElementById("plainText<?echo $i;?>");

				var seriesedit<?echo $i;?> = document.getElementById("SeriesEdit<?echo $i;?>");
				var seriesedittext<?echo $i;?> = document.getElementById("editText<?echo $i;?>");
				var seriesplain<?echo $i;?> = document.getElementById("SeriesPlain<?echo $i;?>");
				var seriesplaintext<?echo $i;?> = document.getElementById("plainText<?echo $i;?>");

				var categoryedit<?echo $i;?> = document.getElementById("CategoryEdit<?echo $i;?>");
				var categoryedittext<?echo $i;?> = document.getElementById("editText<?echo $i;?>");
				var categoryplain<?echo $i;?> = document.getElementById("CategoryPlain<?echo $i;?>");
				var categoryplaintext<?echo $i;?> = document.getElementById("plainText<?echo $i;?>");

				var barcodeedit<?echo $i;?> = document.getElementById("BarcodeEdit<?echo $i;?>");
				var barcodeedittext<?echo $i;?> = document.getElementById("editText<?echo $i;?>");
				var barcodeplain<?echo $i;?> = document.getElementById("BarcodePlain<?echo $i;?>");
				var barcodeplaintext<?echo $i;?> = document.getElementById("plainText<?echo $i;?>");

				var isbnedit<?echo $i;?> = document.getElementById("ISBNEdit<?echo $i;?>");
				var isbnedittext<?echo $i;?> = document.getElementById("editText<?echo $i;?>");
				var isbnplain<?echo $i;?> = document.getElementById("ISBNPlain<?echo $i;?>");
				var isbnplaintext<?echo $i;?> = document.getElementById("plainText<?echo $i;?>");
		
				if(buttonedit<?echo $i;?>.style.display == "inline") 
				{
							//alert('edit Called');
			    	buttonedit<?echo $i;?>.style.display<?echo $i;?> = "none";
						//edittext.innerHTML<?echo $i;?> = "show";
			  	}else{
						buttonedit<?echo $i;?>.style.display = "inline";
						//edittext.innerHTML = "hide";
				}

				if(buttonplain<?echo $i;?>.style.display == "inline") 
				{
							//alert('Plain Called');
			    	buttonplain<?echo $i;?>.style.display = "none";
						//plaintext.innerHTML = "show";
			  	}else{
							//alert('Plain Called');
						buttonplain<?echo $i;?>.style.display = "inline";
						//plaintext.innerHTML = "hide";
				}

				if(titleedit<?echo $i;?>.style.display == "inline") 
				{
							//alert('edit Called');
			    	titleedit<?echo $i;?>.style.display = "none";
						//edittext.innerHTML = "show";
			  	}else{
						titleedit<?echo $i;?>.style.display = "inline";
						//edittext.innerHTML = "hide";
				}
		
				if(titleplain<?echo $i;?>.style.display == "inline") 
				{
							//alert('Plain Called');
			    	titleplain<?echo $i;?>.style.display = "none";
						//plaintext.innerHTML = "show";
			  	}else{
							//alert('Plain Called');
						titleplain<?echo $i;?>.style.display = "inline";
						//plaintext.innerHTML = "hide";
				}
		
				if(formatedit<?echo $i;?>.style.display == "inline") 
				{
							//alert('edit Called');
			    	formatedit<?echo $i;?>.style.display = "none";
						//edittext.innerHTML = "show";
			  	}else{
						formatedit<?echo $i;?>.style.display = "inline";
						//edittext.innerHTML = "hide";
				}
		
				if(formatplain<?echo $i;?>.style.display == "inline") 
				{
							//alert('Plain Called');
			    	formatplain<?echo $i;?>.style.display = "none";
						//plaintext.innerHTML = "show";
			  	}else{
							//alert('Plain Called');
						formatplain<?echo $i;?>.style.display = "inline";
						//plaintext.innerHTML = "hide";
				}
		
				if(seriesedit<?echo $i;?>.style.display == "inline") 
				{
							//alert('edit Called');
			    	seriesedit<?echo $i;?>.style.display = "none";
						//edittext.innerHTML = "show";
			  	}else{
						seriesedit<?echo $i;?>.style.display = "inline";
						//edittext.innerHTML = "hide";
				}
		
				if(seriesplain<?echo $i;?>.style.display == "inline") 
				{
							//alert('Plain Called');
			    	seriesplain<?echo $i;?>.style.display = "none";
						//plaintext.innerHTML = "show";
			  	}else{
							//alert('Plain Called');
						seriesplain<?echo $i;?>.style.display = "inline";
						//plaintext.innerHTML = "hide";
				}
		
				if(categoryedit<?echo $i;?>.style.display == "inline") 
				{
							//alert('edit Called');
			    	categoryedit<?echo $i;?>.style.display = "none";
						//edittext.innerHTML = "show";
			  	}else{
						categoryedit<?echo $i;?>.style.display = "inline";
						//edittext.innerHTML = "hide";
				}
		
				if(categoryplain<?echo $i;?>.style.display == "inline") 
				{
							//alert('Plain Called');
			    	categoryplain<?echo $i;?>.style.display = "none";
						//plaintext.innerHTML = "show";
			  	}else{
							//alert('Plain Called');
						categoryplain<?echo $i;?>.style.display = "inline";
						//plaintext.innerHTML = "hide";
				}
		
				if(barcodeedit<?echo $i;?>.style.display == "inline") 
				{
							//alert('edit Called');
			    	barcodeedit<?echo $i;?>.style.display = "none";
						//edittext.innerHTML = "show";
			  	}else{
						barcodeedit<?echo $i;?>.style.display = "inline";
						//edittext.innerHTML = "hide";
				}
		
				if(barcodeplain<?echo $i;?>.style.display == "inline") 
				{
							//alert('Plain Called');
			    	barcodeplain<?echo $i;?>.style.display = "none";
						//plaintext.innerHTML = "show";
			  	}else{
							//alert('Plain Called');
						barcodeplain<?echo $i;?>.style.display = "inline";
						//plaintext.innerHTML = "hide";
				}
		
				if(isbnedit<?echo $i;?>.style.display == "inline") 
				{
							//alert('edit Called');
			    	isbnedit<?echo $i;?>.style.display = "none";
						//edittext.innerHTML = "show";
			  	}else{
						isbnedit<?echo $i;?>.style.display = "inline";
						//edittext.innerHTML = "hide";
				}
		
				if(isbnplain<?echo $i;?>.style.display == "inline") 
				{
							//alert('Plain Called');
			    	isbnplain<?echo $i;?>.style.display = "none";
						//plaintext.innerHTML = "show";
			  	}else{
							//alert('Plain Called');
						isbnplain<?echo $i;?>.style.display = "inline";
						//plaintext.innerHTML = "hide";
				}
			}

		function updateRecord<?echo $i;?>(width)
			{
				editRecord<?echo $i;?>();
				validate_form(this);
			}
	</script>
<Form Name="Update<?echo $i;?>" action="upload.php" method="get" id=Form<?echo $i;?> name=Form<?echo $i;?>>
<Table border="1" id=Table"<?echo $i;?> name=Table"<?echo $i;?>>
	<tr>
		<tr>
			<th>
				Title 
			</th>
			<td>
				<div id="TitlePlain<?echo $i;?>" style="display: inline">
					<? echo trim($ResultTitle);?>
				</div>
				<div id="TitleEdit<?echo $i;?>" style="display: none">
					<textarea Name="Title" rows=1 cols=<? echo $TitleWidth;?> id=TextArea<?echo $i;?>>
						<? echo trim($ResultTitle);?>
					</textarea>
				</div>
			</td>
			<td>
				<a href="http://www.imdb.com/find?s=tt&q=<? echo $ResultTitle;?>">Search IMDB By Title</a>
			</td>
		</tr>
		<tr>
			<th>
				Format
			</th>
			<td>
				<div id="FormatPlain<?echo $i;?>" style="display: inline">
					<? echo $ResultFormat;?>
				</div>
				<div id="FormatEdit<?echo $i;?>" style="display: none">
					<select Name=Format>
						<?php for($Format_i = 1;$Format_i <= $Format_Count;$Format_i++)
							{
								if($Format_Array[$Format_i][1] == $ResultFormat)
								{
									echo "<option selected value='".$Format_i."'>".$Format_Array[$Format_i][1]."</option>";
								}else{
									echo "<option value='".$Format_i."'>".$Format_Array[$Format_i][1]."</option>";
								}
							}?>
					</select>
				</div>
			</td>
		</tr>
		<tr>
			<th>
				Series
			</th>
			<td>
				<div id="SeriesPlain<?echo $i;?>" style="display: inline">
					<? echo $ResultSeries;?>
				</div>
				<div id="SeriesEdit<?echo $i;?>" style="display: none">
					<select Name=Series>
						<?php for($Series_i = 1;$Series_i <= $Series_Count;$Series_i++)
							{
								if($Series_Array[$Series_i][1] == $ResultSeries)
								{
									echo "<option selected value='".$Series_i."'>".$Series_Array[$Series_i][1]."</option>";
								}else{
									echo "<option value='".$Series_i."'>".$Series_Array[$Series_i][1]."</option>";
								}
							}?>
					</select>
				</div>
			</td>
			<? if($ResultSeries <> 'None')
				{			
					echo "<td>";
					echo "	<a href='search.php?Series=".$ResultSeries."'>List all of the ".$ResultSeries." Series</a>";
					echo "</td>";
				}?>
		</tr>
		<tr>
			<th>
				Category
			</th>
			<td>
				<div id="CategoryPlain<?echo $i;?>" style="display: inline">
					<? echo $ResultCategory;?>
				</div>
				<div id="CategoryEdit<?echo $i;?>" style="display: none">
					<select Name=Category>
						<?php for($Category_i = 1;$Category_i <= $Category_Count;$Category_i++)
							{
								if($Category_Array[$Category_i][1] == $ResultCategory)
								{
									echo "<option selected value='".$Category_i."'>".$Category_Array[$Category_i][1]."</option>";
								}else{
									echo "<option value='".$Category_i."'>".$Category_Array[$Category_i][1]."</option>";
								}
							}?>
					</select>
				</div>
			</td>
			<? if($ResultCategory <> 'None')
				{			
					echo "<td>";
					echo "	<a href='search.php?Category=".$ResultCategory."'>List everything in the ".$ResultCategory." Category</a>";
					echo "</td>";
				}?>
		</tr>
		<tr>
			<th>
				Barcode 
			</th>
        <td>
					<div id="BarcodePlain<?echo $i;?>" style="display: inline">
						<? echo $ResultBarcode;?>
					</div>
					<div id="BarcodeEdit<?echo $i;?>" style="display: none">
						<textarea Name="Barcode" rows=1 cols=<? echo $BarcodeWidth;?>>
							<? echo $ResultBarcode;?>
						</textarea>
					</div>
            </td>
			<td>
				<a href="http://www.amazon.com/s/ref=nb_sb_noss?url=search-alias%3Daps&field-keywords=<?echo $ResultBarcode;?>&x=0&y=0">Search Amazon by Barcode</a>
			</td>
		</tr>
		<tr>
			<th>
				ISBN 
			</th>
        <td>
					<div id="ISBNPlain<?echo $i;?>" style="display: inline">
						<? echo $ResultISBN;?>
					</div>
					<div id="ISBNEdit<?echo $i;?>" style="display: none">
						<textarea Name="ISBN" rows=1 cols=<? echo $ISBNWidth;?>>
							<? echo $ResultISBN;?>
						</textarea>
					</div>
        </td>
			<td>
				<a href="http://www.amazon.com/gp/search/ref=sr_adv_b/?search-alias=stripbooks&unfiltered=1&field-keywords=&field-author=&field-title=&field-isbn=<? echo $ResultISBN;?>&field-publisher=&node=&field-p_n_condition-type=&field-feature_browse-bin=&field-binding_browse-bin=&field-subject=&field-language=&field-dateop=&field-datemod=&field-dateyear=&sort=relevanceexprank&Adv-Srch-Books-Submit.x=0&Adv-Srch-Books-Submit.y=0">Search Amazon by ISBN</a>
			</td>
		</tr>
	</tr>
</table>
<div id="ButtonPlain<?echo $i;?>" style="display: inline">
	<INPUT TYPE="button" Name="edit<?echo $i;?>" VALUE="Edit" onClick="editRecord<?echo $i;?>()">
</div>
<div id="ButtonEdit<?echo $i;?>" style="display: none">
	<INPUT CLASS=inputButton onClick="updateRecord<?echo $i;?>(<?echo $ISBNWidth;?>)" Type=Submit Value=Update>
	<input type="hidden" Name="Mode" value="Update">		
	<input type="hidden" Name="ID" value='<? echo $ResultID; ?>'>		
</div>
</form>
<br><br>
<br>
<?}?>
<?if($found == 0) 
	{
		echo "This is not what I'm looking for:";
	}
?>
<br><br>
<?if($found == 0)
	{
		echo "<Form action='upload.php' method='GET'>";
  	echo "<input type='hidden' Name='Title' value='".$Title."'>";
  	echo "<input type='hidden' Name='Format' value='".$Format."'>";
  	echo "<input type='hidden' Name='Series' value='".$Series."'>";
  	echo "<input type='hidden' Name='Category' value='".$Category."'>";
  	echo "<input type='hidden' Name='Barcode' value='".$Barcode."'>";
  	echo "<input type='hidden' Name='ISBN' value='".$ISBN."'>";
  	echo "<input type='submit' value='Add Something New'>";
		echo "</form>";
	}
?>
<br>
<a href="index.php">Let Me Search Again</a>
</body>
</html>
