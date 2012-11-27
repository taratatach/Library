Library
=======

CSU East Bay Database Architecture (CS 4660) course's web database project using MySQL and PHP.

Database Schema
===============

Read as follow :<br/>
> _Primary key_ <br/>
> "Foreign keys"
    
<table>
<tr><td colspan="2"><strong>Branches</strong></td></tr>
<tr><td><em>BranchName</em></td><td>City</td></tr>
</table>

<table>
<tr><td colspan="2"><strong>Cardholders</strong></td></tr>
<tr><td><em>CardholderName</em></td><td>City</td></tr>
</table>

<table>
<tr><td colspan="2"><strong>Categories</strong></td></tr>
<tr><td><em>CategoryName</em></td><td>Expert</td></tr>
</table>

<table>
<tr><td colspan="5"><strong>Books</strong></td></tr>
<tr><td><em>Title</em></td><td><em>Copy</em></td><td>"Branch"</td><td>"Cardholder"</td><td>"Category"</td></tr>
</table>

<table>
<tr><td colspan="5"><strong>Dvds</strong></td></tr>
<tr><td><em>Title</em></td><td><em>Copy</em></td><td>"Branch"</td><td>"Cardholder"</td><td>"Category"</td></tr>
</table>

SQL queries
===========

## subqueries

_List the names of the cardholders who have two or more books checked out and where at least one is in the Science category._
```sql
SELECT cardholder
FROM books b1
WHERE (

SELECT count( * ) 
FROM books
WHERE b1.cardholder = cardholder
) >1
AND b1.category = "Science"
```
_List the titles owned as books but not Dvds by the Bayfair branch._
```sql
SELECT title
FROM books b1
WHERE NOT 
EXISTS (

SELECT * 
FROM dvds, books
WHERE dvds.title = books.title
AND books.title = b1.title
)
AND b1.branch = "Bayfair"
```

_sub-subquery with aggregates_
```sql
SELECT DISTINCT b1.branch, (

SELECT count( * )
FROM books
WHERE branch = b1.branch
AND category = 'Consumer'
) AS NumBooksOwned, (

SELECT min( title )
FROM books
WHERE branch = b1.branch
AND category = 'Consumer'
) AS FirstBook, (

SELECT max( length( cardholder ) )
FROM books
WHERE branch = b1.branch
AND category = 'Consumer'
) AS LongestCardholderNameLength
FROM books b1
```

## union

_List the titles of books and Dvds owned by the Downtown branch._
```sql
(
SELECT title
FROM books
WHERE branch = "Downtown"
)
UNION (

SELECT title
FROM dvds
WHERE branch = "Downtown"
)
```

More in reports 3, 4, 6 and 7.
