rem ----------------------------------------------------------------------------
rem
rem Component Install Archive Builder
rem
rem This file is part of joomlaXplorer
rem
rem ----------------------------------------------------------------------------

rem YOU MUST HAVE INSTALLED THE 4.x VERSION OF 7zip
rem Please update the program path here accordingly


set PATH=C:\DOWNLOADS\Joomla\components\joomlaxplorer_1.x

cd %PATH%

C:\Programme\7-Zip\7z.exe a -tzip -r %PATH%\com_joomlaxplorer.zip
C:\Programme\7-Zip\7z.exe d -r %PATH%\com_joomlaxplorer.zip .svn\
C:\Programme\7-Zip\7z.exe d %PATH%\com_joomlaxplorer.zip archive\

C:\Programme\7-Zip\7z.exe d -r %PATH%\com_joomlaxplorer.zip build_component.sh build_component.bat
