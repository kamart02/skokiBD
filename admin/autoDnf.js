var autoDNF = (id) => {
    console.log("autoDNF(" + id + ")");

    cheker = document.getElementById("dyskwalifikacja" + id);
    if (cheker.checked) {
        this.dlugosc = document.getElementById("dlugosc" + id).value;
        document.getElementById("dlugosc" + id).type = "text";
        document.getElementById("dlugosc" + id).value = "DNF";
        document.getElementById("dlugosc" + id).disabled = true;
        this.ocena = document.getElementById("ocena" + id).value;
        document.getElementById("ocena" + id).value = 0.0;
        document.getElementById("ocena" + id).disabled = true;
    } else {
        document.getElementById("dlugosc" + id).type = "number";
        if (this.dlugosc == -1) {
            document.getElementById("dlugosc" + id).value = "";
        } else {
            document.getElementById("dlugosc" + id).value = this.dlugosc;
        }
        document.getElementById("dlugosc" + id).disabled = false;
        document.getElementById("ocena" + id).disabled = false;
        document.getElementById("ocena" + id).value = this.ocena;

    }
};

liczbaSkokow = document.getElementById("liczbaSkokow").value;

for (i = 0; i < liczbaSkokow; i++) {
    var autoDNFWithValues = autoDNF.bind(null, i);
    autoDNFWithValues.dlugosc = -1;
    autoDNFWithValues.ocena = 0.0;
    cheker = document.getElementById("dyskwalifikacja" + i);
    if (cheker.checked) {
        document.getElementById("dlugosc" + i).type = "text";
        document.getElementById("dlugosc" + i).value = "DNF";
        document.getElementById("dlugosc" + i).disabled = true;
        document.getElementById("ocena" + i).value = 0.0;
        document.getElementById("ocena" + i).disabled = true;
    }
    document.getElementById("dyskwalifikacja" + i).addEventListener("click", autoDNF.bind(null, i));
}

console.log("autoDNF.js loaded");