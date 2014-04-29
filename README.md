# uup-service

Your MessageBox place. PHP backend. > http://api.uup.nu

# database

| id         | content  | code       | ip          | date      | view    |
| -----------|:--------:| ----------:|------------:|----------:|--------:|
| bigint(16) | longtext | varchar(8) | varchar(16) | timestamp | int(10) |

# docs

- `GET /` Get service information.
- `POST /` New record.
- `GET /:code` Get record of record's code.
- `GET /max/` Get max view record.
- `GET /me/` Get all records of your ip address.

## dependencies

- [NotORM] for database communication.
- [SlimPHP] for micro framework.

## license

This script is available under the GPL license.

## author

* [Eray Arslan](http://erayarslan.com)
