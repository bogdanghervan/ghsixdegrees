# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
  config.vm.box = "ubuntu/xenial64"
  config.vm.network "private_network", ip: "192.168.120.10"
  config.vm.synced_folder "./", "/var/www/ghsixdegrees", create: true, group: "www-data", owner: "www-data"
  config.vm.provider "virtualbox" do |vb|
    vb.memory = "768"
    vb.gui = true
    vb.name = "Six Degrees"
  end
end
