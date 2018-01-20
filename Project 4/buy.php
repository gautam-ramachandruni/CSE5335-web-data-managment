<!doctype html>
<!-- Gautam Ramachandruni -->
<html>
<head><title>Buy Products</title></head>
<body style="font-family:verdana;">


<p/>
<form action="buy.php" method="GET">
<fieldset><legend> <b>Find products</b></legend>
<label>Category: <select style="font-family:verdana;" name="category">
	
<?php
session_start();
error_reporting(E_ALL ^ E_WARNING);
ini_set('display_errors','On');
$xmlstr = file_get_contents('http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/CategoryTree?apiKey=78b0db8a-0ee1-4939-a2f9-d3cd95ec0fcc&visitorUserAgent&visitorIPAddress&trackingId=7000610&categoryId=72&showAllDescendants=true');
$xml = new SimpleXMLElement($xmlstr);
$root = $xml->category;
echo "<option value=\"{$root['id']}\"> {$root->name} </option>";
echo "<optgroup label=\"{$root->name}:\">";

foreach($root->categories->category as $category) {
	echo "<option value=\"{$category['id']}\"> {$category->name} </option>";
	echo "<optgroup label=\"{$category->name}:\">";
	
	foreach($category->categories->category as $subcategory) {
		echo "<option value=\"{$subcategory['id']}\"> {$subcategory->name} </option>";
	}
	echo "</optgroup>";
}
echo "</optgroup>";
?>

</select>
</label>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<label>Search keywords: <input type="text" name="search"/></label>
<input name="SearchButton" type="submit" value="search" style="font-family:verdana;"/>
</fieldset>

<p/>
<?php
error_reporting(E_ALL ^ E_WARNING);
ini_set('display_errors','On');
if(!isset($_SESSION)) { session_start(); }
if(isset($_GET["SearchButton"]) && isset($_GET['search']) && $_GET['search'] !=='' ) {
	$keyword = $_GET['search'];
	$selectedcategory = $_GET['category'];
	$searchxmlstr = file_get_contents('http://sandbox.api.ebaycommercenetwork.com/publisher/3.0/rest/GeneralSearch?apiKey=78b0db8a-0ee1-4939-a2f9-d3cd95ec0fcc&trackingId=7000610&categoryId='. $selectedcategory . '&keyword=' . urlencode($keyword) . '&numItems=20'); 
	$searchxml = new SimpleXMLElement($searchxmlstr);
	$searchroot = $searchxml->categories->category;
	echo "<h3> Search Results </h3><p/>";
	echo "<table border=1>";
	echo "<tr>
    <th>Product Image</th>
    <th>Product Name</th> 
    <th>Price</th>
	<th>Description</th>
  </tr>";
	if(isset($searchroot->items->product)) {
		//$_SESSION['test'] = "script 2";
		$_SESSION['productIDs'] = array();
		$_SESSION['productNames'] = array();
		$_SESSION['productMinPrices'] = array();
		$_SESSION['productImgs'] = array();
		$_SESSION['productOffersURLs'] = array();
		$_SESSION['productDescriptions'] = array();
		foreach($searchroot->items->product as $product) {
			$id = (string)$product['id'];
			//if(isset($_GET['delete']) && ($_SESSION['productIDs']) && !empty($_SESSION['productIDs']) && $_GET['delete']===$id){
				//dont add id to table
			//}
			//else{
				array_push($_SESSION['productIDs'], $id);
				$_SESSION['productNames'][$id] = (string)$product->name;
				$_SESSION['productMinPrices'][$id] = (string)$product->minPrice;
				$_SESSION['productImgs'][$id] = (string)$product->images->image->sourceURL;
				$_SESSION['productOffersURLs'][$id] = (string)$product->productOffersURL;
				$_SESSION['productDescriptions'][$id] = (string)$product->fullDescription;
				
				echo "<tr><td>";
				echo "<a href=\"buy.php?buy={$id}\">";
				echo "<img src=\"{$product->images->image->sourceURL}\"/>";
				echo "</a></td>";
				echo "<td>{$product->name}</td>";
				echo "<td align=right>\${$product->minPrice}</td>";
				echo "<td>{$product->fullDescription}</td>";
				echo "</tr>";
			//}			
		}
		echo "</table><p/>";
	}
	else {
		echo "No products exist, try a different category";
	}	
}
/*
if(isset($_SESSION['productIDs']) && !empty($_SESSION['productIDs'])){
	echo "<label> Search Results </label><p/>";
	echo "<table border=1>";
		foreach($_SESSION['productIDs'] as $ID){
				echo "<tr><td>";
				echo "<a href=\"buy.php?buy={$ID}\">";
				echo "<img src=\"{$_SESSION['productImgs'][$ID]}\"/>";
				echo "</a></td>";
				echo "<td>{$_SESSION['productNames'][$ID]}</td>";
				echo "<td align=right>\${$_SESSION['productMinPrices'][$ID]}</td>";
				echo "<td>{$_SESSION['productDescriptions'][$ID]}</td>";
				echo "</tr>";
		}
	echo "</table><p/>";
}
*/
?>

