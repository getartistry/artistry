#!/bin/bash

export PATH="../vendor/bin:$PATH"

# Get the file path
if [ "x$1" == "x" ] || [ "x$2" == "x" ] || [ "x$3" == "x" ] || [ "x$4" == "x" ] || [ "x$5" == "x" ] || [ "x$6" == "x" ]
then
    echo "Usage $0 site-install-dir site-url db_name db_username db_pass plugin_dir"
    exit 255
else
    install_dir=$1
    install_url=$2
    db_name=$3
    db_user=$4
    db_pass=$5
    plugin_dir=$6
fi

plugin_slug=`basename $plugin_dir`

# Remove trailing slash on install path
install_dir=`echo $install_dir | sed -e 's/\/$//g'`

# Make the install directory if it doesn't exist.
if [ ! -d $install_dir ]
then
	mkdir $install_dir || exit 255
fi

cd $install_dir
# Install WordPress
wp core download 2> /dev/null
wp core config --dbname=$db_name --dbuser=$db_user --dbpass=$db_pass
wp db drop --yes
wp db create
wp core install --title="Test site" --admin_user="testadmin" --admin_password="testadmin" --admin_email="test@example.com" --url="$install_url"

# Install WooCommerce
wp plugin delete woocommerce
wp plugin install woocommerce
wp plugin activate woocommerce

# Install WooCommerce sample data
wp plugin delete wordpress-importer
wp plugin install wordpress-importer
wp plugin activate wordpress-importer
wp import --authors="create" $install_dir/wp-content/plugins/woocommerce/dummy-data/dummy-data.xml

# FIXME - auto-symlink current plugin
echo "ln -s $plugin_dir wp-content/plugins/"
ln -s $plugin_dir wp-content/plugins/

wp plugin activate woocommerce-product-feeds
exit 0
