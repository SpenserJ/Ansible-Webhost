# Ansible Webhost

This package contains an Ansible playbook for configuring a Debian host with a combination of Nginx, PHP, and MySQL. The host will have UFW/IPTables running, and websites can be deployed via Git automatically.

## Requirements

* Ubuntu 14.04 LTS server
* Root (or user with sudo) permissions

## To Do

* Set up a root MySQL password for each server, instead of reusing a single password
* [Potentially Solved] - Deploy SSL certificate for Nginx/Haproxy when deploying a site with SSL
* Hostname is not set correctly on Ubuntu servers - Can't resolve itself
* Enable HSTS in Nginx and HAProxy for SSL enabled websites

## Instructions

* Install [geerlingguy/ansible-role-mailhog](https://github.com/geerlingguy/ansible-role-mailhog) via Ansible Galaxy:
  * `ansible-galaxy install geerlingguy.mailhog`
* Copy `inventory/example.config.php` to `inventory/config.php`, and configure it with your servers
* Add a public key for every administrative user into roles/ssh/files/keys/[username].pub
  * Root access and Password Authentication will be disabled, so be sure this is a working public key or you may lock yourself out.
* The first time you deploy the webserver with this script, use the command:
  * `ansible-playbook main.yml -e "ansible_ssh_user=root default_password=ThisIsTheDefaultPassword" --ask-pass`
  * This will create the user accounts, and then fail to process the remaining roles. This is intentional, as we disable the root user.
* After the initial user configuration, you can use:
  * `ansible-playbook main.yml --ask-sudo-pass`