<?php
	error_reporting(E_ALL ^ E_WARNING);
	ini_set('display_errors','On');
	if(!isset($_SESSION)) { session_start(); }
	if(!isset($_SESSION['cartIds'])) {
		$_SESSION['cartIds'] = array();
	}
	if(isset($_GET['buy'])) {
		//if(isset($_GET['delete']) && ($_GET['buy'] !== $_GET['delete'])){
		//echo "";
		//foreach($_SESSION['cartIds'] as $existingID){
		//	if ($existingID===$_GET['buy']){
				
		//	}
		//	else{
		array_push($_SESSION['cartIds'], $_GET['buy']);
		//	}
		//}
		unset($_GET['buy']);
		//}
	}
	if(isset($_SESSION['cartIds']) && !empty($_SESSION['cartIds'])) {
		//print_r($_SESSION['cartIds']);
		if(isset($_GET['delete'])) {
			foreach($_SESSION['cartIds'] as $key => $idToDelete) {
				if($_GET['delete']===$idToDelete) {
					unset($_SESSION['cartIds'][$key]);
				}
			}
		}
		if(isset($_GET['clear'])) {
			$_SESSION['cartIds']=array();	
		}
		if(!empty($_SESSION['cartIds']) && !isset($_GET['clear'])) {
			echo "<h3> Shopping Cart: </h3><p/>";
			echo "<table border=\"1\">";
			echo "<tr>
    <th>Product Image</th>
    <th>Product Name</th> 
    <th>Price</th>
	<th>Modify Cart</th>
  </tr>";
			$_SESSION['cartTotal'] = 0;
			$uniquearr = array_unique($_SESSION['cartIds']);
			$_SESSION['cartIds'] = $uniquearr;
			foreach($_SESSION['cartIds'] as $cartId) {
				//if(isset($_GET['delete']) && $_GET['delete']===$cartId){
				//	unset($_SESSION['cartIds'][$key]);
				//}
				//else{
					echo "<tr><td>";
					echo "<a href=\"{$_SESSION['productOffersURLs'][$cartId]}\">";
					echo "<img src=\"{$_SESSION['productImgs'][$cartId]}\"></a>";
					echo "</td>";
					echo "<td>{$_SESSION['productNames'][$cartId]}</td>";
					echo "<td align=right>\${$_SESSION['productMinPrices'][$cartId]}</td>";
					echo "<td><a href=\"buy.php?delete={$cartId}\">delete</a>";
					echo "</td></tr>";
					$_SESSION['cartTotal'] += $_SESSION['productMinPrices'][$cartId];
				//}
			}
			echo "</table><p/>";
			echo "Total: $" .$_SESSION['cartTotal']." <p/>";
			echo "			
				<form action=\"buy.php\" method=\"GET\">
				<input type=\"hidden\" name=\"clear\" value=\"1\"/>
				<input type=\"submit\" value=\"Empty Basket\"/>
				</form>
			";
		}
	}
?>

</body>
</html>