CREATE TYPE TypSerii 
AS ENUM ('kwalifikacyjna', 'pierwsza', 'druga');

CREATE TABLE Uzytkownik (
    nazwa VARCHAR(64) PRIMARY KEY,
    hash_hasla VARCHAR(255) NOT NULL
);

CREATE TABLE Seria (
    idSerii SERIAL PRIMARY KEY,
    typSerii TypSerii NOT NULL
);

CREATE TABLE Konkurs (
    idKonkursu SERIAL PRIMARY KEY,
    seriaKwalifikacyjna INT REFERENCES Seria,
    seriaPierwsza INT REFERENCES Seria,
    seriaDruga INT REFERENCES Seria,
    nazwa VARCHAR(64) NOT NULL,
    lokalizacja VARCHAR(64) NOT NULL,
    dataWydarzenia DATE NOT NULL,
    zamknieteZgloszenia BOOLEAN NOT NULL
);

CREATE TABLE Uczestnik (
    idUczestnika SERIAL PRIMARY KEY,
    imie VARCHAR(255) NOT NULL,
    nazwisko VARCHAR(255) NOT NULL
);

CREATE TABLE Reprezentacja (
    idReprezentacji SERIAL PRIMARY KEY,
    idKonkursu INT REFERENCES Konkurs NOT NULL,
    kraj VARCHAR(64) NOT NULL,
    kwotaStartowa INT NOT NULL
);

CREATE TABLE Zgloszenie (
    idZgloszenia SERIAL PRIMARY KEY,
    idReprezentacji INT REFERENCES Reprezentacja NOT NULL,
    idUczestnika INT REFERENCES Uczestnik NOT NULL
);

CREATE TABLE Skok (
    idSkoku SERIAL PRIMARY KEY,
    idZgloszenia INT REFERENCES Zgloszenie NOT NULL,
    idSerii INT REFERENCES Seria NOT NULL,
    dlugosc NUMERIC(10, 1),
    ocena NUMERIC(10, 1),
    dyskwalifikacja BOOLEAN
);

CREATE FUNCTION istniejeKonkurs(id integer) RETURNS boolean AS $$
    BEGIN
        IF EXISTS (SELECT * FROM Konkurs WHERE idKonkursu = id) THEN
            RETURN true;
        ELSE

            RETURN false;
        END IF;
    END;
$$ LANGUAGE plpgsql;