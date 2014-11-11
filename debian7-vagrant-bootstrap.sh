#!/bin/bash
export DEBIAN_FRONTEND=noninteractive

# become root and go to root home
sudo su
cd ~

apt-get update -q
apt-get dist-upgrade -q -y --force-yes

# Install git, vim, PHP
apt-get install -q -y --force-yes \
	git vim \
	couchdb \
	php5-fpm nginx \
	phpunit php5-xdebug php5-curl

cat >> /etc/nginx/sites-available/default << 'EOF'
server {
	listen   80;

	root /vagrant;
	index index.php;

	# Make site accessible from http://localhost/
	server_name _;

	autoindex on;

	location /webgrind {
		root /home/vagrant;
		index index.php;

		# pass the PHP scripts to FastCGI server listening on /tmp/php5-fpm.sock
		location ~ \.php$ {
			fastcgi_pass  unix:/var/run/php5-fpm.sock;
			fastcgi_index index.php;

			include fastcgi_params;

			fastcgi_split_path_info       ^(.+\.php)(/.+)$;
			fastcgi_param PATH_INFO       $fastcgi_path_info;
			fastcgi_param PATH_TRANSLATED $document_root$fastcgi_path_info;
			fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
		}
	}
}
EOF

# CouchDB bind to 0.0.0.0
sed -i 's/^bind_address.*/bind_address = 0.0.0.0/g' /etc/couchdb/default.ini

# Enable xdebug
echo "xdebug.profiler_enable" > /etc/php5/mods-available/xdebug.ini

# Clone webgrind
git clone https://github.com/jokkedk/webgrind.git /home/vagrant/webgrind

service couchdb restart
service php5-fpm restart
service nginx restart
