<?php
/**
 * Config file
 *********************************************************/

// DB login info

define('DB_HOST', 'localhost');		# SQL server host name
define('DB_USER', 'leltar_USER');	# SQL user name
define('DB_PASS', 'leltar');			# password
define('DB_NAME', 'inventory');		# database name

// LDAP server
define('LDAP_HOST', '192.168.10.1');								# LDAP host
define('LDAP_DN', 'CN=Users,DC=nangenex,DC=local');	# LDAP dn
define('LDAP_USR_DOM', '@nangenex.local');					# LDAP user domain
?>