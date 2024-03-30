### Creating Indexes

Indexes on database columns can be extremely beneficial on those columns that are frequently searched. They do however
exact a price, insertion takes longer because of the need to keep the index up to date, and they can consume resources.
However, the benefits that they bring to searches make them worthwhile..

#### To create an Index

- Select the table for which you wish to create an index.
- Select the column(s) you wish to index.
- Click 'Create Index'.

Indexes will be named automatically for you and will follow this pattern;

idx_<tablename>_<column name>