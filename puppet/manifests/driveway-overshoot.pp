# Puppet configurations

Exec
{
	path => [ "/bin/", "/sbin/" , "/usr/bin/", "/usr/sbin/" ]
}

class base
{
	## Update apt-get ##
	exec
	{
		'apt-get update':
		command => '/usr/bin/apt-get update'
	}
}

class php
{
	package
	{
		"php5":
		ensure => present,
	}

	package
	{
		"php5-cli":
		ensure => present,
	}

	package
	{
		"php5-xdebug":
		ensure => present,
	}

	package
	{
		"php5-dev":
		ensure => present,
	}
}

# Inspired here:
# http://johnstonianera.com/install-composer-with-puppet-and-vagrant/
# https://git.drutch.nl/drupal-contrib/puppet-drush/commit/cb08aa6784104fc60db9976e8d5e830899e28ec0
# https://github.com/pigeontech/cptserver/blob/master/config/puppet/modules/composer/manifests/init.pp
# https://www.drupal.org/node/2253969
# http://askubuntu.com/questions/344687/how-to-set-environment-variable-before-running-script-inside-hooks-install

class composer
{
	package
	{
		"curl":
		ensure => installed,
	}

	# Download and add to path
	exec
	{
		"composer":
		command => "curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer",
		environment => "COMPOSER_HOME=/home/vagrant",
		require => Package[ 'curl' ]
	}

	# Add composer/vendor/bin to path
	file
	{
		"/etc/profile.d/composer.sh":
		mode => 0644,
		content => 'export PATH="~/.composer/vendor/bin:$PATH"',
		require => Exec[ 'composer' ]
	}
}

include base
include php
include composer
