## Laravel-MBS

Run the project:

Clone the project
- run composer update
- run php artisan migrate
- run php artisan passport:install
- and php artisan serve


For Register as a New User 
Method POST, URL localhost:8000/api/register (Provide name, email, password, c_password)

For Login
Method POST, URL localhost:8000/api/login (Provide email, password)

For Open New Account (after login and provide authorized token)
Method POST, URL localhost:8000/api/account (Provide account_no, currency)

For Account List with Balance
Method GET, URL localhost:8000/api/account

For Individual Account Balance
Method POST, URL localhost:8000/api/currentbalance (Provide account_no)

For Depost
Method POST, URL localhost:8000/api/deposit (Provide account_no, amount)

For Withdraw
Method POST, URL localhost:8000/api/withdraw (Provide account_no, amount)

For Transfer
Method POST, URL localhost:8000/api/transfer (Provide account_no, transfer_account_no, amount)

For Transaction History
Method POST, URL localhost:8000/api/transaction (Provide account_no)

