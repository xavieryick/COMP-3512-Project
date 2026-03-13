function Endpoint() {}

Endpoint.prototype.getLocation = function (location) {
  return `${location}`;
};

Endpoint.prototype.getID = function (id) {
  return `${id}`;
};

export { Endpoint };
