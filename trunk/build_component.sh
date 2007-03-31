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
/usr/local/lib/p7zip/7za a -ttar -r $PATH/com_joomlaxplorer.tar
/usr/local/lib/p7zip/7za d -r $PATH/com_joomlaxplorer.tar CVS/

/usr/local/lib/p7zip/7za d -r $PATH/com_joomlaxplorer.tar build_component.sh
/usr/local/lib/p7zip/7za a -tgzip $PATH/com_joomlaxplorer_$DATE.tar.gz $PATH/com_joomlaxplorer.tar

/bin/rm $PATH/com_joomlaxplorer.tar
