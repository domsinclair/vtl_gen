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

The first thing that you can do is ensure that you have set the FAKER_LOCALE constant to your own region. The constant can be found in the vtl_faker_config.php file which can be found in the assets folder of the vtl_faker module.

However, if you want to have highly realistic data and even data that is properly related between tables then additional
customisation will probably be required. The place where you will need to do that is in the vtl_faker controller and
specifically in the vtl_faker.php file.

Code generation takes place in the following two methods;

- ```private function generateSingleRowAndInsertViaApi()```
- ```private function generateMultipleRowsAndInsertViaApi()```

Each of the two methods initially checks to see if a generator exists for the field name and if not reverts to a type
match. Logically therefore you should be looking to override the field generation.

> <b>Note that in order to make matching field names as easy as possible all field names are stripped of spaces,
> underscores or hyphens and then converted to lowercase. Therefore, for any customisation that you add, based on field
> names, make sure that you work with a lower case version. For example if the field name was Order_Id you would want to
> work with orderid.</b>

Each of the two generation methods works in the same way (they just construct the actual fake data statement that gets
inserted differently) and therefore you need to be looking at this part to add your customisation.

```php
 foreach ($selectedRows as $selectedRow) {
    $originalFieldName = $selectedRow['field'];
    $field = $this->processFieldName($selectedRow['field']);
    $fieldFakerStatement = $this->generateValueFromFieldName($faker, $field);
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

> <b>NB:  Some data types need to be enclosed in strings others (typically integer types or floats and decimals) do not.
> It may well be a matter of experimentation to determine which is appropriate. Refer to the existing field and type
> switch statements for some guidance on how to do that. </b>

#### Inserting the Data

Vtl_gen checks to see if the table that you have selected to have data generated for has an api associated with it. If
it does then it uses that to insert records. In the event that an api is not present for that table then it will
fall back to traditional Sql statements tio insert data.

In the event that you opt to insert a large number of records into a table (generally 2000 or more) then a sql
transaction will be employed rather than the api batch insert.

Once the operation has been completed a modal popup will inform you of the results.

#### Current Limitations

The biggest issue faced at present is with some, but not all, of the basic tables that form part of a basic Trongate
setup. Specifically issues revolve around Trongate security that I have yet to properly work out because you may well
need to refer to those tables from your own tables.

My current thoughts are that maybe a predefined number of users and associate levels should be created but I'm still
undecided on that point.

There is currently no inbuilt method to generate images (or image links).

