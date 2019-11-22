### Laravel File Converter Kata

#### Task:
- Read the users from the xml, csv and json files within the `storage/app/public/data` directory
- Merge all users into a single list and sort them by their `userId` in ascending order
- Write the ordered results to new xml, csv and json files, see the `storage/app/public/converted` directory
  - Results should use the same structure as the source files they were parsed from
  - The exception is for `lastLoginTime` where an `ISO 8601` date format is preferred for output
- Use Laravel Command to convert files

#### Steps

##### 1. Initial steps
###### 1.1 Deploy source 
```
composer install
```

##### 2. Run
###### 2.1 Execute
```
php artisan file:convert
```

##### 3. Test
###### 3.1 Integration Test
```
bin/phpunit
```
###### 3.2 Function Test
```
bin/phpspec run
```
