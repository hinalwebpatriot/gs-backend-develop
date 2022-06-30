export default function (margins) {
  return margins.reduce((groupedMargins, margin) => {
    const groupName = `${margin.carat_min} - ${margin.carat_max}`;

    if (!_.has(groupedMargins, groupName)) {
      groupedMargins[groupName] = [];
    }

    groupedMargins[groupName].push(margin);

    return groupedMargins;
  }, {});
}