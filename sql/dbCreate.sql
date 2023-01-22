CREATE TYPE TypSerii 
AS ENUM ('kwalifikacyjna', 'pierwsza', 'druga');

CREATE TABLE Uzytkownik (
    nazwa VARCHAR(64) PRIMARY KEY,
    hash_hasla VARCHAR(255) NOT NULL,
    administrator BOOLEAN NOT NULL DEFAULT false
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
    zamknieteZgloszenia BOOLEAN NOT NULL,
    UNIQUE (seriaKwalifikacyjna), UNIQUE (seriaPierwsza), UNIQUE (seriaDruga)
);

CREATE TABLE Uczestnik (
    idUczestnika SERIAL PRIMARY KEY,
    nazwa VARCHAR(64) REFERENCES Uzytkownik,
    imie VARCHAR(255) NOT NULL,
    nazwisko VARCHAR(255) NOT NULL,
    UNIQUE (nazwa)
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
    idUczestnika INT REFERENCES Uczestnik NOT NULL,
    UNIQUE (idReprezentacji, idUczestnika)
);

CREATE TABLE Skok (
    idSkoku SERIAL PRIMARY KEY,
    idZgloszenia INT REFERENCES Zgloszenie NOT NULL,
    idSerii INT REFERENCES Seria NOT NULL,
    dlugosc NUMERIC(10, 1),
    ocena NUMERIC(10, 1),
    dyskwalifikacja BOOLEAN,
    numerStartowy INT NOT NULL,
    UNIQUE (idZgloszenia, idSerii)
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

CREATE FUNCTION istniejeLogin(username varchar) RETURNS boolean AS $$
    BEGIN
        IF EXISTS (SELECT * FROM Uzytkownik WHERE nazwa = username) THEN
            RETURN true;
        ELSE
            RETURN false;
        END IF;
    END;
$$ LANGUAGE plpgsql;

CREATE FUNCTION czyZgloszony(idUcz integer, idKon integer) RETURNS boolean AS $$
    BEGIN
        IF EXISTS (SELECT * FROM (SElECT * FROM Zgloszenie JOIN Reprezentacja ON Zgloszenie.idReprezentacji = Reprezentacja.idReprezentacji) x WHERE idUczestnika = idUcz AND idKonkursu=idKon) THEN
            RETURN true;
        ELSE
            RETURN false;
        END IF;
    END;
$$ LANGUAGE plpgsql;

CREATE FUNCTION sprawdzLiczbeZgloszen() RETURNS TRIGGER AS $$
    BEGIN
        IF (SELECT COUNT(*) FROM Zgloszenie WHERE idReprezentacji = NEW.idReprezentacji) > (SELECT kwotaStartowa FROM Reprezentacja WHERE idReprezentacji = NEW.idReprezentacji) THEN
            RETURN NULL;
        END IF;
        RETURN NEW;
    END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER sprawdzLiczbeZgloszen BEFORE INSERT ON Zgloszenie FOR EACH ROW EXECUTE PROCEDURE sprawdzLiczbeZgloszen();
