websites: {
  example.com: {
    domain: example.com,
    git: { repo: "git@github.com:example.com/example.git" },
    ssl: true,
    type: 'drupal7'
  }
}
mysql_master: true
administrators: [ 'spenser' ]
