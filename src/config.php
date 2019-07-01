<?php
/**
 * Config file
 *********************************************************/

// DB login info

define('DB_HOST', 'localhost');		# SQL user name
define('DB_USER', 'DBuser');			# SQL server host name
define('DB_PASS', 'password');		# password
define('DB_NAME', 'inventory');		# database name

// LDAP server
define('LDAP_HOST', 'your_host');						# LDAP host
define('LDAP_DN', 'CN=Users,DC=company,DC=local');								# LDAP dn
define('LDAP_USR_DOM', '@company.local');	# LDAP user domain
?>