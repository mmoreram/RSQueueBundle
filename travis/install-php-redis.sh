#!/bin/bash
#
# @copyright (c) 2013 phpBB Group
# @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
#

# redis
git clone git://github.com/nicolasff/phpredis.git
cd phpredis
phpize
./configure
make
sudo make install
echo "extension=redis.so" > /etc/php5/conf.d/redis.ini