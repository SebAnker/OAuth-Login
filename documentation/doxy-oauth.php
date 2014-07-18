<?php
/*!
@page oauth Overview of OAuth 2.0

@section intro_oauth Introduction
OAuth 2.0 is an open authorization protocol which enables applications to access each others data. For example, a game application can access a users data in the Facebook application etc.
The following sections should give a basic overview of the OAuth 2.0 protocol.


@section roles Roles
OAuth 2.0 defines four basic roles:
	- The resource owner (or user): \n
		The person (or application) that owns the data that is to be shared \n\n
	- The resource server: \n
		The server responsible for storing and managing the resources \n\n
	- The client applictation (or just client): \n
		The application that requests access to the rescources stored on the resource server \n\n
	- The authorization server: \n
		The server responsible for authorizing the client to access the protected resources, can be the same as the resource server
	.
Their interaction is illustrated in the following diagram:
@image html overview-roles.png 
@image latex overview-roles.eps "Basic roles defined by OAuth 2.0" width=10cm



@section flow Abstract Protocol Flow

The basic flow of OAuth 2.0 (as shown in the diagram below) is as follows:

To get access to protected resources a client sends an authorization request to the resource owner(user). If the user grants authorization, the client gets an authorization grant, this grant can take different forms (see @ref grant) \n
The client exchanges the grant for an access token at the authorization server. \n
The access token can then be used by the client to access the protected resources.

@image html flow.png
@image latex flow.eps "Abstract protocol flow" width=10cm




@section auth Authorization

@subsection registration Registration

Before the client can request access to protected resources, the client 	needs to register with the authorization server.\n
During that process the client is assigned a unique client ID  and a client secret.\n
The client also registers a redirect URI, which is where the user is redirected to, after granting/denying authorization.\n


@subsection grant Authorization Grant

OAuth 2.0 specifies the following four types of authorization grants:


@subsubsection authcode Authorization Code

Since this is the grant implemented in this application a more detailed explanation is required. \n
The basic idea of this grant is as follows:
 	- The user accesses the client application (1)
	- The client app asks the user to log in via an authorization server (2)
	- The user is redirected to the authorization server by the client,
		the client also sends its client ID along (3)
	- After successful login via authorization server the user is asked if 		he/she wants to grant authorize the client to access the user data.\n
		The user is redirected to the registered redirect URI of the client along with an authorization code (4, 5)
	- At the redirect URI the client connects directly to the authorization server and sends its client ID, client secret and the authorization code.\n
If all of the data is valid, the authorization server sends back an access token (6, 7)
	- This token is sent with every recource request and serves as authentication of the client and authorization to access the recources (10-13)

@image html authorization-auth-code.png
@image latex authorization-auth-code.eps "authorization code grant flow" width=8cm

@subsubsection implicit Implicit
The implicit grant is similar to authorization code grant, the difference being that the authorization server sends an access token \n
to the client immediatly after the client logged in and authorized the client.

@subsubsection ropc Resource Owner Password Credentials
This grant requires the user to give the own credentials to the client application, so that the client can use them to access the resources.

@subsubsection cc Client Credentials
The authorization server exchanges an access token for client ID and client secret directly


@subsection endpoint Endpoint
An endpoint is usually a URI on a server, in the case of OAuth 2.0 these are:
	- The authorization endpoint: \n
		The endpoint where the user logs in and grants/denies authorization to the client \n\n
	- The redirect endpoint: \n
		The endpoint where the user is redirected to after granting/denying authorization  \n\n
	- The token endpoint: \n
		The endpoint where the client exchanges client ID, client secret and authorization code for an access token \n\n

@image html endpoints.png
@image latex endpoints.eps "Endpoints defined by OAuth 2.0" width=7cm

*/
