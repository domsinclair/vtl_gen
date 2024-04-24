### Creating Data

Vtl Data Generator makes use of the FakerPhp library.

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

The Vtl Data Generator is now capable of transferring images to the directories that are created when you use the
desktop app to add a single image uploader to a module. By definition when you do that two directories are created in
the assets folder of your module <moduleName>_pis and <moduleName>_pics_thumbnails. The Generator will add additional
folders to those (named after the id of each record) and copy the correct image to it from those supplied in the
vtl_faker module.

To invoke this, once you have added data to a table that has a picture field added by the single image uploader you will
then see another button with the text 'Transfer Images' on it. Clicking that will invoke the transfer.
Both standard sized and thumbnail images are provided.

In the case of the Vtl Data Generator the provided images can be found at this location vtl_gen/vtl_faker/assets/images

> <b> This will not as yet work with single image uploaders you create yourself unless you happen to add image repositories in the assets folder of the module in question that follow the same naming convention as the desktop app follows.</b>

<br/>

#### Current Limitations

The biggest issue faced at present is with some, but not all, of the basic tables that form part of a basic Trongate
setup. Specifically issues revolve around Trongate security that I have yet to properly work out because you may well
need to refer to those tables from your own tables.

My current thoughts are that maybe a predefined number of users and associate levels should be created but I'm still
undecided on that point.



