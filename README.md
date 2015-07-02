This project was created in order to complete a technical test for Newscorp. You
can clone it using the following command: 

```
git clone git@github.com:marinusjvv/recipefinder.git
```

You will then have to run the following command in the project's main folder, in
order to download it's dependancies:

```
composer install
```

The program is run via commandline. To run open the commandline in the main
directory. Run the script with the required parameters in the following format:

```
php index.php [fridge file location] [recipe file location]
```

Example
```
php index.php tests/data/fridge_input_different_dates.csv tests/data/recipe_two_valid.txt
```