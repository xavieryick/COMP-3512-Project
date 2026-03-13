let main = document.querySelector("#cdetails");

let urlParams = new URLSearchParams(window.location.search);
let loc = urlParams.get("country");

let button = main.querySelector("#back");
button.addEventListener("click", () => {
  window.history.back();
});

fetch("api/getCountryInfo")
  .then((response) => response.json())
  .then((information) => {
    let correctCountry = information.find((country) => country.ISO === loc);

    let h2 = document.createElement("h2");
    h2.classList.add("padded");
    let name = correctCountry?.CountryName || "N/A";
    h2.append(name);

    fetch("https://flagcdn.com/en/codes.json")
      .then((response) => response.json())
      .then((information) => {
        let flagURL = `https://flagcdn.com/w80/${loc.toLowerCase()}.png`;

        let flag = document.createElement("img");
        flag.src = flagURL;
        flag.alt = loc;

        let flagDiv = document.createElement("div");
        flagDiv.classList.add("padded");
        flagDiv.append(flag);

        let pPop = document.createElement("p");
        pPop.classList.add("padded");
        let pop = correctCountry?.Population || "N/A";
        pPop.append(`Population: ${pop}`);

        let pCap = document.createElement("p");
        pCap.classList.add("padded");
        let cap = correctCountry?.Capital || "N/A";
        pCap.append(`Capital: ${cap}`);

        let pCurr = document.createElement("p");
        pCurr.classList.add("padded");
        let currName = correctCountry?.CurrencyName || "N/A";
        let currCode = correctCountry?.CurrencyCode || "N/A";
        pCurr.append(`Currency: ${currName} (${currCode})`);

        let pLang = document.createElement("p");
        pLang.classList.add("padded");
        let lang = correctCountry?.Languages || "N/A";
        pLang.append(`Language(s): ${lang}`);

        let pNeigh = document.createElement("p");
        pNeigh.classList.add("padded");
        let neigh = correctCountry?.Neighbours || "N/A";
        pNeigh.append(`Neighbour(s): ${neigh}`);

        let pDesc = document.createElement("p");
        pDesc.classList.add("padded");
        let desc = correctCountry?.CountryDescription || "N/A";
        pDesc.append(`Description: ${desc}`);

        let info = main.querySelector("#info");
        info.append(h2, flagDiv, pPop, pCap, pCurr, pLang, pNeigh, pDesc);
      });
  });
