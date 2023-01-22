DROP TABLE Uzytkownik CASCADE;
DROP TABLE Seria CASCADE;
DROP TABLE Konkurs CASCADE;
DROP TABLE Uczestnik CASCADE;
DROP TABLE Reprezentacja CASCADE;
DROP TABLE Zgloszenie CASCADE;
DROP TABLE Skok CASCADE;
DROP FUNCTION istniejeKonkurs(integer);
DROP FUNCTION istniejeLogin(varchar);
DROP FUNCTION czyZgloszony(integer, integer);
DROP TYPE typSerii;
DROP TRIGGER sprawdzLiczbeZgloszen ON Konkurs;
DROP FUNCTION sprawdzLiczbeZgloszen();