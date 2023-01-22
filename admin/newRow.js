var addRow = () => {
    var formTable = document.getElementById("formTable");
    var maxEntry = document.getElementById("maxEntry").value;
    maxEntry = parseInt(maxEntry);

    var tr = document.createElement("tr");

    var tdkraj = document.createElement("td");
    var krajInput = document.createElement("input");
    krajInput.setAttribute("type", "text");
    krajInput.setAttribute("name", "kraj" + maxEntry);
    krajInput.required = true;
    tdkraj.appendChild(krajInput);
    tr.appendChild(tdkraj);

    var kwotaInput = document.createElement("input");
    var tdkwota = document.createElement("td");
    kwotaInput.setAttribute("type", "number");
    kwotaInput.setAttribute("name", "kwotaStartowa" + maxEntry);
    kwotaInput.setAttribute("min", "1");
    kwotaInput.required = true;
    tdkwota.appendChild(kwotaInput);
    tr.appendChild(tdkwota);

    var hiddenInput = document.createElement("input");
    hiddenInput.setAttribute("type", "hidden");
    hiddenInput.setAttribute("name", "idReprezentacji" + maxEntry);
    hiddenInput.setAttribute("value", "");
    tr.appendChild(hiddenInput);

    formTable.appendChild(tr);

    maxEntry++;
    document.getElementById("maxEntry").value = maxEntry;
}

document.getElementById("newRep").addEventListener("click", addRow);