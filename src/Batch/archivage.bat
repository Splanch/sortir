@echo off

cd C:\wamp64\www\sortir
php bin/console app:changement-etat
set /p nom= Quel est votre nom ?