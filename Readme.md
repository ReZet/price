Please, use Makefile and run `make init` for launch the project. 

The server should be available by http://localhost:8003. There are two endpoints:

 - GET http://localhost:8003/api/price - to get all added prices
 - POST http://localhost:8003/api/price - for sending new prices

You can make http requests from a file `./http/client.http`