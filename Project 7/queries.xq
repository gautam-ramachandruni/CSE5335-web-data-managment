(: Gautam Ramachandruni :)
<query1> &#xa;1. Print the number of items listed on all continents.&#xa;<itemcount>
{
fn:sum
    (
        for $item in doc("auction.xml")/site/regions/*
            return {count($item/item)}
    ) 
}
</itemcount>&#xa;</query1>,'&#xa;',


<query2> &#xa;2. List the names of items registered in Europe along with their descriptions.&#xa;<europeitems>
{
for $item in doc("auction.xml")/site/regions/europe//item
    return {$item/name, '&#xa;',$item/description, '&#xa;'}
}
</europeitems>&#xa;</query2>,'&#xa;',


<query3> &#xa;3. List the names of persons and the number of items they bought.&#xa;<purchases>
{
for $buyer in doc("auction.xml")/site/people/person
let $item_bought := for $auction in doc("auction.xml")/site/closed_auctions/closed_auction
           where $auction/buyer/@person = $buyer/@id
           return $auction
return ({$buyer/name},'&#xa;',<itemcount> {count($item_bought)} </itemcount>,'&#xa;')
}
</purchases>&#xa;</query3>,'&#xa;',


<query4> &#xa;4. List all persons according to their interest (ie, for each interest category, display the persons on that category).
{
for $cat in doc("auction.xml")/site/categories/category
let $people := for $person in doc("auction.xml")/site/people//person
           where $person/profile/interest/@category = $cat/@id
           return $person
return (<category>&#xa;{$cat/@id/string()} &#xa;<people> &#xa;{for $p in $people return (<person> {$p/name/string()} </person>, '&#xa;')} </people>&#xa;</category>, '&#xa;')
}
</query4>,'&#xa;',


<query5> &#xa;5. Group persons by their categories of interest and output the size of each group.&#xa;<categorysize>
{
for $cat in doc("auction.xml")/site/categories/category
let $people := for $person in doc("auction.xml")/site/people//person
           where $person/profile/interest/@category = $cat/@id
           return $person
return (<category>&#xa;{$cat/@id/string()}&#xa;<size> {count($people)} </size>&#xa;</category>, '&#xa;')
}
</categorysize>&#xa;</query5>,'&#xa;',


<query6> &#xa;6. List the names of persons and the names of the items they bought in Europe.&#xa;<europebuyers>
{
for $europe_item in doc("auction.xml")/site/regions/europe/item
for $person in doc("auction.xml")/site/people/person
let $items_bought := for $items in doc("auction.xml")/site/closed_auctions/closed_auction
                where $items/itemref/@item = $europe_item/@id and $items/buyer/@person = $person/@id
                return (<person> {$person//../name/string()} </person>,'&#xa;', <item> {$europe_item/name/string()} </item>, '&#xa;')
return (for $item in $items_bought return {$item})
}</europebuyers>&#xa;</query6>,'&#xa;',


<query7> &#xa;7. Give an alphabetically ordered list of all items along with their location.&#xa;<ordereditems>
{
for $item in doc("auction.xml")/site/regions/*/item
order by $item/name
return ({$item/name}, '&#xa;', {$item/location},'&#xa;')
}</ordereditems>&#xa;</query7>,'&#xa;',

<query8> &#xa;8. List the reserve prices of those open auctions where a certain person with id person3 issued a bid before another person with id person6. (Here before means "listed before in the XML document", that is, before in document order.)&#xa;<prices>
{
for $auction in doc("auction.xml")/site/open_auctions/open_auction
for $bid3 in $auction/bidder
for $bid6 in $auction/bidder
where $bid3/personref/@person = "person3" and $bid6/personref/@person="person6" and index-of($auction/bidder, $bid3)<index-of($auction/bidder, $bid6)
return ({$auction/reserve}, '&#xa;')
}</prices>&#xa;</query8>