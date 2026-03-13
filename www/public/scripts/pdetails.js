let main = document.querySelector("#pdetails");

let urlParams = new URLSearchParams(window.location.search);
let pid = Number(urlParams.get("imageid"));

let button = main.querySelector("#back");
button.addEventListener("click", () => {
  window.history.back();
});

fetch("api/getPhotoInfo")
  .then((response) => response.json())
  .then((information) => {
    let correctPhoto = information.find((photo) => photo.ImageID === pid);

    let photo = document.createElement("img");
    photo.src = `https://res.cloudinary.com/dg0durrxj/image/upload/w_250,h_250,c_fill/${correctPhoto.Path}`;
    photo.alt = correctPhoto.ImageID;
    photo.classList.add("padded");

    let desc = document.createElement("p");
    desc.textContent = `Description: ${correctPhoto.Description}`;
    desc.classList.add("padded");

    let exif = document.createElement("p");
    exif.textContent = `Exif: `;

    if (correctPhoto.Exif !== "" && correctPhoto.Exif !== null) {
      let exifData = JSON.parse(correctPhoto.Exif);

      let make = document.createElement("li");
      make.textContent = `Make: ${exifData.make}`;
      let model = document.createElement("li");
      model.textContent = `Model: ${exifData.model}`;
      let exposureTime = document.createElement("li");
      exposureTime.textContent = `Exposure Time: ${exifData.exposure_time}`;
      let aperature = document.createElement("li");
      aperature.textContent = `Aperature: ${exifData.aperture}`;
      let focalLength = document.createElement("li");
      focalLength.textContent = `Focal Length: ${exifData.focal_length}`;
      let iso = document.createElement("li");
      iso.textContent = `ISO: ${exifData.iso}`;

      let exifdetails = document.createElement("ul");
      exifdetails.append(
        make,
        model,
        exposureTime,
        aperature,
        focalLength,
        iso,
      );

      exif.append(exifdetails);
      exif.classList.add("padded");
    } else {
      exif.append(`N/A`);
      exif.classList.add("padded");
    }

    let creator = document.createElement("p");
    creator.textContent = `Creator: ${correctPhoto.ActualCreator}`;
    creator.classList.add("padded");

    let rating = document.createElement("p");
    rating.textContent = `Average Rating: ${correctPhoto.averageRating}`;
    rating.classList.add("padded");

    let city = document.createElement("p");
    city.textContent = `City: ${correctPhoto.AsciiName}`;
    city.classList.add("padded");

    let latLong = document.createElement("p");
    latLong.textContent = `Latitutde/Longitude: ${correctPhoto.Latitude}, ${correctPhoto.Longitude}`;
    latLong.classList.add("padded");

    fetch(
      `https://maps.googleapis.com/maps/api/staticmap?center=${correctPhoto.Latitude},${correctPhoto.Longitude}&zoom=15&size=200x200&key=AIzaSyAQ6HDQrndIPK8JfOuHM82e42BObvAJgh4`,
    ).then((information) => {
      let map = document.createElement("img");
      map.src = information.url;
      map.alt = `${correctPhoto.Latitude}, ${correctPhoto.Longitude} on Google Maps`;

      let info = main.querySelector("#info");
      info.append(photo, desc, exif, creator, rating, city, latLong, map);
    });
  });
