#!\bin\sh
# ----------------------------------------------------------------------------
#
# Component Install Archive Builder
#
# This file is part of joomlaXplorer
#
# ----------------------------------------------------------------------------

# YOU MUST HAVE INSTALLED THE 4.x BETA VERSION OF p7zip (the command line version of 7-Zip for Unix\Linux).
# It's usually globally accessible (in the directory \usr\local\bin\)


set PATH=C:\DOWNLOADS\Joomla\components\joomlaXplorer

cd %PATH%

C:\Programme\7-Zip\7z.exe a -tzip -r %PATH%\scripts.zip scripts
C:\Programme\7-Zip\7z.exe d -r %PATH%\scripts.zip .svn\

C:\Programme\7-Zip\7z.exe a -tzip -r %PATH%\com_joomlaxplorer.zip
C:\Programme\7-Zip\7z.exe d -r %PATH%\com_joomlaxplorer.zip .svn\
C:\Programme\7-Zip\7z.exe d %PATH%\com_joomlaxplorer.zip scripts\

C:\Programme\7-Zip\7z.exe d -r %PATH%\com_joomlaxplorer.zip build_component.sh build_component.bat .project .projectOptions .cache

del %PATH%\scripts.zip