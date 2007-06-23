#!/bin/sh
# ----------------------------------------------------------------------------
#
# Component Install Archive Builder
#
# This file is part of joomlaXplorer
#
# ----------------------------------------------------------------------------

# YOU MUST HAVE INSTALLED THE 4.x BETA VERSION OF p7zip (the command line version of 7-Zip for Unix/Linux).
# It's usually globally accessible (in the directory /usr/local/bin/)

DATE=$(date +%Y%m%d)
PATH='/home/soeren/Joomla/components/com_joomlaxplorer'
cd $PATH

/usr/local/lib/p7zip/7za a -tzip -r $PATH/scripts.zip scripts
/usr/local/lib/p7zip/7za d -r $PATH/scripts.zip .svn/

/usr/local/lib/p7zip/7za a -tzip -r $PATH/com_joomlaxplorer.zip
/usr/local/lib/p7zip/7za d -r $PATH/com_joomlaxplorer.zip .svn/
/usr/local/lib/p7zip/7za d $PATH/com_joomlaxplorer.zip scripts/

/usr/local/lib/p7zip/7za d -r $PATH/com_joomlaxplorer.zip build_component.sh build_component.bat .project .projectOptions .cache

/bin/rm $PATH/scripts.zip