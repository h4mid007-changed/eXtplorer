#!/bin/sh
# ----------------------------------------------------------------------------
#
# Component Install Archive Builder
#
# This file is part of eXtplorer
#
# ----------------------------------------------------------------------------

# YOU MUST HAVE INSTALLED THE 4.x BETA VERSION OF p7zip (the command line version of 7-Zip for Unix/Linux).
# It's usually globally accessible (in the directory /usr/local/bin/)

DATE=$(date +%Y%m%d)
PATH='/home/soeren/Joomla/components/extplorer'
cd $PATH

/usr/local/lib/p7zip/7za a -tzip -r $PATH/scripts.zip scripts
/usr/local/lib/p7zip/7za d -r $PATH/scripts.zip .svn/

/usr/local/lib/p7zip/7za a -tzip -r $PATH/com_extplorer.zip
/usr/local/lib/p7zip/7za d -r $PATH/com_extplorer.zip .svn/
/usr/local/lib/p7zip/7za d $PATH/com_extplorer.zip archive
/usr/local/lib/p7zip/7za d $PATH/com_extplorer.zip scripts/

/usr/local/lib/p7zip/7za d -r $PATH/com_extplorer.zip build_component.sh build_component.bat .project .projectOptions .cache preinstall.php README_PREINSTALL.txt

/bin/rm $PATH/scripts.zip