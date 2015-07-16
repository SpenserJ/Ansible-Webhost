# Ansible Webhost

This package contains an Ansible playbook for configuring a Debian host with a combination of Nginx, PHP, and MySQL. The host will have UFW/IPTables running, and websites can be deployed via Git automatically.

## Requirements

* Root (or user with sudo) permissions

## To Do

* We cannot provision a fresh machine **with** our user so we must connect as vagrant, and we cannot clone websites **without** our user. How do we connect as one for the first role, and another for the remainder?
* Customizable website configs for Nginx/PHP, via vars
* Develop/test MySQL Master/Slave configuration and replication
* Set up a root MySQL password for each server, instead of reusing a single password
* [Potentially Solved] - Deploy SSL certificate for Nginx/Haproxy when deploying a site with SSL
* Hostname is not set correctly on Ubuntu servers - Can't resolve itself
* Enable HSTS in Nginx and HAProxy for SSL enabled websites

## Instructions

* Copy `hosts.example` to `hosts`, and configure it with your servers
* Edit group_vars/production and group_vars/development, and add any administrative users or management IPs that you will use. Root access will be disabled, so be sure you have a working account configured
* Add a public key for every administrative user into roles/ssh/files/keys/[username].pub
* The first time you deploy the webserver with this script, use the command:
  * `ansible-playbook main.yml -e "ansible_ssh_user=root default_password=ThisIsTheDefaultPassword" --ask-pass`
  * This will create the user accounts, and then fail to process the remaining roles. This is intentional, as we disable the root user.
* After the initial user configuration, you can use:
  * `ansible-playbook main.yml --ask-sudo-pass`
* Currently, installing a production server will require changing the port Nginx is on. This causes a minor race condition, that you can solve with the following:
  * `sudo service nginx restart && sudo service haproxy restart && sudo service varnish restart`
