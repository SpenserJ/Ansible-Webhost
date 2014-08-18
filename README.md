# Ansible Webhost

This package contains an Ansible playbook for configuring a Debian or CentOS host with a combination of Apache, PHP, and MySQL. The host will have UFW/IPTables running, and websites can be deployed via Git automatically.

## To Do

* We cannot provision a fresh machine **with** our user so we must connect as vagrant, and we cannot clone websites **without** our user. How do we connect as one for the first role, and another for the remainder?
* Customizable website configs for Apache, via vars
* Develop/test MySQL Master/Slave configuration and replication
