import { Endpoint } from "./endpoint.js";
const endpoint = new Endpoint();

let input = document.querySelector("#input");
let results = document.querySelector("#results");
// let button = document.querySelector("button");

input.addEventListener("input", find);
results.addEventListener("click", (event) => {
  if (event.target.tagName === "INPUT" || event.target.tagName === "LABEL") {
    let location = document.querySelector('input[name="location"]:checked');
    let destination = endpoint.getLocation(location.id);
    window.location.href = `matches?location=${destination}`;
  }
});

// takes a query and searches for matches beginning with query
async function find() {
  let query = input.value.toLowerCase();
  if (query.length < 2) {
    results.textContent = "";
    return;
  }

  let stored = sessionStorage.getItem(query);
  if (stored) {
    results.textContent = "";
    let converted = JSON.parse(stored);
    await converted.forEach((location) => {
      let li = document.createElement("li");

      let butt = document.createElement("input");
      butt.type = "radio";
      butt.id = location.name;
      butt.name = "location";
      butt.classList.add("radio");

      let label = document.createElement("label");
      label.htmlFor = butt.id;
      label.textContent = ` ${location.name} (${location.count})`;

      li.appendChild(butt);
      li.appendChild(label);
      results.appendChild(li);
    });
  } else {
    let response = await fetch("api/getLocations");
    let locations = await response.json();
    const filtered = await locations.filter((item) => {
      return item.name.toLowerCase().startsWith(query);
    });

    results.textContent = "";

    await filtered.forEach((location) => {
      let li = document.createElement("li");

      let butt = document.createElement("input");
      butt.type = "radio";
      butt.id = location.name;
      butt.name = "location";
      butt.classList.add("radio");

      let label = document.createElement("label");
      label.htmlFor = butt.id;
      label.textContent = ` ${location.name} (${location.count})`;

      li.appendChild(butt);
      li.appendChild(label);
      results.appendChild(li);
    });
    await sessionStorage.setItem(query, JSON.stringify(filtered));
  }
}
