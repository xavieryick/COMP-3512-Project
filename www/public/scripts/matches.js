let main = document.querySelector("#matches");
let results = main.querySelector("#results");
let toggle = main.querySelector("#toggleView");

let button = main.querySelector("#back");
button.addEventListener("click", () => {
  window.location.href = "/";
});

let photos;

initializePhotos();

async function initializePhotos() {
  photos = await find();
  sorting();
}

function sorting() {
  results.textContent = "";
  if (sortCityA.classList.contains("sorting")) {
    sortByCity(photos, "A");
    createFigures(photos);
  } else if (sortCityD.classList.contains("sorting")) {
    sortByCity(photos, "D");
    createFigures(photos);
  } else if (sortCountryA.classList.contains("sorting")) {
    sortByCountry(photos, "A");
    createFigures(photos);
  } else if (sortCountryD.classList.contains("sorting")) {
    sortByCountry(photos, "D");
    createFigures(photos);
  }
}

function redirect(imageid) {
  window.location.href = `photo?imageid=${imageid}`;
}

results.addEventListener("click", (event) => {
  if (event.target.tagName === "IMG") {
    redirect(event.target.alt);
  }
});

async function find() {
  let urlParams = new URLSearchParams(window.location.search);
  let desiredLocation = urlParams.get("location");
  let response = await fetch("api/getMatches");
  let photos = await response.json();
  let filtered = filterPhotos(photos, desiredLocation);
  createFigures(filtered);

  let respAvail = await fetch("api/getAvailable");
  let countAvail = await respAvail.json();
  let avail = await countAvail.find(
    (index) => index.location === desiredLocation,
  );

  let respTotal = await fetch("api/getTotal");
  let countTotal = await respTotal.json();
  let total = await countTotal.find(
    (index) => index.location === desiredLocation,
  );

  let h2 = main.querySelector("h2");
  h2.textContent = `${desiredLocation} (${avail.count}/${total.count} Photos)`;

  return filtered;
}

function filterPhotos(array, location) {
  const filtered = array.filter((item) => {
    return (
      item.CountryName.includes(location) ||
      item.AsciiName.includes(location) ||
      item.ContinentName.includes(location)
    );
  });
  return filtered;
}

async function getFlagged() {
  let fResponse = await fetch("api/getFlagged");
  let flagged = await fResponse.json();
  return flagged;
}

async function createFigures(array) {
  let flagged = await getFlagged();
  array.forEach((photo) => {
    let fig = document.createElement("figure");

    let img = document.createElement("img");
    img.src = `https://res.cloudinary.com/dg0durrxj/image/upload/w_200,h_200,c_fill/${photo.Path}`;
    img.alt = `${photo.ImageID}`;

    let flaggedStatus = flagged.find(
      (flaggedphoto) => flaggedphoto.imageid === photo.ImageID,
    );

    if (flaggedStatus !== undefined) {
      img.classList.add("flagged");
    }

    let a = document.createElement("a");
    a.href = `details?country=${photo.CountryCodeISO}`;
    a.textContent = `${photo.CountryName}`;

    let p = document.createElement("p");

    let cap = document.createElement("figcaption");

    p.appendChild(a);
    let pText = document.createTextNode(
      `, ${photo.AsciiName}, by ${photo.FirstName} ${photo.LastName}`,
    );
    p.appendChild(pText);

    cap.append(p);
    fig.append(img);
    fig.appendChild(cap);
    results.appendChild(fig);
  });
}

function sortByCity(array, method) {
  if (method === "A") {
    return array.sort((a, b) => {
      if (a.AsciiName.localeCompare(b.AsciiName) === 0)
        return a.CountryName.localeCompare(b.CountryName);
      return a.AsciiName.localeCompare(b.AsciiName);
    });
  } else {
    return array.sort((a, b) => {
      if (b.AsciiName.localeCompare(a.AsciiName) === 0)
        return b.CountryName.localeCompare(a.CountryName);
      return b.AsciiName.localeCompare(a.AsciiName);
    });
  }
}

function sortByCountry(array, method) {
  if (method === "A") {
    return array.sort((a, b) => {
      if (a.CountryName.localeCompare(b.CountryName) === 0)
        return a.AsciiName.localeCompare(b.AsciiName);
      return a.CountryName.localeCompare(b.CountryName);
    });
  } else {
    return array.sort((a, b) => {
      if (b.CountryName.localeCompare(a.CountryName) === 0)
        return b.AsciiName.localeCompare(a.AsciiName);
      return b.CountryName.localeCompare(a.CountryName);
    });
  }
}

toggle.addEventListener("click", () => {
  results.classList.toggle("resultsGrid");
  results.classList.toggle("resultsList");

  if (results.classList.contains("resultsGrid")) {
    toggle.textContent = "Switch to List View";
  }
  if (results.classList.contains("resultsList")) {
    toggle.textContent = "Switch to Grid View";
  }
});

let sortCityA = main.querySelector("#sortCityA");
sortCityA.addEventListener("click", () => {
  sortCityA.classList.add("sorting");
  sortCityD.classList.remove("sorting");
  sortCountryA.classList.remove("sorting");
  sortCountryD.classList.remove("sorting");
  sorting();
});
let sortCityD = main.querySelector("#sortCityD");
sortCityD.addEventListener("click", () => {
  sortCityA.classList.remove("sorting");
  sortCityD.classList.add("sorting");
  sortCountryA.classList.remove("sorting");
  sortCountryD.classList.remove("sorting");
  sorting();
});
let sortCountryA = main.querySelector("#sortCountryA");
sortCountryA.addEventListener("click", () => {
  sortCityA.classList.remove("sorting");
  sortCityD.classList.remove("sorting");
  sortCountryA.classList.add("sorting");
  sortCountryD.classList.remove("sorting");
  sorting();
});
let sortCountryD = main.querySelector("#sortCountryD");
sortCountryD.addEventListener("click", () => {
  sortCityA.classList.remove("sorting");
  sortCityD.classList.remove("sorting");
  sortCountryA.classList.remove("sorting");
  sortCountryD.classList.add("sorting");
  sorting();
});
