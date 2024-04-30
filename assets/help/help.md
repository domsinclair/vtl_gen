### Help

Vtl Gen is a module designed to assist you with everyday database administrative tasks that should make your development
work easier.

It will allow you to create and delete data, create and delete indexes and generate an export script when it comes time
to move your project into production.
If this is the first time that you've used the Vtl Data Generator then please take some time and read through the help
here to acquaint yourself with what the module does and how it does it.

Once you are familiar with the way the module works you can just jump to whichever function you wish to employ from the
navigation buttons at the top of the page.

#### Light andDark Mode Support

Vtl Data Generator has its own css stylesheet that makes use of the css
property ```@media (prefers-color-scheme: dark)``` which means that it should automatically respect your own system
preference for light or dark theme.

<div>
<figure>
<picture>
    <source srcset="vtl_gen_module/help/images/introDark.png" media="(prefers-color-scheme: dark)">
    <img src="vtl_gen_module/help/images/intro.png">
</picture>
<figcaption>Vtl Data Generator supports Light and Dark Mode</figcaption> 
</figure>
</div>

#### Issues

If you find an issue with the VTL Data Generator then please report
it [here](https://github.com/domsinclair/vtl_gen/issues). Items reported on github will automatically generate an email
to me and as such you're likely to get a faster response. General questions can of course be raised on the Trongate Help
Bar.

Please bear in mind that it's not always possible to respond immediately.

#### Disclaimer

The free version of this is supplied as is with no implied warranty. The code is open source and you are free to do with
it as you wish. Leaving this module in a production site could seriously compromise data integrity. You have been
warned!

### Creating Data

Vtl Data Generator makes use of the FakerPhp library. To generate data follow these steps

- Start at the Vtl Data Generator home page and click the create button under the Data section in the navigation.
- Select a table for which you wish create data from the drop down.

<div>
<figure>
<picture>
    <source srcset="vtl_gen_module/help/images/createdata1Dark.png" media="(prefers-color-scheme: dark)">
    <img src="vtl_gen_module/help/images/createdata1.png">
</picture>
<figcaption>Use the Select Table dropdown to choose a table</figcaption> 
</figure>
</div>

<br>

<div>
<figure>
<picture>
    <source srcset="vtl_gen_module/help/images/createdata2Dark.png" media="(prefers-color-scheme: dark)">
    <img src="vtl_gen_module/help/images/createdata2.png">
</picture>
<figcaption>Pick the table you want to create data for</figcaption> 
</figure>
</div>

- Once you have selected your table details about all of its columns will be shown.

<div>
<figure>
<picture>
    <source srcset="vtl_gen_module/help/images/createdata3Dark.png" media="(prefers-color-scheme: dark)">
    <img src="vtl_gen_module/help/images/createdata3.png">
</picture>
<figcaption>All of the table's available column details</figcaption> 
</figure>
</div>

- Select one or all of the fields for which you want data and then enter the number of rows you want to generate.

<div>
<figure>
<picture>
    <source srcset="vtl_gen_module/help/images/createdata4Dark.png" media="(prefers-color-scheme: dark)">
    <img src="vtl_gen_module/help/images/createdata4.png">
</picture>
<figcaption>Select one or all columns for data generation</figcaption> 
</figure>
</div>

<br>

> Note the last field in the illustration is picture of type varchar(255). The orders module had a single image uploader
> added to it via the Trongate Desktop app and that field was added automatically.

At the point that the single image uploader is added to the app only the required field is added to the database no
storage location is created by default. Vtl Data Generator understands the rules for the storage and creates it for you
automatically when it realises that you have selected a table that has a picture field in it.

<div>
<figure>
<picture>
    <source srcset="vtl_gen_module/help/images/createdata6.png" media="(prefers-color-scheme: dark)">
    <img src="vtl_gen_module/help/images/createdata6.png">
</picture>
<figcaption>No default storage is create by desktop app for image uploader</figcaption> 
</figure>
</div>

<br>

<div>
<figure>
<picture>
    <source srcset="vtl_gen_module/help/images/createdata7.png" media="(prefers-color-scheme: dark)">
    <img src="vtl_gen_module/help/images/createdata7.png">
</picture>
<figcaption>Default storage is automatically created for you</figcaption> 
</figure>
</div>

<br>

- With your chosen fields selected and number of rows required set click the Generate Fake Data button.
- Once generation is complete a dialog will inform you of success.
- If the presence of a picture field was detected you'll now see a Transfer Images button.

> Vtl Data Generator ships with its own set of dummy images  (in standard and thumbnail sizes). When you invoke the
> Transfer Images function it will transfer those dummies to the chosen module's image storage creating all the required
> folders as it does so. This process could be potentially time consuming so a progress bar will be displayed.

- Click the Transfer Images button.

<div>
<figure>
<picture>
    <source srcset="vtl_gen_module/help/images/createdata8Dark.png" media="(prefers-color-scheme: dark)">
    <img src="vtl_gen_module/help/images/createdata8.png">
</picture>
<figcaption>Transferring Images</figcaption> 
</figure>
</div>

<br>
This will create all the requires storage folders.

<div>
<figure>
<picture>
    <source srcset="vtl_gen_module/help/images/createdata9.png" media="(prefers-color-scheme: dark)">
    <img src="vtl_gen_module/help/images/createdata9.png">
</picture>
<figcaption>Newly Created folders for image storage</figcaption> 
</figure>
</div>

<br>

That completes the process. Now we can examine the data that has been created.

<div>
<figure>
<picture>
    <source srcset="vtl_gen_module/help/images/createdata10.png" media="(prefers-color-scheme: dark)">
    <img src="vtl_gen_module/help/images/createdata10.png">
</picture>
<figcaption>Generated data</figcaption> 
</figure>
</div>

<br>

When we examine an individual record we can see that it also has an image.

<div>
<figure>
<picture>
    <source srcset="vtl_gen_module/help/images/createdata11.png" media="(prefers-color-scheme: dark)">
    <img src="vtl_gen_module/help/images/createdata11.png">
</picture>
<figcaption>Individual record illustrating successful image transfer</figcaption> 
</figure>
</div>

<br>

#### How is the data generated

Vtl_gen generates data from either the field name or the data type.

By definition this is an imprecise science in as much as it necessitates having solutions for common field names and all
the likely data types. You can add your own additions to the relevant functions (both found in the Vtl_faker.php
controller). However, a more practical solution would probably be to submit requests for additional option to the
module's GitHub repo. By doing that if they warrant inclusion then they'll be available to all via the Trongate Module
repository.

#### Can I customise the way data is generated

The short answer is yes.

The more complicated answer is that it depends upon what you want to achieve and why.

If the only thing that you want to do is stress test the site to see how it handles large record sets then there is
arguably little reason to change the generated data.

The first thing that you can do is ensure that you have set the FAKER_LOCALE constant to your own region.
The next thing is to ensure that you have set the FAKER_DATE_FORMAT and FAKER_DATETIME_FORMAT to the date and datetime
formats that you prefer.
These constants can be found in the vtl_faker_config.php file which can be found in the assets folder of the vtl_faker
module.

Currently these are defined as follows;

```php
define('FAKER_LOCALE', 'en_GB');
define('FAKER_SEED', '13579');
define('FAKER_DATE_FORMAT', 'Y-m-d');
define('FAKER_DATETIME_FORMAT', 'Y-m-d H:i:s');
```

You may be wondering what the seed does, essentially it's a means of ensuring that the faker always creates the same
fake values. That can be particularly useful for running tests against your code so that you know what should appear.
If this is not of great importance to you then simply comment out this line which is in the createFakes method in the
vtl_faker.php controller.

```php
// Seed the faker.  This will ensure that the same data gets recreated
// which can be useful for testing purposes.
$faker -> seed(FAKER_SEED);
```

However, if you want to have highly realistic data and even data that is properly related between tables then additional
customisation will probably be required. The place where you will need to do that is in the vtl_faker controller and
specifically in the vtl_faker.php file.

Code generation takes place in the following two methods;

- ```private function generateSingleRowAndInsertViaApi()```
- ```private function generateMultipleRowsAndInsertViaApi()```
- ```private function generateSingleRowAndInsertViaSql()```
- ```private function generateMultipleRowsAndInsertViaSql()```

Each of the methods initially checks to see if a generator exists for the field name and if not reverts to a type
match. Logically therefore you should be looking to override the field generation.

> <b>Note that in order to make matching field names as easy as possible all field names are stripped of spaces,
> underscores or hyphens and then converted to lowercase. Therefore, for any customisation that you add, based on field
> names, make sure that you work with a lower case version. For example if the field name was Order_Id you would want to
> work with orderid.</b>

Each generation method works in the same way (they just construct the actual fake data statement that gets
inserted differently) and therefore you need to be looking at this part to add your customisation.

```php
  <?php
  foreach ($selectedRows as $selectedRow) {
           $originalFieldName = $selectedRow['field'];
           $field = $this->processFieldName($selectedRow['field']);
           $dbType = $selectedRow['type'];
           list($type, $length) = $this->parseDatabaseType($dbType);
           $fieldFakerStatement = $this->generateValueFromFieldName($faker, $field, $length);
                
           //This is where you should add code to generate custom field data
           //it needs to be in the form of:
           //  if($field === '<add your field name here') {
           //      $fieldFakerStatement = $faker -> rgbColor();
           //  }
```

You could actually add your own switch statement here in which case refer to the field and type switch statements there
already are for some guidelines. If you are working in an IDE like phpStorm then intellisense will come to your aid
when creating these statements because the IDE already knows about the Faker library as it is supplied with the Vtl Data
Generator.

When creating your own custom faker statements you may find this reference useful;

```php
// unique() forces providers to return unique values
$values = [];
for ($i = 0; $i < 10; $i++) {
    // get a random digit, but always a new one, to avoid duplicates
    $values []= $faker->unique()->randomDigit();
}
print_r($values); // [4, 1, 8, 5, 0, 2, 6, 9, 7, 3]

// providers with a limited range will throw an exception when no new unique value can be generated
$values = [];
try {
    for ($i = 0; $i < 10; $i++) {
        $values []= $faker->unique()->randomDigitNotNull();
    }
} catch (\OverflowException $e) {
    echo "There are only 9 unique digits not null, Faker can't generate 10 of them!";
}

// you can reset the unique modifier for all providers by passing true as first argument
$faker->unique($reset = true)->randomDigitNotNull(); // will not throw OverflowException since unique() was reset
// tip: unique() keeps one array of values per provider

// optional() sometimes bypasses the provider to return a default value instead (which defaults to NULL)
$values = [];
for ($i = 0; $i < 10; $i++) {
    // get a random digit, but also null sometimes
    $values []= $faker->optional()->randomDigit();
}
print_r($values); // [1, 4, null, 9, 5, null, null, 4, 6, null]

// optional() accepts a weight argument to specify the probability of receiving the default value.
// 0 will always return the default value; 1.0 will always return the provider. Default weight is 0.5 (50% chance).
// Please note that the weight can be provided as float (0 / 1.0) or int (0 / 100)

// As float
$faker->optional($weight = 0.1)->randomDigit(); // 90% chance of NULL
$faker->optional($weight = 0.9)->randomDigit(); // 10% chance of NULL

// As int
$faker->optional($weight = 10)->randomDigit; // 90% chance of NULL
$faker->optional($weight = 100)->randomDigit; // 0% chance of NULL

// optional() accepts a default argument to specify the default value to return.
// Defaults to NULL.
$faker->optional($weight = 0.5, $default = false)->randomDigit(); // 50% chance of FALSE
$faker->optional($weight = 0.9, $default = 'abc')->word(); // 10% chance of 'abc'

// valid() only accepts valid values according to the passed validator functions
$values = [];
$evenValidator = function($digit) {
    return $digit % 2 === 0;
};
for ($i = 0; $i < 10; $i++) {
    $values []= $faker->valid($evenValidator)->randomDigit();
}
print_r($values); // [0, 4, 8, 4, 2, 6, 0, 8, 8, 6]

// just like unique(), valid() throws an overflow exception when it can't generate a valid value
$values = [];
try {
    $faker->valid($evenValidator)->randomElement([1, 3, 5, 7, 9]);
} catch (\OverflowException $e) {
    echo "Can't pick an even number in that set!";
}
```

<br/>


> <b>NB:  Some data types need to be enclosed in strings others (typically integer types or floats and decimals) do not.
> It may well be a matter of experimentation to determine which is appropriate. Refer to the existing field and type
> switch statements for some guidance on how to do that. </b>

<br/>
#### Inserting the Data

Vtl_gen checks to see if the table that you have selected to have data generated for has an api associated with it. If
it does then it uses that to insert records. In the event that an api is not present for that table then it will
fall back to traditional Sql statements tio insert data.

In the event that you opt to insert a large number of records into a table (generally 2000 or more) then a sql
transaction will be employed rather than the api batch insert.

Once the operation has been completed a modal popup will inform you of the results.

#### The Inserted Data is not very Realistic

The first thing that you should do in this instance is to check that you have correctly set the FAKER_LOCALE to your own
locale. That will greatly improve things like address and name generation.

If the generator is able to use field names as the key to generation your end results will be better, falling back to
type is not ideal, but at least it does provide some data to work with.

It is possible to create very realistic data but to do so involves a great deal of customisation.

#### Images

The Vtl Data Generator ships with a number of images (all of one of my dogs as it happens) that will be used by the
generator to add image names to your data tables.
There are some important caveats to this. The first is that , currently at least, only the following field names will
end up with a valid image name in the database.

- Picture
- Picture Url
- Product Image
- Product Image Url
- Image
- Image Url

> <b> Remember that when working with field names the generator will convert everything to lowercase and strip out all spaces and underscores.</b>

#### Current Limitations

The biggest issue faced at present is with some, but not all, of the basic tables that form part of a basic Trongate
setup. Specifically issues revolve around Trongate security that I have yet to properly work out because you may well
need to refer to those tables from your own tables.

My current thoughts are that maybe a predefined number of users and associate levels should be created but I'm still
undecided on that point.

### Showing Data from the Application Tables

This facility is provided so that you can quickly look at existing data in all of the tables that reside in your
application's database.

- Click on the 'Show' button in the data section of the main navigation

<div>
<figure>
<picture>
    <source srcset="vtl_gen_module/help/images/showdata1Dark.png" media="(prefers-color-scheme: dark)">
    <img src="vtl_gen_module/help/images/showdata1.png">
</picture>
<figcaption>Select a table to show its data</figcaption> 
</figure>
</div>

- As soon as you have selected a table its data will be displayed in a seperate view.

<div>
<figure>
<picture>
    <source srcset="vtl_gen_module/help/images/showdata2Dark.png" media="(prefers-color-scheme: dark)">
    <img src="vtl_gen_module/help/images/showdata2.png">
</picture>
<figcaption>Table data displayed</figcaption> 
</figure>
</div>

If there is no data in the table to be displayed an appropriate message to that effect will be shown.

### Deleting Data

Deleting data is a pretty straightforward operation.

Simply select those tables for which you wish to delete data and then click the 'Clear Data' button. You will then see a
modal detailing what has happened.

<div>
<figure>
<picture>
    <source srcset="vtl_gen_module/help/images/deletedata1Dark.png" media="(prefers-color-scheme: dark)">
    <img src="vtl_gen_module/help/images/deletedata1.png">
</picture>
<figcaption>Select the table or tables from which to delete data</figcaption> 
</figure>
</div>



> By default, the following tables will have all but the first row of data deleted so as to retain a default Trongate
> installation;
>  - trongate_administrators
>  - trongate_users
>  - trongate_user_levels

You can also opt to delete data from a table and reset the auto increment. To trigger that option you should check the '
Reset Primary Auto Increment' at the bottom of the list of tables. This invokes a Truncate command dropping and
resetting the table. It is a much quicker way to delete large tables. Because of the fact that you may need to retain
certain Trongate security information this option will not be applied to the following tables;

- trongate_administrators
- trongate_users
- trongate_user_levels

Images that have been added to folders that follow the naming conventions employed by the desktop app when adding a
single image uploader will also be deleted along with the folders that contained them. The main repository folders will
not be deleted.

<div>
<figure>
<picture>
    <source srcset="vtl_gen_module/help/images/deletedata2.png" media="(prefers-color-scheme: dark)">
    <img src="vtl_gen_module/help/images/deletedata2.png">
</picture>
<figcaption>Image storage returned to pristine state</figcaption> 
</figure>
</div>

### Creating Indexes

Indexes on database columns can be extremely beneficial on those columns that are frequently searched. They do however
exact a price, insertion takes longer because of the need to keep the index up to date, and they can consume resources.
However, the benefits that they bring to searches make them worthwhile..

#### To create an Index

- Select the table for which you wish to create an index.

<div>
<figure>
<picture>
    <source srcset="vtl_gen_module/help/images/createindex1Dark.png" media="(prefers-color-scheme: dark)">
    <img src="vtl_gen_module/help/images/createindex1.png">
</picture>
<figcaption>Select the table on which to create an index</figcaption> 
</figure>
</div>

- Select the column(s) you wish to index.

<div>
<figure>
<picture>
    <source srcset="vtl_gen_module/help/images/createindex2Dark.png" media="(prefers-color-scheme: dark)">
    <img src="vtl_gen_module/help/images/createindex2.png">
</picture>
<figcaption>Select the table on which to create an index</figcaption> 
</figure>
</div>

- Click 'Create Index'.

Indexes will be named automatically for you and will follow this pattern;

idx_table name_column name

### Deleting Indexes

You can delete indexes that have been created by this module. to do so head to the delete indexes view and select the
table you're interested in. If there are any indexes on it created by this module then they will be shown in a table,
and
you will have the option to select and then delete them. If only the Primary key index is there you will be informed as
such but will not be given the option to delete it.

<div>
<figure>
<picture>
    <source srcset="vtl_gen_module/help/images/deleteindex1Dark.png" media="(prefers-color-scheme: dark)">
    <img src="vtl_gen_module/help/images/deleteindex1.png">
</picture>
<figcaption>Select a table to see custom indexes </figcaption> 
</figure>
</div>


<div>
<figure>
<picture>
    <source srcset="vtl_gen_module/help/images/deleteindex2Dark.png" media="(prefers-color-scheme: dark)">
    <img src="vtl_gen_module/help/images/deleteindex2.png">
</picture>
<figcaption>Select the index or indexes to delete</figcaption> 
</figure>
</div>


<div>
<figure>
<picture>
    <source srcset="vtl_gen_module/help/images/deleteindex3Dark.png" media="(prefers-color-scheme: dark)">
    <img src="vtl_gen_module/help/images/deleteindex3.png">
</picture>
<figcaption>This table has no custom indexes</figcaption> 
</figure>
</div>

### Exporting a Database Script

Vtl Data Generator also includes the following library mysqldump.php which allows you to run a mysqldump from php.

To export the database select those tables that you wish to export by checking the relevant boxes on the lefthand side
of the table. If you wish to export the data from the tables then check the relevant box io the right hand side.

The export process is highly configurable so to avoid too much only the most common settings have been applied. However
if you look at the export code in the module and consult the readme for the mysqldump.php file you will be able to see
what you can configure.

<div>
<figure>
<picture>
    <source srcset="vtl_gen_module/help/images/exportdata1Dark.png" media="(prefers-color-scheme: dark)">
    <img src="vtl_gen_module/help/images/exportdata1.png">
</picture>
<figcaption>Select the tables for which you require an export script</figcaption> 
</figure>
</div>


> Backups will be stored in the following location
> vtl_gen/vtl_faker/assets/backups   
> If that folder does not exist it will be created for you.


<div>
<figure>
<picture>
    <source srcset="vtl_gen_module/help/images/exportdata2.png" media="(prefers-color-scheme: dark)">
    <img src="vtl_gen_module/help/images/exportdata2.png">
</picture>
<figcaption>Select the tables for which you require an export script</figcaption> 
</figure>
</div>