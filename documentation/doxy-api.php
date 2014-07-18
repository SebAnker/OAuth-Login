<?php

/*!
@page api API Documenation
@section sec_api API
All requests need to be made to api/foo, for example: \n
http://example.com/Login-Authorization/api/request-token

@subsection sub1 Authentication

@subsubsection subsub1 request-token
Redirect here with these query parametes:
	- client_id
	- scope
	- redirect_uri (optional if only one is registered)
	- user_id
	.
A approval dialog will open, if the user grants authorization \n
the user will be redirected to redirect_uri with these query parameters:
	- code
	- state
	.
@latexonly
\vspace{10cm}
\pagebreak
@endlatexonly

@subsubsection subsub2 access-token
Send a POST request with these parametes to receive an access token:
	- client_id
	- client_secret
	- redirect_uri (same as in request-token or the only registered one)
	- code (from request-token)
	.
The response will be a JSON:\n
@verbatim
   {"access_token": string,
    "expires_in": int,
    "token_type": string,
    "scope": string}
@endverbatim

@subsection sub2 Resource
@subsubsection subsub3 validate
Send a POST request with these parametes to check if a token is still valid:
	- access_token
	.
The response will be a JSON:\n
@verbatim
   {"success": bool}
@endverbatim


@subsubsection subsub4 get-login-data
Send a POST request with these parametes to get the data of the user associated with the token:
	- access_token
	.
The response will be a JSON:\n
@verbatim
   {"user_name": string,
    "user_id": int,
    "foaf_uri": string}
@endverbatim

*/
