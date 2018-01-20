# PHP Scripting
The goal of this project is to learn server-side web programming using PHP. More specifically, to develop an e-commerce web application where customers can buy products listed by the eBay Commerce Network API for Shopping.

The shopping.com API was used for shopping. The eBay Commerce Network API ("ECN API") is a flexible way to access and recreate practically everything you see on Shopping.com. You need to get your own API key or use a demo API key. The Search by keyword method and the Requesting category tree information -> Include all descendants in category tree method were adopted from the eBay Commerce Network Publisher API Use Cases.

The search form has a menu to select a category, a text window to specify search keywords, and a submit button. The menu contains all sub-categories of the category "computers" (whose id is 72). A PHP session was implemented to store the shopping basket (the list of chosen items throughout the session) as well as the results of the previous search. The PHP script handles the following query strings:

•	To search for all items in the "Laptops" category that match the search keywords "samsung i7": buy.php category=9007&search=samsung+i7

•	To put a listed item with id=138681275 in the shopping basket (if it does not exist): buy.php?buy=138681275

•	To remove a listed item with Id=138681275 from the shopping basket (if exists): buy.php?delete=138681275

•	To clear the shopping basket: buy.php?clear=1
