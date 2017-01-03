<?php
	echo $_POST[''];
    $sqli='SELECT product_name,product_id,price FROM products';
	$resulti=mysqli_query($con,$sqli);
	if(mysqli_num_rows($resulti) > 0){
		//echo"<table border=1><tr><th>product_Name</th><th>product_ID</th><th>Price</th>";
	echo '<form name="empty" method="POST">';
	 while($row = mysqli_fetch_assoc($resulti)){		 
       echo "<p class=inline-p>".$row["product_id"].')'."</p>";	
       $n = $row["product_id"];  
	   echo "<p class=inline-p>".$row["product_name"]."</p>";
	   echo '<br>';
	   echo "<p>".'$'.$row["price"]."</p>"; 	
	   echo "<p class=inline-p>quantity:</p>";
	   echo "<input type='text' class=inline-p>";
	   echo '<br>';
	   echo '<br>';
       echo "<input type='submit' class='ban' value='Add to cart'  id='$n'  onclick='alert(this.id)'>";
	   //echo "<input type='hidden' value=".$row['product_id']." name='input2'/>";
       // print '<center><a href="admin.php?id='.$row['product_id'].'" class="buttonize">Add to cart</a></center>';                          	   
	   echo '<br>';
	   //echo $row['product_name'] . " " . $row['product_id']. " " . $row['price'];
	   echo '<br>';
	  
	}
	echo '</form>';
	}else{echo"0 results";} 
?>