#!/usr/bin/env node
var prompt = require('prompt')
  , sys = require('sys')
  , exec = require('child_process').exec
  , spawn = require('child_process').spawn
  , fork = require('child_process').fork
  , temp = require('temp')
  , fs = require('fs');

//temp.track();
prompt.start();

var schema = {
  properties: {
    email: {
      pattern: /^.*@simplesimple\.ca$/,
      message: 'Email must be @simplesimple.ca',
      required: true,
    },
    password: {
      hidden: true,
      required: true,
    },
  },
};

prompt.get(schema, function (err, result) {
  if (err) { console.log(err); return; }
  result.username = result.email.match(/^(.*)@simplesimple\.ca$/)[1];
  result.hostname = 'dev-' + result.username;
  result.client_ip = process.env.SSH_CLIENT.split(' ')[0];
  setup_playbook(result);
});

var setup_playbook = function setup_playbook(settings) {
  temp.open('ansible-bootstrap', function (err, playbook) {
    fs.write(playbook.fd, settings.client_ip +
      ' hostname=' + settings.hostname +
      ' email=' + settings.email +
      ' username=' + settings.username);
    fs.close(playbook.fd, function (err) {
      run_playbook(playbook.path, settings);
    });
  });
};

var run_playbook = function run_playbook(inventory_file, settings) {
  var known_hosts = fs.openSync('/home/ansible-bootstrap/.ssh/known_hosts', 'a');
  spawn('ssh-keyscan', ['-H', settings.client_ip], { stdio: [ 'ignore', known_hosts, 'ignore' ]})
    .on('close', function (code) {
      spawn('ansible-playbook', ['-i', inventory_file, '--extra-vars', '"password=$(openssl\\ passwd\\ -1\\ -salt\\ $(openssl\\ rand\\ -base64\\ 6)\\ ' + settings.password + ')"', '-u', 'vagrant', '/etc/ansible/bootstrap.yml'], { stdio: 'inherit' })
        .on('close', function (code) {
          var hosts = fs.readFileSync('/etc/ansible/hosts', { encoding: 'utf-8' });
          if ((new RegExp('\\[devs\\](?:\\n.+)*?\\n' + settings.hostname)).test(hosts) === false) {
            exec('sudo sed -i "s/\\(\\[devs\\]\\)/\\1\\n' + settings.hostname + '/" /etc/ansible/hosts', function (err, stdout, stderr) {
              console.log('\nTo SSH to the server, please use `ssh ' + settings.username + '@' + settings.hostname + '`\n\n');
            });
          } else {
            console.log('\nTo SSH to the server, please use `ssh ' + settings.username + '@' + settings.hostname + '`\n\n');
          }
      //exec('./cleanup.sh ' + settings.hostname);
        });
    });
};
