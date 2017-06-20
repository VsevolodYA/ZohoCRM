ZohoCRM API test 
1. Clone project.
2. In project folder you have to run command: composer install (in order to install all necessary dependencies).
3. Change permissions for those folders: bootstrap, public, storage to 755.
4. Create .env file for instance: .env.example.
5. Insert your settings to connect to the database in .env.
6. Insert your setting for ZohoCRM.
7. Run php artisan migrate in order to create 'contact' table.
8. Configure virtual host or use the following command: php artisan serve.