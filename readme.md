## Laravel-MBS

Run the project:

Clone the project
- run composer update
- run php artisan migrate
- run php artisan passport:install
- and php artisan serve


For Register as a New User <br />
Method POST, URL localhost:8000/api/register (Provide name, email, password, c_password)

For Login <br />
Method POST, URL localhost:8000/api/login (Provide email, password)

For Open New Account (after login and provide authorized token) <br />
Method POST, URL localhost:8000/api/account (Provide account_no, currency)

For Account List with Balance <br />
Method GET, URL localhost:8000/api/account

For Individual Account Balance <br />
Method POST, URL localhost:8000/api/currentbalance (Provide account_no)

For Depost <br />
Method POST, URL localhost:8000/api/deposit (Provide account_no, amount)
 
For Withdraw <br />
Method POST, URL localhost:8000/api/withdraw (Provide account_no, amount)

For Transfer <br />
Method POST, URL localhost:8000/api/transfer (Provide account_no, transfer_account_no, amount)

For Transaction History <br />
Method POST, URL localhost:8000/api/transaction (Provide account_no)