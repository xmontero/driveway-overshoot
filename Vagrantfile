# Inspired here:
# https://github.com/patrickdlee/vagrant-examples/blob/master/example3/Vagrantfile
# https://github.com/sapienza/vagrant-php-box/blob/master/Vagrantfile

Vagrant.configure("2") do |config|
  # All Vagrant configuration is done here. The most common configuration
  # options are documented and commented below. For a complete reference,
  # please see the online documentation at vagrantup.com.

  # Every Vagrant virtual environment requires a box to build off of.
  config.vm.box = "ubuntu/trusty64"
  config.vm.hostname = "driveway-overshoot-model-ddd-with-symfony"

  config.vm.provision :puppet do |puppet|
    puppet.manifests_path = "puppet/manifests"
    puppet.manifest_file = "driveway-overshoot.pp"
  end

  config.vm.network :private_network, ip: "172.16.111.11"
end
