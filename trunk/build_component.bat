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


set PATH=C:\DOWNLOADS\Joomla\components\joomlaXplorer

cd %PATH%

C:\Programme\7-Zip\7z.exe a -ttar -r %PATH%\com_joomlaxplorer.tar
C:\Programme\7-Zip\7z.exe d -r %PATH%\com_joomlaxplorer.tar .svn/

C:\Programme\7-Zip\7z.exe d -r %PATH%\com_joomlaxplorer.tar build_component.sh build_component.bat
C:\Programme\7-Zip\7z.exe a -tgzip %PATH%\com_joomlaxplorer.tar.gz %PATH%\com_joomlaxplorer.tar

del %PATH%\com_joomlaxplorer.tar
